import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Derive sane defaults for host/scheme/port when Vite vars are not set correctly in prod
const detectedScheme = typeof window !== 'undefined' && window.location?.protocol === 'https:' ? 'https' : 'http';
const scheme = (import.meta.env.VITE_REVERB_SCHEME || detectedScheme).replace(':', '');
const defaultPort = scheme === 'https' ? 443 : 80;
const host = import.meta.env.VITE_REVERB_HOST || (typeof window !== 'undefined' ? window.location.hostname : 'localhost');
const port = Number(import.meta.env.VITE_REVERB_PORT) || defaultPort;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: host,
    wsPort: port,
    wssPort: port,
    forceTLS: scheme === 'https',
    enabledTransports: ['ws', 'wss'],
    // If you configure a custom server path in Nginx, expose it via Vite and pass as wsPath
    // wsPath: import.meta.env.VITE_REVERB_PATH || undefined,
});
