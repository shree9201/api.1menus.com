(function () {
  const DRAWER_MENUS = {
    home: {
      title: '1Menus Hotel Ops',
      subtitle: 'Choose role or tool',
      items: [
        { nav: 'owner/dashboard', icon: '👑', label: 'Hotel Owner' },
        { nav: 'hod/dashboard', icon: '🧹', label: 'HOD' },
        { nav: 'staff/home', icon: '👤', label: 'Staff (Ravi)' },
        { nav: 'hr/dashboard', icon: '📋', label: 'HR Manager' },
        { nav: 'flow-map', icon: '🗺️', label: 'Flow Map' },
      ],
    },
    owner: {
      title: 'Hotel Owner',
      subtitle: 'All features',
      items: [
        { nav: 'owner/dashboard', icon: '🏠', label: 'Overview' },
        { nav: 'owner/notifications', icon: '🔔', label: 'Notifications' },
        { nav: 'owner/departments', icon: '🏢', label: 'Departments' },
        { nav: 'owner/insights', icon: '💡', label: 'Insights' },
        { nav: 'owner/insights-tab', icon: '📈', label: 'Insights Feed' },
        { nav: 'owner/reports', icon: '📄', label: 'Reports' },
        { nav: 'owner/more', icon: '⚙️', label: 'Settings' },
        { action: 'logout', icon: '⎋', label: 'Logout' },
      ],
    },
    hod: {
      title: 'Manager',
      subtitle: 'All features',
      items: [
        { nav: 'hod/dashboard', icon: '▦', label: 'Dashboard' },
        { nav: 'hod/notifications', icon: '🔔', label: 'Notifications' },
        { nav: 'hod/tasks-tab', icon: '📋', label: 'Tasks' },
        { nav: 'hod/staff', icon: '👥', label: 'Staff' },
        { nav: 'hod/performance', icon: '🏆', label: 'Performance' },
        { nav: 'hod/analytics', icon: '📊', label: 'Analytics' },
        { nav: 'hod/attention', icon: '⚠️', label: 'Attention' },
        { nav: 'hod/reports', icon: '📄', label: 'Reports' },
        { nav: 'hod/profile', icon: '👤', label: 'Profile' },
        { nav: 'hod/more', icon: '⚙️', label: 'More' },
        { action: 'logout', icon: '⎋', label: 'Logout' },
      ],
    },
    staff: {
      title: 'Ravi Kumar',
      subtitle: 'Staff',
      items: [
        { nav: 'staff/home', icon: '🏠', label: 'Home' },
        { nav: 'staff/tasks-tab', icon: '📥', label: 'Requests' },
        { nav: 'staff/performance', icon: '✅', label: 'Performance' },
        { nav: 'staff/training', icon: '🎓', label: 'Training' },
        { nav: 'staff/profile', icon: '👤', label: 'Profile' },
        { nav: 'staff/change-password', icon: '🔐', label: 'Change Password' },
        { nav: 'staff/support', icon: '🛟', label: 'Help & Support' },
        { action: 'logout', icon: '⎋', label: 'Logout' },
      ],
    },
    hr: {
      title: 'HR Manager',
      subtitle: 'All features',
      items: [
        { nav: 'hr/dashboard', icon: '▦', label: 'Dashboard' },
        { nav: 'hr/notifications', icon: '🔔', label: 'Notifications' },
        { nav: 'hr/employees', icon: '👥', label: 'Employees' },
        { nav: 'hr/performance-tab', icon: '📈', label: 'Performance' },
        { nav: 'hr/training', icon: '🎓', label: 'Training' },
        { nav: 'hr/profile', icon: '👤', label: 'Profile' },
        { nav: 'hr/reports', icon: '📄', label: 'Reports' },
        { nav: 'hr/more', icon: '⚙️', label: 'More' },
        { action: 'logout', icon: '⎋', label: 'Logout' },
      ],
    },
  };

  const overlay = document.getElementById('app-drawer-overlay');
  const sheet = document.getElementById('app-drawer-sheet');
  const grid = document.getElementById('app-drawer-grid');
  const titleEl = document.getElementById('app-drawer-title');
  const subtitleEl = document.getElementById('app-drawer-subtitle');
  const footerBar = document.getElementById('app-footer-drawer');

  function openDrawer(role) {
    const menu = DRAWER_MENUS[role];
    if (!menu || !grid) return;
    titleEl.textContent = menu.title;
    subtitleEl.textContent = menu.subtitle;
    grid.innerHTML = menu.items
      .map((item) => {
        if (item.action === 'logout') {
          return `
            <button type="button" class="drawer-tile" data-action="logout">
              <span class="drawer-tile-icon">${item.icon}</span>
              <span class="drawer-tile-label">${item.label}</span>
            </button>
          `;
        }
        return `
          <button type="button" class="drawer-tile" data-nav="${item.nav}" ${item.paramId ? `data-param-id="${item.paramId}"` : ''}>
            <span class="drawer-tile-icon">${item.icon}</span>
            <span class="drawer-tile-label">${item.label}</span>
          </button>
        `;
      })
      .join('');
    overlay.classList.add('open');
    sheet.classList.add('open');
    document.body.classList.add('drawer-open');
  }

  function closeDrawer() {
    overlay.classList.remove('open');
    sheet.classList.remove('open');
    document.body.classList.remove('drawer-open');
  }

  window.AppDrawer = {
    show: openDrawer,
    hide: closeDrawer,
    setRole: function (role) {
      const drawerRole = role && DRAWER_MENUS[role] ? role : 'home';
      if (footerBar) footerBar.dataset.role = drawerRole;
    },
  };

  document.getElementById('app-drawer-open')?.addEventListener('click', () => {
    const role = footerBar?.dataset.role;
    if (role) openDrawer(role);
  });

  overlay?.addEventListener('click', closeDrawer);
  document.getElementById('app-drawer-close')?.addEventListener('click', closeDrawer);

  grid?.addEventListener('click', (e) => {
    const btn = e.target.closest('.drawer-tile');
    if (!btn) return;
    closeDrawer();
  });
})();
