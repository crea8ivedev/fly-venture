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

    // Inject a dedicated toggle button after each parent menu item's link
    menuItems.forEach((menuItem) => {
      const submenuWrapper = menuItem.querySelector('.submenu-wrapper');
      if (submenuWrapper) {
        const link = menuItem.querySelector(':scope > a');
        if (link && !menuItem.querySelector('.submenu-toggle')) {
          const toggleBtn = document.createElement('button');
          toggleBtn.className = 'submenu-toggle';
          toggleBtn.type = 'button';
          toggleBtn.setAttribute('aria-label', 'Toggle submenu');
          link.insertAdjacentElement('afterend', toggleBtn);
          menuItem.classList.add('has-submenu-toggle');
        }
      }
    });

    const updateSubmenuBehavior = () => {
      isMobile = window.innerWidth < 1200;

      menuItems.forEach((menuItem) => {
        const submenuWrapper = menuItem.querySelector('.submenu-wrapper');
        const toggleBtn = menuItem.querySelector('.submenu-toggle');

        if (submenuWrapper) {
          // Remove existing event listeners
          menuItem.removeEventListener('mouseenter', handleMouseEnter);
          menuItem.removeEventListener('mouseleave', handleMouseLeave);
          if (toggleBtn) {
            toggleBtn.removeEventListener('click', handleClick);
          }

          if (isMobile) {
            // Mobile: only the toggle button opens/closes the submenu
            if (toggleBtn) {
              toggleBtn.addEventListener('click', handleClick);
            }
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
      e.stopPropagation();
      const menuItem = e.currentTarget.closest('li');
      if (menuItem) {
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

  // Announcement countdown timer — targets the empty <span> placed inside announcement_text
  const announcementItems = document.querySelectorAll('.announcement-item[data-countdown-end]');
  if (announcementItems.length) {
    // ACF returns "DD/MM/YYYY H:MM am/pm" — parse manually for cross-browser reliability
    const rawEnd = announcementItems[0].dataset.countdownEnd;
    const [datePart, timePart, period] = rawEnd.split(' ');
    const [day, month, year] = datePart.split('/').map(Number);
    const [rawHour, minute]  = timePart.split(':').map(Number);
    const hour = period?.toLowerCase() === 'pm' && rawHour !== 12 ? rawHour + 12
               : period?.toLowerCase() === 'am' && rawHour === 12 ? 0
               : rawHour;
    const endTime = new Date(year, month - 1, day, hour, minute).getTime();
    const spans   = [...announcementItems].map(el => el.querySelector('span')).filter(Boolean);
    const pad     = (n) => String(n).padStart(2, '0');

    const tick = () => {
      const diff = endTime - Date.now();
      const days    = Math.floor(diff / 86400000);
      const hours   = Math.floor((diff % 86400000) / 3600000);
      const minutes = Math.floor((diff % 3600000) / 60000);
      const seconds = Math.floor((diff % 60000) / 1000);
      const text = diff <= 0
        ? '00h 00m 00s'
        : (days > 0 ? `${pad(days)}d ` : '') + `${pad(hours)}h ${pad(minutes)}m ${pad(seconds)}s`;

      spans.forEach(span => { span.textContent = text; });

      if (diff > 0) setTimeout(tick, 1000);
    };

    tick();
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
