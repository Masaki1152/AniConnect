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
    centeredSlides: true,
    loop: true,
    breakpoints: {
        0: {
            slidesPerView: 2.1,
        },
        380: {
            slidesPerView: 2.2,
        },
        420: {
            slidesPerView: 2.4,
        },
        460: {
            slidesPerView: 2.6,
        },
        500: {
            slidesPerView: 2.8,
        },
        540: {
            slidesPerView: 3,
        },
        580: {
            slidesPerView: 3.2,
        },
        620: {
            slidesPerView: 3.4,
        },
        660: {
            slidesPerView: 3.6,
        },
        700: {
            slidesPerView: 3.8,
        },
        740: {
            slidesPerView: 4,
        },
        780: {
            slidesPerView: 4.2,
        },
    },
});
