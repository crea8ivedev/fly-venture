

/*global, Swiper*/
import Swiper from 'swiper/bundle';
export const initSliders = () => {
  const sliders = {
    announcement: null,
    partners: null,
    customExperience: null,
  };

  if (typeof Swiper !== 'function') {
    return sliders;
  }

  const announcementSliderElement = document.querySelector('.announcement-slider');
  const partnerSliderElement = document.querySelector('.partner-logo-slider');
  const customExperienceSliderElement = document.querySelector('.custom-experience-slider');

  if (announcementSliderElement) {
    const announcementWrapper = announcementSliderElement.querySelector('.swiper-wrapper');
    const announcementSlides = announcementWrapper
      ? Array.from(announcementWrapper.children)
      : [];

    if (announcementWrapper && announcementSlides.length && !announcementSliderElement.dataset.marqueeReady) {
      const duplicates = document.createDocumentFragment();

      announcementSlides.forEach((slide) => {
        const clone = slide.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        duplicates.appendChild(clone);
      });

      announcementWrapper.appendChild(duplicates);
      announcementSliderElement.dataset.marqueeReady = 'true';
    }

    announcementSliderElement.classList.add('is-marquee');
  }

  if (partnerSliderElement) {
    const partnerWrapper = partnerSliderElement.querySelector('.swiper-wrapper');
    const partnerSlides = partnerWrapper
      ? Array.from(partnerWrapper.children)
      : [];

    if (partnerWrapper && partnerSlides.length && !partnerSliderElement.dataset.marqueeReady) {
      const duplicates = document.createDocumentFragment();

      partnerSlides.forEach((slide) => {
        const clone = slide.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        duplicates.appendChild(clone);
      });

      partnerWrapper.appendChild(duplicates);
      partnerSliderElement.dataset.marqueeReady = 'true';
    }

    partnerSliderElement.classList.add('is-marquee');
  }

  if (customExperienceSliderElement) {
    const customExperienceProgress = customExperienceSliderElement.querySelector('.custom-experience-progress');

    sliders.customExperience = new Swiper(customExperienceSliderElement, {
      slidesPerView: 1,
      spaceBetween: 20,
      speed: 500,
      loop: false,
      pagination: customExperienceProgress
        ? {
          el: customExperienceProgress,
          type: 'progressbar',
        }
        : undefined,
      breakpoints: {
        575: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 2.5,
          spaceBetween: 24,
        },
        1023:{
          slidesPerView: 3,
          spaceBetween: 24,
        },
        1300: {
          slidesPerView: 4,
          spaceBetween: 30,
        },
        1600: {
          slidesPerView: 4,
          spaceBetween: 44,
        },
      },
    });
  }

  return sliders;
};
