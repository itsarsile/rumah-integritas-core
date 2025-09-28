import './bootstrap';

// Carousel controls + autoplay for DaisyUI carousels

function initDaisyCarousels() {
  const carousels = document.querySelectorAll('[data-carousel-scope] .carousel');
  carousels.forEach((carousel) => {
    if (carousel._daisyCarouselInit) return;

    const scope = carousel.closest('[data-carousel-scope]') || carousel.parentElement;
    const items = Array.from(carousel.querySelectorAll('.carousel-item'));
    if (items.length <= 1) {
      carousel._daisyCarouselInit = true;
      return;
    }

    let index = 0;
    const scrollToIndex = (i, behavior = 'smooth') => {
      index = (i + items.length) % items.length;
      const target = items[index];
      if (!target) return;
      carousel.scrollTo({ left: target.offsetLeft, behavior });
    };

    const prevBtn = scope.querySelector('[data-carousel-prev]');
    const nextBtn = scope.querySelector('[data-carousel-next]');
    if (prevBtn) prevBtn.addEventListener('click', () => scrollToIndex(index - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => scrollToIndex(index + 1));

    const delayAttr = scope.getAttribute('data-carousel-interval') || '4000';
    let delay = parseInt(delayAttr, 10);
    if (!Number.isFinite(delay) || delay < 1000) delay = 4000;

    let timer = setInterval(() => scrollToIndex(index + 1), delay);
    const resetTimer = () => {
      clearInterval(timer);
      timer = setInterval(() => scrollToIndex(index + 1), delay);
    };

    carousel.addEventListener('mouseenter', () => clearInterval(timer));
    carousel.addEventListener('mouseleave', resetTimer);

    // Keep index in sync with manual scroll
    let scrollDebounce;
    carousel.addEventListener('scroll', () => {
      clearTimeout(scrollDebounce);
      scrollDebounce = setTimeout(() => {
        const left = carousel.scrollLeft;
        let nearest = 0;
        let min = Infinity;
        items.forEach((el, i) => {
          const d = Math.abs(el.offsetLeft - left);
          if (d < min) { min = d; nearest = i; }
        });
        index = nearest;
      }, 100);
    });

    // Initial position
    scrollToIndex(0, 'auto');

    carousel._daisyCarouselInit = true;
  });
}

document.addEventListener('DOMContentLoaded', initDaisyCarousels);
window.addEventListener('load', initDaisyCarousels);

if (window.Livewire) {
  window.addEventListener('livewire:load', () => {
    initDaisyCarousels();
    window.Livewire.hook('message.processed', () => initDaisyCarousels());
  });
}
// No JS needed for DaisyUI carousel
