(function () {
  const history = [];
  let currentRole = null;

  function parseHash() {
    const raw = location.hash.slice(1) || 'home';
    const [path, query] = raw.split('?');
    const params = {};
    if (query) {
      query.split('&').forEach((p) => {
        const [k, v] = p.split('=');
        params[decodeURIComponent(k)] = decodeURIComponent(v || '');
      });
    }
    return { path, params };
  }

  function showPage(pageId, params) {
    document.querySelectorAll('.page').forEach((el) => el.classList.remove('active'));
    const page = document.getElementById('page-' + pageId);
    if (!page) {
      console.warn('Page not found:', pageId);
      return;
    }
    page.classList.add('active');

    const role = pageId.split('/')[0];
    if (['owner', 'hod', 'staff', 'hr'].includes(role)) currentRole = role;

    document.querySelectorAll('.bottom-nav').forEach((nav) => {
      nav.style.display = 'none';
    });
    const nav = document.getElementById('nav-' + role);
    const footerStack = document.getElementById('app-footer-drawer');
    if (footerStack) footerStack.style.display = 'flex';
    if (nav && !page.dataset.hideNav) {
      nav.style.display = 'flex';
      const tab = page.dataset.tab;
      nav.querySelectorAll('.nav-item').forEach((item) => {
        item.classList.toggle('active', item.dataset.tab === tab);
      });
    }

    document.querySelectorAll('.screen-label').forEach((l) => (l.style.display = 'none'));
    const label = document.getElementById('label-' + role);
    if (label && page.dataset.showLabel !== 'false') label.style.display = 'block';

    applyPageParams(page, params);
    initStaffPages(pageId, params);
    if (window.AppDrawer) {
      AppDrawer.setRole(['owner', 'hod', 'staff', 'hr'].includes(role) ? role : pageId === 'home' || pageId === 'flow-map' ? 'home' : null);
    }
    window.scrollTo(0, 0);
    const scroller = document.querySelector('.screen-content');
    if (scroller) {
      scroller.scrollTop = 0;
      scroller.classList.toggle('nav-hidden-only', !!page.dataset.hideNav);
    }
  }

  function initStaffPages(pageId, params) {
    if (pageId === 'staff-task-active' && params.id) {
      const data = PAGE_DATA[params.id];
      const mins = data?.durationMinutes || 15;
      const slaEl = document.querySelector('#page-staff-task-active [data-dynamic-field="sla"]');
      if (slaEl) slaEl.textContent = mins + ' min SLA';
      if (window.TaskTimer) {
        if (params.started === '1' || params.started === 'true') TaskTimer.start(params.id, mins);
        else if (TaskTimer.getTaskId() !== params.id) TaskTimer.start(params.id, mins);
      }
    }
    if (pageId === 'staff-task-request' && params.id) {
      if (window.StaffActions) StaffActions.syncRequestButtons(params.id);
      const data = PAGE_DATA[params.id];
      if (data) {
        const mins = data.durationMinutes || 15;
        document.querySelectorAll('#page-staff-task-request [data-dynamic-field="sla"]').forEach((el) => {
          el.textContent = mins + ' min';
        });
      }
    }
    if (pageId === 'staff-task-active' && params.id) {
      document.querySelectorAll('#page-staff-task-active [data-nav="staff/pass-request"]').forEach((el) => {
        el.dataset.paramId = params.id;
      });
    }
  }

  function applyPageParams(page, params) {
    if (params.filter) {
      page.querySelectorAll('[data-filter]').forEach((tab) => {
        tab.classList.toggle('active', tab.dataset.filter === params.filter);
      });
      const title = page.querySelector('[data-filter-title]');
      if (title) title.textContent = params.filter.charAt(0).toUpperCase() + params.filter.slice(1) + ' Tasks';
    }
    if (params.id) {
      const nameEl = page.querySelector('[data-dynamic-name]');
      const roomEl = page.querySelector('[data-dynamic-room]');
      const data = PAGE_DATA[params.id];
      if (data && nameEl) nameEl.textContent = data.name || params.id;
      if (data && roomEl) roomEl.textContent = data.room || '';
      if (data) {
        page.querySelectorAll('[data-dynamic-field]').forEach((el) => {
          const key = el.dataset.dynamicField;
          if (key === 'sla' && data.durationMinutes) el.textContent = data.durationMinutes + ' min';
          else if (data[key]) el.textContent = data[key];
        });
      }
    }
    if (params.metric) {
      const metricEl = page.querySelector('[data-metric-title]');
      const metrics = {
        requests: { title: 'Total Requests', value: '356', trend: '+12% vs yesterday' },
        response: { title: 'Avg. Response Time', value: '14m 25s', trend: '↓ 8% improvement' },
        rating: { title: 'Guest Rating', value: '4.5 ★', trend: '+0.3 this week' },
        delayed: { title: 'Delayed Requests', value: '28', trend: '↑ 5% vs last week' },
      };
      const m = metrics[params.metric];
      if (m && metricEl) {
        page.querySelector('[data-metric-value]').textContent = m.value;
        page.querySelector('[data-metric-trend]').textContent = m.trend;
        metricEl.textContent = m.title;
      }
    }
  }

  function navigate(path, params, push = true) {
    const qs = params
      ? '?' +
        Object.entries(params)
          .map(([k, v]) => encodeURIComponent(k) + '=' + encodeURIComponent(v))
          .join('&')
      : '';
    if (push) history.push(location.hash);
    location.hash = path + qs;
  }

  window.navigate = navigate;

  window.goBack = function () {
    if (history.length) {
      location.hash = history.pop().slice(1);
    } else {
      const role = currentRole || 'home';
      const fallbacks = { owner: 'owner/dashboard', hod: 'hod/dashboard', staff: 'staff/home', hr: 'hr/dashboard' };
      navigate(role === 'home' || !fallbacks[role] ? 'home' : fallbacks[role], null, false);
    }
  };

  function onHashChange() {
    const { path, params } = parseHash();
    const pageId = path.replace(/\//g, '-');
    showPage(pageId, params);
  }

  document.addEventListener('click', (e) => {
    const link = e.target.closest('[data-nav]');
    if (!link) return;
    e.preventDefault();
    const path = link.dataset.nav;
    const params = {};
    Object.keys(link.dataset).forEach((k) => {
      if (k === 'nav' || k === 'tab' || k === 'filter') return;
      if (k.startsWith('param')) {
        const key = k.slice(5, 6).toLowerCase() + k.slice(6);
        params[key] = link.dataset[k];
      }
    });
    navigate(path, Object.keys(params).length ? params : null);
  });

  window.addEventListener('hashchange', onHashChange);
  onHashChange();
})();

const PAGE_DATA = {
  'room-305': { name: 'Room 305 — AC Issue', room: '305', assignee: 'Ravi', status: 'Delayed', time: '12 min', dept: 'Housekeeping' },
  'room-412': { name: 'Room 412 — Extra Towels', room: '412', assignee: 'Anita', status: 'In Progress', time: '8 min', dept: 'Housekeeping' },
  'room-118': { name: 'Room 118 — Deep Cleaning', room: '118', assignee: 'Suresh', status: 'In Progress', time: '25 min', dept: 'Housekeeping' },
  'room-220': { name: 'Room 220 — Mini Bar', room: '220', assignee: 'Unassigned', status: 'Pending', time: '5 min', dept: 'Housekeeping' },
  'room-204': { name: 'Room 204 — Need Towels', room: '204', taskTitle: 'Need Towels', assignee: 'Ravi', status: 'Awaiting acceptance', priority: 'Medium', time: '2 min ago', dept: 'Housekeeping', durationMinutes: 15, guest: 'Mr. Sharma' },
  'room-512': { name: 'Room 512 — Room Cleaning', room: '512', taskTitle: 'Room Cleaning', assignee: 'Ravi', status: 'Awaiting acceptance', priority: 'High', time: '15 min ago', dept: 'Housekeeping', durationMinutes: 20, guest: 'Ms. Patel' },
  'room-308': { name: 'Room 308 — Mini Bar Restock', room: '308', taskTitle: 'Mini Bar Restock', assignee: 'Ravi', status: 'Awaiting acceptance', priority: 'Low', time: '30 min ago', dept: 'Housekeeping', durationMinutes: 25, guest: 'Mr. Lee' },
  housekeeping: { name: 'Housekeeping', rating: '4.6', tasks: '142', delayed: '3' },
  'front-office': { name: 'Front Office', rating: '4.4', tasks: '89', delayed: '5' },
  maintenance: { name: 'Maintenance', rating: '3.8', tasks: '67', delayed: '12' },
  'fb-service': { name: 'F&B Service', rating: '4.3', tasks: '58', delayed: '4' },
  security: { name: 'Security', rating: '4.6', tasks: '34', delayed: '1' },
  'insight-ac': { name: 'AC Complaints Trend', room: '', dept: 'Maintenance' },
  'insight-hk': { name: 'Housekeeping Performance', room: '', dept: 'Housekeeping' },
  'insight-peak': { name: 'Peak Delay Analysis', room: '', dept: 'All Departments' },
  'emp-ravi': { name: 'Ravi Kumar', room: 'HK1287', dept: 'Housekeeping', rating: '4.8', tasks: '32' },
  'emp-anita': { name: 'Anita Singh', room: 'HK1291', dept: 'Housekeeping', rating: '4.6', tasks: '28' },
  'emp-mohit': { name: 'Mohit Sharma', room: 'MT1042', dept: 'Maintenance', rating: '4.2', tasks: '24' },
  'emp-suresh': { name: 'Suresh Yadav', room: 'MT1055', dept: 'Maintenance', rating: '4.0', tasks: '19' },
};
