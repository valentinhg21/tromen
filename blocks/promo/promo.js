window.addEventListener('DOMContentLoaded', () => {

    splideCarrousel = new Splide( '.splide.splide-promo',{
        type   : 'loop',
        perPage: 1,
        arrows: true,
        pagination: false,
        gap: '3rem',
        breakpoints: {
            878: {
                arrows: false,
                perPage: 1,
                pagination: true,
            },
            720: {
                perPage: 1,
            }
      }
    }).mount();
})