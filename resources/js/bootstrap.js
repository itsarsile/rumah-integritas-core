import axios from 'axios';
window.axios = axios;

// Include cookies for same-origin auth (broadcasting/auth)
window.axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Send CSRF token if present in the page <head>
const csrfMeta = typeof document !== 'undefined' ? document.head.querySelector('meta[name="csrf-token"]') : null;
if (csrfMeta && csrfMeta.content) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfMeta.content;
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

// Dev-only Echo logger
if (import.meta.env.DEV) {
  import('./echo-dev-logger.js');
}
