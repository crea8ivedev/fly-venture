import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

import $ from 'jquery';
window.$ = $;
window.jQuery = $;

import { initHeader } from './header.js';
import script from './script.js';
import 'lazysizes';
import 'lazysizes/plugins/parent-fit/ls.parent-fit';

// -----------------------------
// Dynamic Animation Loader
// -----------------------------
function loadAnimations() {
  Promise.all([
    import('gsap'),
    import('gsap/ScrollTrigger'),
    import('@studio-freight/lenis'),
    import('./animation.js'),
  ]).then(([gsapModule, ScrollTriggerModule, LenisModule, animationModule]) => {
    const gsap = gsapModule.default;
    const ScrollTrigger = ScrollTriggerModule.ScrollTrigger || ScrollTriggerModule.default;
    const Lenis = LenisModule.default;
    gsap.registerPlugin(ScrollTrigger);
    animationModule.default(gsap, ScrollTrigger, Lenis);
  });
}

// Load a module once an element enters (or is near) the viewport.
function whenVisible(selector, loader, rootMargin = '300px') {
  const el = document.querySelector(selector);
  if (!el) return;
  const obs = new IntersectionObserver((entries, o) => {
    if (entries[0].isIntersecting) {
      o.disconnect();
      loader();
    }
  }, { rootMargin });
  obs.observe(el);
}

// -----------------------------
// DOMContentLoaded — critical UI only
// -----------------------------
document.addEventListener('DOMContentLoaded', function () {

  initHeader();
  script();

  // Announcement slider is in the header (above fold) — must init Swiper immediately
  if (document.querySelector('#announcement-slider')) {
    import('./slider.js').then(({ initSliders }) => initSliders());
  }

  // Hero price-box close button
  const heroPriceClose = document.querySelector('.hero-price-close');
  const heroPriceBox   = document.querySelector('.hero-price-box');
  if (heroPriceClose && heroPriceBox) {
    heroPriceClose.addEventListener('click', function () {
      heroPriceBox.style.display = 'none';
    });
  }

  // Mobile sticky button vs footer
  const partnerLogoStrip = document.querySelector('.partner-logo-strip');
  const mobileStickyBtn  = document.querySelector('.mobile-sticky-btn');
  const footer           = document.querySelector('.site-footer');
  if (partnerLogoStrip && mobileStickyBtn && footer) {
    const toggleStickyButton = () => {
      const rect            = partnerLogoStrip.getBoundingClientRect();
      const isPast          = rect.bottom < 0;
      const isFooterReached = window.scrollY + window.innerHeight >= footer.offsetTop + 20;
      if (isFooterReached) {
        mobileStickyBtn.classList.remove('is-sticky');
        mobileStickyBtn.classList.add('in-footer');
        footer.classList.remove('mobile-sticky-active');
      } else if (isPast) {
        mobileStickyBtn.classList.add('is-sticky');
        mobileStickyBtn.classList.remove('in-footer');
        footer.classList.remove('mobile-sticky-active');
      } else {
        mobileStickyBtn.classList.remove('is-sticky', 'in-footer');
        footer.classList.remove('mobile-sticky-active');
      }
    };
    window.addEventListener('scroll', toggleStickyButton, { passive: true });
    toggleStickyButton();
  }

  const btn = document.querySelector('.btn-back-top');
  if (btn) {
    btn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

});

// -----------------------------
// window.load — non-critical modules
// Runs after all resources (images, stylesheets) have finished loading,
// pushing heavy JS evaluation past the LCP / TBT measurement window.
// -----------------------------
window.addEventListener('load', function () {

  // GSAP + Lenis: wait for true idle time (no forced timeout)
  if (document.querySelector('.fadeText')) {
    if ('requestIdleCallback' in window) {
      requestIdleCallback(loadAnimations);
    } else {
      setTimeout(loadAnimations, 200);
    }
  }

  // Swipers not already handled by the announcement slider init above
  if (!document.querySelector('#announcement-slider')) {
    whenVisible('.swiper, .swiper-container', () => {
      import('./slider.js').then(({ initSliders }) => initSliders());
    });
  }

  // Below-fold sections: load when they scroll into view
  whenVisible('.faq-list', () => {
    import('./faq.js').then((mod) => mod.default());
  });

  whenVisible('.popular-tours-wrap', () => {
    import('./popularTours.js').then((mod) => mod.default());
  });

  whenVisible('.garely-grid', () => {
    import('./gallery.js').then(({ initGalleryGrid }) => initGalleryGrid());
  });

  whenVisible('.tour-overview-section', () => {
    import('./tab.js').then(({ initTourOverview, initGiftCardTabs }) => {
      initTourOverview();
      initGiftCardTabs();
    });
  });

  whenVisible('.ctm-popular-gift', () => {
    import('./tab.js').then(({ initTourOverview, initGiftCardTabs }) => {
      initTourOverview();
      initGiftCardTabs();
    });
  });

  whenVisible('.blog-listing-section', () => {
    import('./blogListing.js').then((mod) => mod.default());
  });

  whenVisible('#county-map-svg', () => {
    import('./countyMap.js').then(({ initCountyMap }) => initCountyMap());
  });

  whenVisible('.employment-filter', () => {
    import('./employment.js').then((mod) => mod.default());
  });

  // Offer popup: not tied to scroll position, but non-critical
  if (document.querySelector('.fv-offer-popup')) {
    import('./popupOffer.js').then(({ initOfferPopup }) => initOfferPopup());
  }

});
