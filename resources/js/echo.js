// Provide a minimal stub so Livewire doesn't warn if Echo loads late
if (typeof window !== 'undefined' && !window.Echo) {
  const chain = {
    listen() { return this; },
    listenForWhisper() { return this; },
    notification() { return this; },
    stopListening() { return this; },
    whisper() { return this; },
    here() { return this; },
    joining() { return this; },
    leaving() { return this; },
  };
  window.Echo = {
    channel() { return chain; },
    private() { return chain; },
    join() { return chain; },
  };
}

(() => {
  const safeInit = async () => {
    try {
      const Echo = (await import('laravel-echo')).default;
      const Pusher = (await import('pusher-js')).default;
      window.Pusher = Pusher;

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
        wsPath: import.meta.env.VITE_REVERB_PATH || undefined,
      });
    } catch (e) {
      console.warn('Realtime/Echo disabled:', e?.message || e);
    }
  };
  safeInit();
})();
