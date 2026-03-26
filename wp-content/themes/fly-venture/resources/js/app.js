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

document.addEventListener('DOMContentLoaded', function () {
  initHeader();
  initAnimations();
  initSliders();
  initFAQ();
  initPopularTours();
  script();
  initOfferPopup();
});
