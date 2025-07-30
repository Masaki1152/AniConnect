import Swiper from 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.mjs';

const desktopSwiper = new Swiper('.swiper-desktop', {
    loop: true,
    autoplay: {
        delay: 10000,
    },
    navigation: {
        nextEl: '.swiper-desktop .swiper-button-next',
        prevEl: '.swiper-desktop .swiper-button-prev',
    },
    pagination: {
        el: '.swiper-desktop .swiper-pagination',
        clickable: true,
    },
});

const mobileSwiper = new Swiper('.swiper-mobile', {
    autoplay: {
        delay: 5000,
    },
    spaceBetween: 8,
    breakpoints: {
        0: {
            slidesPerView: 1.7,
        },
        380: {
            slidesPerView: 1.8,
        },
        420: {
            slidesPerView: 2,
        },
        460: {
            slidesPerView: 2.2,
        },
        500: {
            slidesPerView: 2.4,
        },
        540: {
            slidesPerView: 2.6,
        },
        580: {
            slidesPerView: 2.8,
        },
        620: {
            slidesPerView: 3,
        },
        660: {
            slidesPerView: 3.2,
        },
        700: {
            slidesPerView: 3.4,
        },
        740: {
            slidesPerView: 3.6,
        },
        780: {
            slidesPerView: 3.8,
        },
    },
});
