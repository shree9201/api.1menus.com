(function () {
  const AUTH_STORAGE_KEY = 'oneMenusSession';

  const EXPECTED_USERS = {
    // Internal routing roles:
    // - staff -> staff dashboard
    // - hod   -> manager dashboard (requested "Manager")
    // - hr    -> hr dashboard
    staff: { username: 'staff', password: 'staff', name: 'Ravi', prefix: 'Mr.' },
    hod: { username: 'manager', password: 'manager', name: 'Sunil', prefix: 'Mr.' },
    hr: { username: 'hr', password: 'hr', name: 'Priya', prefix: 'Ms.' },
  };

  const ROLE_ROUTES = {
    staff: 'staff/home',
    hod: 'hod/dashboard',
    hr: 'hr/dashboard',
  };

  const SUBTITLES = {
    staff: '1Menus Digital Staff',
    hod: 'Manager',
    hr: 'HR Manager',
  };

  function loadSession() {
    try {
      const raw = localStorage.getItem(AUTH_STORAGE_KEY);
      if (!raw) return null;
      const session = JSON.parse(raw);
      if (!session || typeof session !== 'object') return null;
      return session;
    } catch (e) {
      return null;
    }
  }

  function saveSession(session) {
    localStorage.setItem(AUTH_STORAGE_KEY, JSON.stringify(session));
  }

  function clearSession() {
    localStorage.removeItem(AUTH_STORAGE_KEY);
  }

  function greetingPart(d) {
    const h = d.getHours();
    if (h < 12) return 'Good morning';
    if (h < 17) return 'Good afternoon';
    return 'Good evening';
  }

  function applyGreeting(role) {
    const cfg = EXPECTED_USERS[role];
    if (!cfg) return;

    const title = `${greetingPart(new Date())} ${cfg.prefix} ${cfg.name}`;
    document.querySelectorAll('[data-greeting-title]').forEach((el) => {
      el.textContent = title;
    });
    document.querySelectorAll('[data-greeting-subtitle]').forEach((el) => {
      el.textContent = SUBTITLES[role] || el.textContent;
    });
  }

  function clearGreeting() {
    document.querySelectorAll('[data-greeting-title]').forEach((el) => {
      el.textContent = '';
    });
  }

  function getLoginFormValues() {
    const outletCode = document.getElementById('login-outlet-code')?.value?.trim() || '';
    const role = document.getElementById('login-role')?.value || '';
    const username = document.getElementById('login-username')?.value?.trim() || '';
    const password = document.getElementById('login-password')?.value || '';
    const autoLogin = !!document.getElementById('login-autologin')?.checked;
    return { outletCode, role, username, password, autoLogin };
  }

  function setLoginError(msg) {
    const el = document.getElementById('login-error');
    if (el) el.textContent = msg || '';
  }

  function loginSubmit(e) {
    e.preventDefault();
    setLoginError('');

    const { outletCode, role, username, password, autoLogin } = getLoginFormValues();
    const expected = EXPECTED_USERS[role];

    // Prototype-friendly: if omitted, use a default outlet.
    const normalizedOutletCode = outletCode || '101';
    if (Number.isNaN(Number(normalizedOutletCode))) {
      setLoginError('Outlet Code must be numeric.');
      return;
    }
    if (!expected) {
      setLoginError('Please select a valid role.');
      return;
    }

    const usernameNorm = String(username).trim().toLowerCase();
    const passwordNorm = String(password);

    // Prototype auth uses fixed credentials.
    if (usernameNorm !== expected.username || passwordNorm !== expected.password) {
      setLoginError('Invalid username or password for selected role.');
      return;
    }

    saveSession({
      outletCode: normalizedOutletCode,
      role,
      username: usernameNorm,
      autoLogin,
    });

    applyGreeting(role);

    const route = ROLE_ROUTES[role];
    if (route && window.navigate) navigate(route, null, true);
  }

  function handleLogout() {
    clearSession();
    if (window.AppDrawer && window.AppDrawer.hide) window.AppDrawer.hide();
    clearGreeting();
    if (window.navigate) navigate('home', null, false);
    else location.hash = 'home';
  }

  function syncGreetingFromSession() {
    const session = loadSession();
    if (session?.role) applyGreeting(session.role);
    else clearGreeting();
  }

  function prefillLoginForm(session) {
    if (!session) return;
    const outletCodeEl = document.getElementById('login-outlet-code');
    const roleEl = document.getElementById('login-role');
    const usernameEl = document.getElementById('login-username');
    if (outletCodeEl && session.outletCode) outletCodeEl.value = session.outletCode;
    if (roleEl && session.role) roleEl.value = session.role;
    if (usernameEl && session.username) usernameEl.value = session.username;
  }

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-action="logout"]');
    if (!btn) return;
    handleLogout();
  });

  window.Auth = window.Auth || {};
  window.Auth.logout = handleLogout;

  // Init
  document.addEventListener('DOMContentLoaded', () => {
    const session = loadSession();
    if (session) prefillLoginForm(session);

    const loginForm = document.getElementById('login-form');
    if (loginForm) loginForm.addEventListener('submit', loginSubmit);

    // If user requested auto-login, go straight to their dashboard.
    if (session?.autoLogin && ROLE_ROUTES[session.role]) {
      applyGreeting(session.role);
      if (window.navigate) navigate(ROLE_ROUTES[session.role], null, false);
      else location.hash = ROLE_ROUTES[session.role];
      return;
    }

    // Keep greeting synced on navigation.
    syncGreetingFromSession();

    // Floating scroll-to-top (matches screenshot UI).
    const scrollBtn = document.getElementById('scroll-to-top');
    const scroller = document.querySelector('.screen-content');
    if (scrollBtn && scroller) {
      scrollBtn.addEventListener('click', () => {
        scroller.scrollTop = 0;
      });
    }
  });

  window.addEventListener('hashchange', () => {
    // Router may show screens without re-initializing greeting, so re-sync here.
    syncGreetingFromSession();
  });
})();

