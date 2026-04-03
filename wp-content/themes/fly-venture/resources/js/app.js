import.meta.glob([
  '../images/**',
  '../fonts/**',
]);
import $ from 'jquery';

import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
import Lenis from "@studio-freight/lenis";
import script from './script.js';



// Define globally
window.$ = $;
window.jQuery = $;
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;
window.Lenis = Lenis;


import { initSliders } from './slider.js';
import { initHeader } from './header.js';
import initAnimations from './animation.js';
import initFAQ from './faq.js';
import initPopularTours from './popularTours.js';
import { initOfferPopup } from './popupOffer.js';
import { initGalleryGrid } from './gallery.js';
import { initTourOverview , initGiftCardTabs } from './tab.js';
import initBlogListing from './blogListing.js';
import { initCountyMap } from './countyMap.js';
import initEmploymentFilters from './employment.js';

document.addEventListener('DOMContentLoaded', function () {
  initHeader();
  initAnimations();
  initSliders();
  initFAQ();
  initPopularTours();
  script();
  initOfferPopup();
  initGalleryGrid();
  initTourOverview();
  initBlogListing();
    initCountyMap();
    initEmploymentFilters();
    initGiftCardTabs();

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
        toggleStickyButton(); // Check initial state
    }
    const btn = document.querySelector('.btn-back-top');
    if (btn) {
        btn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});
