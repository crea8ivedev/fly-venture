import.meta.glob([
    '../images/**',
    '../fonts/**',
  ]);
  
  import $ from 'jquery';
  
  // Only expose if needed
  window.$ = $;
  window.jQuery = $;
  
  import { initHeader } from './header.js';
  import script from './script.js';
  
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
  
  // -----------------------------
  // DOM Ready
  // -----------------------------
  document.addEventListener('DOMContentLoaded', function () {
  
    // Always load
    initHeader();
    script();
  
    // -----------------------------
    // CONDITIONAL LOADING
    // -----------------------------
  
    // Animations - load when fadeText elements exist
    if (document.querySelector('.fadeText')) {
      // Use requestIdleCallback for better performance
      if ('requestIdleCallback' in window) {
        requestIdleCallback(() => loadAnimations(), { timeout: 2000 });
      } else {
        setTimeout(loadAnimations, 100);
      }
    }
  
    // Sliders
    if (document.querySelector('.swiper, .swiper-container')) {
      import('./slider.js').then(({ initSliders }) => {
        initSliders();
      });
    }
  
    // FAQ
    if (document.querySelector('.faq-list')) {
      import('./faq.js').then((mod) => mod.default());
    }
  
    // Popular Tours
    if (document.querySelector('.popular-tours-wrap')) {
      import('./popularTours.js').then((mod) => mod.default());
    }
  
    // Offer Popup
    if (document.querySelector('.fv-offer-popup')) {
      import('./popupOffer.js').then(({ initOfferPopup }) => {
        initOfferPopup();
      });
    }
  
    // Gallery
    if (document.querySelector('.garely-grid')) {
      import('./gallery.js').then(({ initGalleryGrid }) => {
        initGalleryGrid();
      });
    }
  
    // Tabs
    if (document.querySelector('.tour-overview-section') || document.querySelector('.ctm-popular-gift')) {
      import('./tab.js').then(({ initTourOverview, initGiftCardTabs }) => {
        initTourOverview();
        initGiftCardTabs();
      });
    }
  
    // Blog
    if (document.querySelector('.blog-listing-section')) {
      import('./blogListing.js').then((mod) => mod.default());
    }
  
    // Map
    if (document.querySelector('#county-map-svg')) {
      import('./countyMap.js').then(({ initCountyMap }) => {
        initCountyMap();
      });
    }
  
    // Employment
    if (document.querySelector('.employment-filter')) {
      import('./employment.js').then((mod) => mod.default());
    }
  
    // -----------------------------
    // SMALL INLINE FEATURES
    // -----------------------------
  
    const heroPriceClose = document.querySelector('.hero-price-close');
    const heroPriceBox = document.querySelector('.hero-price-box');
  
    if (heroPriceClose && heroPriceBox) {
      heroPriceClose.addEventListener('click', function () {
        heroPriceBox.style.display = 'none';
      });
    }
  
    const partnerLogoStrip = document.querySelector('.partner-logo-strip');
    const mobileStickyBtn = document.querySelector('.mobile-sticky-btn');
    const footer = document.querySelector('.site-footer');
  
    if (partnerLogoStrip && mobileStickyBtn && footer) {
      const toggleStickyButton = () => {
        const rect = partnerLogoStrip.getBoundingClientRect();
        const isPast = rect.bottom < 0;
        const isFooterReached = window.scrollY + window.innerHeight >= footer.offsetTop + 20;
  
        if (isFooterReached) {
          mobileStickyBtn.classList.remove('is-sticky');
          mobileStickyBtn.classList.add('in-footer');
          footer.classList.remove('mobile-sticky-active');
        } else if (isPast) {
          mobileStickyBtn.classList.add('is-sticky');
          mobileStickyBtn.classList.remove('in-footer');
          footer.classList.add('mobile-sticky-active');
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