import './bootstrap';

// Swiper.js for login slider (bundled via Vite)
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

function initSwiperSliders() {
  const sliders = document.querySelectorAll('.swiper');
  sliders.forEach((el) => {
    if (el._swiperInstance) return; // already initialized

    const slidesCount = el.querySelectorAll('.swiper-wrapper > .swiper-slide').length;
    const hasMultiple = slidesCount > 1;

    const instance = new Swiper(el, {
      modules: [Navigation, Pagination, Autoplay],
      slidesPerView: 1,
      spaceBetween: 0,
      speed: 600,
      loop: hasMultiple,
      autoplay: hasMultiple
        ? { delay: 4000, disableOnInteraction: false, pauseOnMouseEnter: true }
        : false,
      navigation: hasMultiple
        ? {
            nextEl: el.querySelector('.swiper-button-next'),
            prevEl: el.querySelector('.swiper-button-prev'),
          }
        : false,
      pagination: hasMultiple
        ? {
            el: el.querySelector('.swiper-pagination'),
            clickable: true,
          }
        : false,
    });

    el._swiperInstance = instance;
  });
}

document.addEventListener('DOMContentLoaded', initSwiperSliders);
window.addEventListener('load', initSwiperSliders);

if (window.Livewire) {
  window.addEventListener('livewire:load', () => {
    initSwiperSliders();
    window.Livewire.hook('message.processed', () => initSwiperSliders());
  });
}
