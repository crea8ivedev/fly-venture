export const initHeader = () => {
  const headerElement = document.getElementById('site-header');
  const announcementBarElement = document.querySelector('.announcement-bar');
  const announcementSliderElement = document.getElementById('announcement-slider');
  const menuToggleButton = document.getElementById('menu-toggle');
  const mobileMenuLinks = document.querySelectorAll('.main-navigation .menu li:not(.menu-item-has-children) a');
  let isStickyState = false;

  // Submenu functionality
  if (headerElement) {
    // Wrap submenu ul elements with wrapper div
    const submenuUls = document.querySelectorAll('.main-navigation .menu > li > ul');
    submenuUls.forEach((ul) => {
      const wrapper = document.createElement('div');
      wrapper.className = 'submenu-wrapper';
      ul.parentNode.insertBefore(wrapper, ul);
      wrapper.appendChild(ul);
    });

    const menuItems = document.querySelectorAll('.main-navigation .menu > li');
    let isMobile = window.innerWidth < 1200;

    const updateSubmenuBehavior = () => {
      isMobile = window.innerWidth < 1200;

      menuItems.forEach((menuItem) => {
        const submenuWrapper = menuItem.querySelector('.submenu-wrapper');

        if (submenuWrapper) {
          // Remove existing event listeners
          menuItem.removeEventListener('mouseenter', handleMouseEnter);
          menuItem.removeEventListener('mouseleave', handleMouseLeave);
          menuItem.removeEventListener('click', handleClick);

          if (isMobile) {
            // Mobile: click to toggle
            menuItem.addEventListener('click', handleClick);
          } else {
            // Desktop: hover to show/hide
            menuItem.addEventListener('mouseenter', handleMouseEnter);
            menuItem.addEventListener('mouseleave', handleMouseLeave);
          }
        }
      });
    };

    const handleMouseEnter = (e) => {
      const submenuWrapper = e.currentTarget.querySelector('.submenu-wrapper');
      if (submenuWrapper) {
        submenuWrapper.classList.add('active');
      }
    };

    const handleMouseLeave = (e) => {
      const submenuWrapper = e.currentTarget.querySelector('.submenu-wrapper');
      if (submenuWrapper) {
        submenuWrapper.classList.remove('active');
      }
    };

    const handleClick = (e) => {
      const menuItem = e.currentTarget;
      const submenuWrapper = menuItem.querySelector('.submenu-wrapper');

      if (submenuWrapper) {
        e.preventDefault();
        e.stopPropagation(); // Prevent event bubbling
        menuItem.classList.toggle('submenu-open');
      }
    };

    // Initial setup
    updateSubmenuBehavior();

    // Update on resize
    window.addEventListener('resize', updateSubmenuBehavior);
  }


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
