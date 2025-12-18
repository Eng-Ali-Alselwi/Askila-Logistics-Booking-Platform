const slider = () => {
  const swiperElement = document.querySelector(".swiper");
  if (swiperElement) {


    var swiper = new Swiper(".university__slider__thumb", {
      spaceBetween: 10,
      slidesPerView: 4,
      freeMode: true,
      watchSlidesProgress: true,
      // autoplay: {
      //   delay: 5000,
      // },
    });
    // swiper slider
    var swiper2 = new Swiper(".ecommerce-slider2", {
      slidesPerView: 1,
      grabCursor: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      loop: true,
      autoplay: {
        delay: 3000,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      thumbs: {
        swiper: swiper,
      },
    });
  }
};


