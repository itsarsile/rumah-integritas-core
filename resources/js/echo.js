import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Make Pusher available globally for Echo's Reverb connector
window.Pusher = Pusher;

try {
  const providedHost = (import.meta.env.VITE_REVERB_HOST || '').trim();
  const providedScheme = (import.meta.env.VITE_REVERB_SCHEME || '').trim().replace(':', '');
  const providedPort = Number(import.meta.env.VITE_REVERB_PORT);

  const hasCustomHost = !!providedHost;
  const scheme = providedScheme || (typeof window !== 'undefined' && window.location?.protocol === 'https:' ? 'https' : 'http');

  let host;
  let port;
  if (hasCustomHost) {
    // Local/dev typical: connect directly to Reverb on provided host
    host = providedHost;
    port = Number.isFinite(providedPort) ? providedPort : 8080;
  } else {
    // Production typical: same-origin through Nginx proxy at /app
    host = typeof window !== 'undefined' ? window.location.hostname : 'localhost';
    const pagePort = typeof window !== 'undefined' && window.location.port ? Number(window.location.port) : undefined;
    port = Number.isFinite(providedPort) ? providedPort : (pagePort || (scheme === 'https' ? 443 : 80));
  }

  const wsPath = (import.meta.env.VITE_REVERB_PATH || '').trim();
  window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: host,
    wsPort: port,
    wssPort: port,
    forceTLS: scheme === 'https',
    enabledTransports: ['ws', 'wss'],
    // Only set wsPath when overriding the default
    wsPath: wsPath && wsPath !== '/app' ? wsPath : undefined,
  });

  // Helpful diagnostics in dev
  if (import.meta.env.DEV && window.Echo?.connector?.pusher?.connection) {
    const conn = window.Echo.connector.pusher.connection;
    conn.bind('state_change', (s) => console.debug('[Echo] state', s.previous, '->', s.current));
    conn.bind('error', (e) => console.error('[Echo] error', e));
  }
} catch (e) {
  console.warn('Realtime/Echo disabled:', e?.message || e);
}
