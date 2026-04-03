import Swiper from 'swiper/bundle';

export const initGalleryGrid = () => {
    if (typeof Swiper !== 'function') {
        return;
    }

    const galleries = document.querySelectorAll('.garely-grid');

    galleries.forEach((gallery) => {
        const galleryTopElement = gallery.querySelector('.eco-gallery-top');
        const galleryThumbsElement = gallery.querySelector('.eco-gallery-thumbs');

        if (!galleryTopElement || !galleryThumbsElement) {
            return;
        }

        // Initialize thumbs FIRST — thumbs swiper must exist before main swiper references it
        const galleryThumbs = new Swiper(galleryThumbsElement, {
            direction: 'horizontal',
            slidesPerView: 3,
            slideToClickedSlide: true,
            spaceBetween: 20,
            watchSlidesProgress: true,  // Required for thumbs to work correctly
            loop: false,                // Disable loop on thumbs — avoids index offset bugs
            breakpoints: {
                1200: {
                    direction: 'vertical',
                },
                769: {
                    spaceBetween: 20,
                    slidesPerView: 4,
                    direction: 'horizontal',
                },
                639: {
                    spaceBetween: 20,
                    slidesPerView: 3,
                    direction: 'horizontal',
                },
                450: {
                    spaceBetween: 12,
                    slidesPerView: 3,
                    direction: 'horizontal',
                },
                320: {
                    slidesPerView: 2,
                    spaceBetween: 12,
                    direction: 'horizontal',
                },
            },
        });

        // Initialize main swiper with thumbs module — NOT controller
        const galleryTop = new Swiper(galleryTopElement, {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            thumbs: {
                swiper: galleryThumbs,
            },
        });
    });
};