/* eslint-disable no-undef */

const initAnimations = (gsap, ScrollTrigger, Lenis) => {

  // -----------------------------
  // Lenis (ONLY DESKTOP)
  // -----------------------------
  let lenis;

  if (window.innerWidth > 768) {
    lenis = new Lenis({
      smoothWheel: true,
      smoothTouch: false,
      lerp: 0.06,
      wheelMultiplier: 0.8,
      duration: 1.4,
      easing: (t) => 1 - Math.pow(1 - t, 3),
    });

    lenis.on('scroll', ScrollTrigger.update);

    gsap.ticker.add((time) => {
      lenis.raf(time * 1000);
    });

    gsap.ticker.lagSmoothing(0);

    ScrollTrigger.addEventListener('refresh', () => lenis.resize());
  }

  // -----------------------------
  // Smooth anchor scroll (safe)
  // -----------------------------
  if (!window.anchorSmoothInit) {
    window.anchorSmoothInit = true;

    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener('click', (e) => {
        const hash = anchor.getAttribute('href');
        if (hash === '#') return;

        const target = document.querySelector(hash);
        if (!target) return;

        e.preventDefault();

        if (lenis) {
          lenis.scrollTo(target, { offset: 0 });
        } else {
          target.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });
  }

  // -----------------------------
  // Fade Text Animation
  // -----------------------------
  const fadeTexts = document.querySelectorAll('.fadeText');

  if (fadeTexts.length) {
    fadeTexts.forEach((el) => {
      if (!el.children.length) return;

      gsap.fromTo(
        el.children,
        {
          y: 50,
          opacity: 0,
        },
        {
          y: 0,
          opacity: 1,
          duration: 0.5,
          stagger: 0.1,
          ease: 'power2.out',
          scrollTrigger: {
            trigger: el,
            start: 'top 80%',
            toggleActions: 'play none none none',
          },
        }
      );
    });

    // Lazy-loaded images change the page height after GSAP initializes,
    // causing ScrollTrigger's trigger-position calculations to be stale.
    // Refresh once immediately and again whenever a lazy image loads.
    ScrollTrigger.refresh();
    document.addEventListener('lazyloaded', () => ScrollTrigger.refresh());
  }

  // -----------------------------
  // Optimize for mobile (lighter animations)
  // -----------------------------
  if (window.innerWidth < 768) {
    ScrollTrigger.defaults({
      toggleActions: 'play none none none',
    });
  }

};

export default initAnimations;