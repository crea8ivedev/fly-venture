export const initHeader = () => {
  const headerElement = document.getElementById('site-header');
  const announcementBarElement = document.querySelector('.announcement-bar');
  const announcementSliderElement = document.getElementById('announcement-slider');
  const menuToggleButton = document.getElementById('menu-toggle');
  const mobileMenuLinks = document.querySelectorAll('.main-navigation .menu li a');
  let isStickyState = false;


  if (headerElement) {
    const toggleStickyHeader = () => {
      const shouldBeSticky = window.scrollY > 30;

      if (shouldBeSticky === isStickyState) {
        return;
      }

      isStickyState = shouldBeSticky;
      headerElement.classList.toggle('is-sticky', shouldBeSticky);
      announcementBarElement?.classList.toggle('is-sticky', shouldBeSticky);

      const announcementSwiper = announcementSliderElement?.swiper;
      if (announcementSwiper?.autoplay) {
        window.requestAnimationFrame(() => {
          announcementSwiper.update();
          announcementSwiper.autoplay.start();
        });
      }
    };

    toggleStickyHeader();
    window.addEventListener('scroll', toggleStickyHeader, { passive: true });
  }

  if (headerElement && menuToggleButton) {
    const closeMenu = () => {
      headerElement.classList.remove('menu-open');
      menuToggleButton.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('menu-open');
    };

    const openMenu = () => {
      headerElement.classList.add('menu-open');
      menuToggleButton.setAttribute('aria-expanded', 'true');
      document.body.classList.add('menu-open');
    };

    menuToggleButton.addEventListener('click', () => {
      if (headerElement.classList.contains('menu-open')) {
        closeMenu();
        return;
      }

      openMenu();
    });

    mobileMenuLinks.forEach((menuLink) => {
      menuLink.addEventListener('click', closeMenu);
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth > 1199) {
        closeMenu();
      }
    });
  }

};
