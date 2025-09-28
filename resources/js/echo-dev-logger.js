// Dev-only helper to trace Echo usage without affecting production
// Loaded dynamically only when import.meta.env.DEV is true

(() => {
  if (!import.meta?.env?.DEV) return;

  const log = (...args) => console.debug('[Echo dev]', ...args);

  const wrapChannel = (name, ch) => {
    try {
      // Presence helpers for quick visibility
      if (typeof ch.here === 'function') {
        ch.here((users) => log('here', name, users));
      }
      if (typeof ch.joining === 'function') {
        ch.joining((user) => log('joining', name, user));
      }
      if (typeof ch.leaving === 'function') {
        ch.leaving((user) => log('leaving', name, user));
      }

      // Wrap listen-like methods to log bindings and payloads
      const wrapMethod = (method) => {
        if (typeof ch[method] !== 'function') return;
        const original = ch[method].bind(ch);
        ch[method] = (event, handler) => {
          log(`${method}`, name, 'bind ->', event);
          const wrapped = (...args) => {
            try { log('event', name, event, args?.[0] ?? args); } catch {}
            return typeof handler === 'function' ? handler(...args) : undefined;
          };
          const res = original(event, wrapped);
          return res || ch; // keep chaining behavior
        };
      };

      ['listen', 'notification', 'listenForWhisper'].forEach(wrapMethod);
    } catch (e) {
      // never break the app in dev
      console.warn('[Echo dev] wrap error', e);
    }
    return ch;
  };

  const install = () => {
    if (!window.Echo) return false;
    try {
      // Connection state logs
      const p = window.Echo?.connector?.pusher;
      const conn = p?.connection;
      if (conn && !conn.__echoDevPatched) {
        conn.__echoDevPatched = true;
        conn.bind('state_change', (s) => log('state', s.previous, '->', s.current));
        conn.bind('error', (e) => console.error('[Echo dev] error', e));
      }

      // Patch join/channel/private to wrap returned channels
      const patch = (method) => {
        if (typeof window.Echo[method] !== 'function' || window.Echo[method].__echoDevPatched) return;
        const original = window.Echo[method].bind(window.Echo);
        window.Echo[method] = (name, ...rest) => {
          log(method, name);
          const ch = original(name, ...rest);
          return wrapChannel(name, ch);
        };
        window.Echo[method].__echoDevPatched = true;
      };

      ['join', 'private', 'channel'].forEach(patch);

      // Expose a quick spy helper
      window.__echoSpy = (name, type = 'join') => {
        if (!window.Echo) return console.warn('[Echo dev] Echo not ready');
        const fn = window.Echo[type] || window.Echo.join;
        const ch = fn.call(window.Echo, name);
        log('spy attached', type, name);
        // common presence hooks already set in wrapChannel; add a catch-all
        if (typeof ch.listen === 'function') ch.listen('*', (e) => log('wildcard', name, e));
        return ch;
      };

      return true;
    } catch (e) {
      console.warn('[Echo dev] install error', e);
      return false;
    }
  };

  // Wait for Echo to be ready
  let tries = 0;
  const t = setInterval(() => {
    if (install() || ++tries > 100) clearInterval(t);
  }, 100);
})();

