import Swiper from 'swiper/bundle';


const normalizeCity = (value) => {
  const city = String(value || '').trim().toLowerCase();

  if (city === 'tempa') {
    return 'tampa';
  }

  return city;
};

const initPopularTours = () => {
  const section = document.querySelector('.popular-tours-wrap');

  if (!section || typeof Swiper !== 'function') {
    return;
  }

  const ajaxUrl = section.dataset.ajaxUrl || '';
  const ajaxNonce = section.dataset.ajaxNonce || '';
  const selectedTours = section.dataset.selectedTours || '';
  const $ajax = window.jQuery || window.$;
  const hasAjax = Boolean(ajaxUrl && ajaxNonce && $ajax);

  const tabs = Array.from(section.querySelectorAll('.popular-tour-tabs button[data-city]'));
  const wraps = Array.from(section.querySelectorAll('.popular-tour-cards-wrap[data-city]'));
  const ctaLinks = Array.from(section.querySelectorAll('.popular-tour-cta'));

  if (!tabs.length || !wraps.length) {
    return;
  }

  const wrapsByCity = new Map();
  const swipersByCity = new Map();
  const cityCache = new Map();
  let activeSwiper = null;
  let pendingRequest = null;

  wraps.forEach((wrap) => {
    const city = normalizeCity(wrap.dataset.city);
    wrapsByCity.set(city, wrap);
  });

  const updateCta = (city, tab) => {
    const ctaUrl    = tab?.dataset.ctaUrl    || tab?.dataset.cityLink || '';
    const ctaLabel  = tab?.dataset.ctaLabel  || `VIEW ALL ${city.toUpperCase()} TOURS`;
    const ctaTarget = tab?.dataset.ctaTarget || '_self';

    ctaLinks.forEach((link) => {
      link.textContent = ctaLabel.toUpperCase();
      link.target      = ctaTarget;
      link.setAttribute('aria-label', ctaLabel);
      link.dataset.city = city;
      if (ctaUrl) {
        link.setAttribute('href', ctaUrl);
      }
    });
  };

  const initCitySlider = (city) => {
    if (swipersByCity.has(city)) {
      return swipersByCity.get(city);
    }

    const wrap = wrapsByCity.get(city);
    const sliderElement = wrap?.querySelector('.popular-tour-slider');
    const progressElement = wrap?.querySelector('.popular-tour-progress');

    if (!wrap || !sliderElement || !progressElement) {
      return null;
    }

    const instance = new Swiper(sliderElement, {
      slidesPerView: 1,
      spaceBetween: 16,
      speed: 450,
      watchOverflow: false,
      scrollbar: {
        el: progressElement,
        draggable: true,
        dragSize: 'auto',
        snapOnRelease: true
      },
      breakpoints: {
        0:{
          slidesPerView: 1,
          spaceBetween: 20
        },
        621:{
          slidesPerView: 1.5,
          spaceBetween: 20
        },
        768: {
          slidesPerView: 1.8,
          spaceBetween: 20
        },
        1024: {
          slidesPerView: 1.5,
          spaceBetween: 24
        },
        1300: {
          slidesPerView: 2,
          spaceBetween: 34
        },
        // 1600: {
        //   slidesPerView: 2.6,
        //   spaceBetween: 44
        // }
      }

    });

    swipersByCity.set(city, instance);
    return instance;
  };

  const refreshSwiper = (city, wrap) => {
    swipersByCity.delete(city);
    wrapsByCity.set(city, wrap);
    wrap.dataset.city = city;

    const instance = (activeSwiper && !activeSwiper.destroyed)
      ? activeSwiper
      : initCitySlider(city);

    activeSwiper = instance;

    if (instance) {
      requestAnimationFrame(() => {  // ← only change
        instance.slideTo(0, 0);
        instance.update();
      });
    }
  };

  const setActiveTab = (city) => {
    tabs.forEach((tab) => {
      const tabCity = normalizeCity(tab.dataset.city);
      const isActive = tabCity === city;
      tab.classList.toggle('active', isActive);
      tab.setAttribute('aria-pressed', isActive ? 'true' : 'false');
    });
  };

  const loadCityTours = (city, wrap) => {
    const cards = wrap.querySelector('.popular-tour-cards');

    if (!cards || !hasAjax) {
      refreshSwiper(city, wrap);
      return;
    }

    if (cityCache.has(city)) {
      cards.innerHTML = cityCache.get(city);
      refreshSwiper(city, wrap);
      return;
    }

    if (pendingRequest && typeof pendingRequest.abort === 'function') {
      pendingRequest.abort();
    }

    wrap.setAttribute('aria-busy', 'true');

    pendingRequest = $ajax.ajax({
      url: ajaxUrl,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'flyventure_popular_tours',
        nonce: ajaxNonce,
        category: city,
        selected_tours: selectedTours
      }
    });

    pendingRequest.done((response) => {
      if (response && response.success && typeof response.data?.html === 'string') {
        cityCache.set(city, response.data.html);
        cards.innerHTML = response.data.html;
      }
    });

    pendingRequest.always(() => {
      wrap.removeAttribute('aria-busy');
      refreshSwiper(city, wrap);
    });
  };

  const showCity = (cityValue, tabEl = null) => {
    const city = normalizeCity(cityValue);
    const targetWrap = wrapsByCity.get(city) || wraps[0];

    if (!targetWrap) {
      return;
    }

    wraps.forEach((wrap) => {
      wrap.hidden = wrap !== targetWrap;
    });

    setActiveTab(city);
    const activeTab = tabEl || tabs.find((tab) => normalizeCity(tab.dataset.city) === city);
    updateCta(city, activeTab);

    if (hasAjax) {
      loadCityTours(city, targetWrap);
      return;
    }

    const instance = initCitySlider(city);
    activeSwiper = instance;
    if (instance) {
      instance.slideTo(0, 0);
      instance.update();
    }
  };

  tabs.forEach((tab) => {
    tab.setAttribute('aria-pressed', tab.classList.contains('active') ? 'true' : 'false');
    tab.addEventListener('click', () => {
      const city = normalizeCity(tab.dataset.city);
      showCity(city, tab);
    });
  });

  // const defaultCity = normalizeCity(section.querySelector('.popular-tour-tabs button.active')?.dataset.city) || 'sarasota';
  // showCity(defaultCity, section.querySelector('.popular-tour-tabs button.active'));

  const defaultCity =
    normalizeCity(section.querySelector('.popular-tour-tabs button.active')?.dataset.city) ||
    'sarasota';
  const defaultTab = section.querySelector('.popular-tour-tabs button.active');

  showCity(defaultCity, defaultTab);

  window.addEventListener('resize', () => {
    const activeTab = section.querySelector('.popular-tour-tabs button.active');
    const activeCity = normalizeCity(activeTab?.dataset.city);
    const currentSwiper = swipersByCity.get(activeCity) || activeSwiper;

    if (currentSwiper) {
      currentSwiper.update();
    }
  });
};

export default initPopularTours;
