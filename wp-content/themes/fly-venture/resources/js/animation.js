/* eslint-disable no-undef */

const initAnimations = () => {
  // -----------------------------
  // GSAP + ScrollTrigger
  // -----------------------------
  gsap.registerPlugin(ScrollTrigger);

  // -----------------------------
  // Lenis smooth scroll
  // -----------------------------
  const lenis = new Lenis(
    {
      smoothWheel: true,
      smoothTouch: false,
      lerp: 0.06,
      wheelMultiplier: 0.8,
      touchMultiplier: 1,
      duration: 1.4,
      easing: (t) => 1 - Math.pow(1 - t, 3)
    }
  );
  // Sync Lenis with GSAP ScrollTrigger
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add((time) => {
    lenis.raf(time * 1000);
  });
  gsap.ticker.lagSmoothing(0);

  ScrollTrigger.addEventListener('refresh', () => lenis.resize());

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', (e) => {
      const hash = anchor.getAttribute('href');
      if (hash === '#') return;
      const target = document.querySelector(hash);
      if (!target) return;
      e.preventDefault();
      lenis.scrollTo(target, { offset: 0 });
    });
  });

  // -----------------------------
  // Fade Text Animation (Reusable)
  // -----------------------------
  const fadeTexts = gsap.utils.toArray('.fadeText');

  fadeTexts.forEach((el) => {
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


};

export default initAnimations;
