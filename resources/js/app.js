import './bootstrap';

// Glide.js for login slider (bundled via Vite)
import Glide from '@glidejs/glide';
import '@glidejs/glide/dist/css/glide.core.min.css';
import '@glidejs/glide/dist/css/glide.theme.min.css';

function initGlideSliders() {
  const sliders = document.querySelectorAll('.glide');
  sliders.forEach((el) => {
    if (el._glideInstance) return; // already initialized

    const slidesCount = el.querySelectorAll('.glide__slides > .glide__slide').length;
    const hasMultiple = slidesCount > 1;

    const options = {
      type: 'carousel',
      perView: 1,
      gap: 0,
      animationDuration: 600,
      autoplay: hasMultiple ? 4000 : false,
      hoverpause: true,
    };

    const instance = new Glide(el, options);
    instance.mount();
    el._glideInstance = instance;
  });
}

document.addEventListener('DOMContentLoaded', initGlideSliders);
window.addEventListener('load', initGlideSliders);

if (window.Livewire) {
  window.addEventListener('livewire:load', () => {
    initGlideSliders();
    window.Livewire.hook('message.processed', () => initGlideSliders());
  });
}
