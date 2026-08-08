(function () {
  const history = [];
  const AUTH_STORAGE_KEY = 'oneMenusSession';
  let currentRole = loadSavedRole();

  function loadSavedRole() {
    try {
      const raw = localStorage.getItem(AUTH_STORAGE_KEY);
      if (!raw) return null;
      const session = JSON.parse(raw);
      return session && typeof session.role === 'string' ? session.role : null;
    } catch (e) {
      return null;
    }
  }

  function roleToHomeRoute(role) {
    // Internal routing role mapping:
    // - staff -> staff/home
    // - hod   -> hod/dashboard (Manager screens)
    // - hr    -> hr/dashboard
    if (role === 'staff') return 'staff/home';
    if (role === 'hod') return 'hod/dashboard';
    if (role === 'hr') return 'hr/dashboard';
    return null;
  }

  const STAFF_ATTENDANCE_DATA = {
    'August 2026': {
      summary: { daysPresent: '24/26', workedHours: '192h', lateArrivals: '2' },
      daily: [
        { day: '01', status: 'present', detail: '09:00 – 18:00' },
        { day: '02', status: 'present', detail: '09:05 – 18:00' },
        { day: '03', status: 'present', detail: '09:00 – 18:10' },
        { day: '04', status: 'absent', detail: 'Absent' },
        { day: '05', status: 'present', detail: '09:00 – 18:00' },
        { day: '06', status: 'present', detail: '09:00 – 18:00' },
        { day: '07', status: 'present', detail: '09:00 – 18:00' },
      ],
    },
    'July 2026': {
      summary: { daysPresent: '23/31', workedHours: '184h', lateArrivals: '3' },
      daily: [
        { day: '01', status: 'present', detail: '09:00 – 18:00' },
        { day: '05', status: 'present', detail: '09:10 – 18:00' },
        { day: '08', status: 'present', detail: '09:00 – 18:00' },
        { day: '10', status: 'absent', detail: 'Absent' },
        { day: '13', status: 'present', detail: '09:00 – 18:05' },
        { day: '20', status: 'half', detail: '09:00 – 14:00' },
        { day: '28', status: 'present', detail: '09:00 – 18:00' },
      ],
    },
    'June 2026': {
      summary: { daysPresent: '25/30', workedHours: '200h', lateArrivals: '1' },
      daily: [
        { day: '02', status: 'present', detail: '09:00 – 18:00' },
        { day: '05', status: 'present', detail: '09:00 – 18:00' },
        { day: '11', status: 'present', detail: '09:00 – 18:00' },
        { day: '14', status: 'half', detail: '09:00 – 14:00' },
        { day: '18', status: 'present', detail: '09:00 – 18:00' },
        { day: '23', status: 'present', detail: '09:00 – 18:00' },
        { day: '30', status: 'present', detail: '09:00 – 18:00' },
      ],
    },
    'May 2026': {
      summary: { daysPresent: '26/31', workedHours: '208h', lateArrivals: '4' },
      daily: [
        { day: '03', status: 'present', detail: '09:00 – 18:00' },
        { day: '07', status: 'present', detail: '09:10 – 18:00' },
        { day: '12', status: 'half', detail: '09:00 – 14:30' },
        { day: '15', status: 'present', detail: '09:00 – 18:00' },
        { day: '19', status: 'present', detail: '09:00 – 18:00' },
        { day: '24', status: 'absent', detail: 'Absent' },
        { day: '29', status: 'present', detail: '09:00 – 18:00' },
      ],
    },
  };

  const ATTENDANCE_REPORTS = {
    hod: {
      'August 2026': { month: 'August 2026', present: '98%', late: '4' },
      'July 2026': { month: 'July 2026', present: '96%', late: '5' },
      'June 2026': { month: 'June 2026', present: '97%', late: '3' },
    },
    hr: {
      'August 2026': { month: 'August 2026', present: '98%', late: '4' },
      'July 2026': { month: 'July 2026', present: '96%', late: '5' },
      'June 2026': { month: 'June 2026', present: '97%', late: '3' },
    },
  };

  const EMPLOYEE_ATTENDANCE_DATA = {
    'emp-ravi': {
      name: 'Ravi Kumar',
      dept: 'Housekeeping',
      attendance: {
        'August 2026': {
          present: '27/30',
          late: '2',
          absent: '1',
          daily: [
            { day: '01', status: 'present', detail: '09:00 – 18:00' },
            { day: '02', status: 'present', detail: '09:05 – 18:00' },
            { day: '03', status: 'present', detail: '09:00 – 18:10' },
            { day: '04', status: 'absent', detail: 'Absent' },
            { day: '05', status: 'present', detail: '09:00 – 18:00' },
            { day: '28', status: 'late', detail: '09:15 – 18:00' },
            { day: '30', status: 'present', detail: '09:00 – 18:00' },
          ],
        },
        'July 2026': {
          present: '25/31',
          late: '3',
          absent: '2',
          daily: [
            { day: '02', status: 'present', detail: '09:00 – 18:00' },
            { day: '08', status: 'late', detail: '09:20 – 18:00' },
            { day: '10', status: 'absent', detail: 'Absent' },
            { day: '17', status: 'present', detail: '09:00 – 18:00' },
            { day: '25', status: 'present', detail: '09:00 – 18:00' },
          ],
        },
        'June 2026': {
          present: '26/30',
          late: '1',
          absent: '1',
          daily: [
            { day: '05', status: 'present', detail: '09:00 – 18:00' },
            { day: '11', status: 'present', detail: '09:00 – 18:00' },
            { day: '14', status: 'half', detail: '09:00 – 14:00' },
            { day: '18', status: 'late', detail: '09:10 – 18:00' },
            { day: '23', status: 'present', detail: '09:00 – 18:00' },
          ],
        },
      },
    },
    'emp-anita': {
      name: 'Anita Singh',
      dept: 'Housekeeping',
      attendance: {
        'August 2026': {
          present: '28/30',
          late: '1',
          absent: '0',
          daily: [
            { day: '01', status: 'present', detail: '09:00 – 18:00' },
            { day: '05', status: 'present', detail: '09:05 – 18:00' },
            { day: '12', status: 'present', detail: '09:00 – 18:00' },
            { day: '20', status: 'late', detail: '09:20 – 18:00' },
            { day: '27', status: 'present', detail: '09:00 – 18:00' },
          ],
        },
        'July 2026': {
          present: '27/31',
          late: '2',
          absent: '1',
          daily: [
            { day: '03', status: 'present', detail: '09:00 – 18:00' },
            { day: '09', status: 'present', detail: '09:10 – 18:00' },
            { day: '13', status: 'absent', detail: 'Absent' },
            { day: '21', status: 'present', detail: '09:00 – 18:00' },
            { day: '29', status: 'present', detail: '09:00 – 18:00' },
          ],
        },
        'June 2026': {
          present: '28/30',
          late: '0',
          absent: '0',
          daily: [
            { day: '02', status: 'present', detail: '09:00 – 18:00' },
            { day: '08', status: 'present', detail: '09:00 – 18:00' },
            { day: '15', status: 'present', detail: '09:00 – 18:00' },
            { day: '22', status: 'present', detail: '09:00 – 18:00' },
            { day: '29', status: 'present', detail: '09:00 – 18:00' },
          ],
        },
      },
    },
    'emp-mohit': {
      name: 'Mohit Sharma',
      dept: 'Maintenance',
      attendance: {
        'August 2026': {
          present: '26/30',
          late: '1',
          absent: '2',
          daily: [
            { day: '04', status: 'absent', detail: 'Absent' },
            { day: '11', status: 'present', detail: '09:00 – 18:00' },
            { day: '16', status: 'present', detail: '09:00 – 18:00' },
            { day: '22', status: 'late', detail: '09:10 – 18:00' },
            { day: '30', status: 'present', detail: '09:00 – 18:00' },
          ],
        },
      },
    },
    'emp-suresh': {
      name: 'Suresh Yadav',
      dept: 'Maintenance',
      attendance: {
        'August 2026': {
          present: '29/30',
          late: '1',
          absent: '0',
          daily: [
            { day: '01', status: 'present', detail: '09:00 – 18:00' },
            { day: '10', status: 'present', detail: '09:00 – 18:00' },
            { day: '18', status: 'present', detail: '09:00 – 18:00' },
            { day: '24', status: 'present', detail: '09:00 – 18:00' },
            { day: '30', status: 'present', detail: '09:00 – 18:00' },
          ],
        },
      },
    },
  };

  const FOOTER_CONFIGS = {
    staff: [
      { nav: 'staff/home', label: 'Home', icon: '🏠' },
      { nav: 'staff/tasks-tab', label: 'Tasks', icon: '📋' },
      { nav: 'staff/profile-tab', label: 'Profile', icon: '👤' },
      { nav: 'staff/rewards', label: 'Leaderboard', icon: '🏆' },
      { nav: 'staff/more', label: 'More', icon: '⋯', action: 'drawer' },
    ],
    hod: [
      { nav: 'hod/dashboard', label: 'Home', icon: '🏠' },
      { nav: 'hod/tasks-tab', label: 'Tasks', icon: '📋' },
      { nav: 'hod/performance', label: 'Performance', icon: '📈' },
      { nav: 'hod/staff', label: 'Staff', icon: '👥' },
      { nav: 'hod/profile', label: 'Profile', icon: '👤' },
    ],
    hr: [
      { nav: 'hr/dashboard', label: 'Home', icon: '🏠' },
      { nav: 'hr/employees', label: 'Employees', icon: '👥' },
      { nav: 'hr/performance-tab', label: 'Performance', icon: '📈' },
      { nav: 'hr/training', label: 'Training', icon: '🎓' },
      { nav: 'hr/profile', label: 'Profile', icon: '👤' },
    ],
  };

  function applyFooter(role, pageId) {
    const config = FOOTER_CONFIGS[role];
    if (!config) return;
    config.forEach((item, idx) => {
      const btn = document.getElementById('footer-item-' + (idx + 1));
      if (!btn) return;
      if (item.action === 'drawer') {
        btn.dataset.action = 'open-app-drawer';
        delete btn.dataset.nav;
      } else {
        btn.dataset.nav = item.nav;
        delete btn.dataset.action;
      }
      const iconEl = btn.querySelector('.footer-ic');
      const labelEl = btn.querySelector('.footer-label');
      if (iconEl) iconEl.textContent = item.icon;
      if (labelEl) labelEl.textContent = item.label;
      const targetPageId = item.nav ? item.nav.replace(/\//g, '-') : '';
      btn.classList.toggle('active', targetPageId === pageId);
    });
    const footerNav = document.querySelector('.footer-nav');
    if (footerNav) footerNav.classList.toggle('footer-nav--staff', role === 'staff');
  }

  function parseHash() {
    const raw = location.hash.slice(1) || 'loading';
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

    if (pageId === 'loading') {
      setTimeout(() => {
        if ((location.hash.slice(1) || 'loading') === 'loading') navigate('home', null, false);
      }, 1400);
    }

    // Refresh role from saved session.
    // This ensures Logout reliably hides the footer even when URL pages are still accessible.
    currentRole = loadSavedRole();
    const activeRole = currentRole;

    const footerStack = document.getElementById('app-footer-drawer');
    const isAuthedRole = ['staff', 'hod', 'hr'].includes(activeRole);
    const shouldShowFooter = isAuthedRole && pageId !== 'home';
    if (footerStack) {
      footerStack.style.display = shouldShowFooter ? 'block' : 'none';

      if (shouldShowFooter) {
        applyFooter(activeRole, pageId);
        if (window.AppDrawer) window.AppDrawer.setRole(activeRole);
      }
    }

    document.querySelectorAll('.screen-label').forEach((l) => (l.style.display = 'none'));
    const label = activeRole ? document.getElementById('label-' + activeRole) : null;
    if (label && page.dataset.showLabel !== 'false') label.style.display = 'block';

    applyPageParams(page, params);
    initStaffPages(pageId, params);
    if (pageId.endsWith('-settings') && window.ThemeSettings) {
      window.ThemeSettings.initSettingsForm(pageId);
    }
    if (['staff-profile', 'hod-profile', 'hr-profile', 'staff-profile-tab'].includes(pageId) && window.ThemeSettings) {
      window.ThemeSettings.prefillProfileFields(pageId.split('-')[0]);
    }
    window.scrollTo(0, 0);
    const scroller = document.querySelector('.screen-content');
    if (scroller) {
      scroller.scrollTop = 0;
      // When footer is hidden, use the smaller padding variant.
      scroller.classList.toggle('nav-hidden-only', !shouldShowFooter);
    }
  }

  function initStaffPages(pageId, params) {
    if (window.StaffActions) StaffActions.refreshTaskCards();

    if (pageId === 'staff-task-active' && params.id) {
      const data = PAGE_DATA[params.id];
      const mins = data?.durationMinutes || 15;
      const slaEl = document.querySelector('#page-staff-task-active [data-dynamic-field="sla"]');
      if (slaEl) slaEl.textContent = mins + ' min SLA';
      if (window.TaskTimer && (params.started === '1' || params.started === 'true')) {
        const startedAt = window.TaskState ? TaskState.getStartedAt(params.id) : null;
        TaskTimer.start(params.id, mins, startedAt);
      }
      if (window.StaffActions) StaffActions.syncRequestPage(params.id);
    }
    if (pageId === 'staff-task-request' && params.id) {
      if (window.StaffActions) StaffActions.syncRequestPage(params.id);
    }
    if (pageId === 'staff-task-detail' && params.id) {
      if (window.StaffActions) StaffActions.syncTaskDetailPage(params.id);
    }
    if (pageId === 'staff-task-active' && params.id) {
      document.querySelectorAll('#page-staff-task-active [data-nav="staff/pass-request"]').forEach((el) => {
        el.dataset.paramId = params.id;
      });
    }
    if (pageId === 'staff-attendance') {
      renderStaffAttendance();
    }
    if (pageId === 'hod-attendance') {
      renderRoleAttendanceSummary('hod');
    }
    if (pageId === 'hr-attendance') {
      renderRoleAttendanceSummary('hr');
    }
    if (pageId === 'attendance-detail') {
      renderAttendanceDetail(params);
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

  function renderStaffAttendance() {
    const monthSelect = document.getElementById('staff-attendance-month');
    const summaryEls = {
      daysPresent: document.querySelector('[data-attendance-summary="daysPresent"]'),
      workedHours: document.querySelector('[data-attendance-summary="workedHours"]'),
      lateArrivals: document.querySelector('[data-attendance-summary="lateArrivals"]'),
    };
    const calendarContainer = document.getElementById('staff-attendance-calendar');
    const dayList = document.getElementById('staff-attendance-day-list');

    function updateAttendance(month) {
      const data = STAFF_ATTENDANCE_DATA[month] || STAFF_ATTENDANCE_DATA['August 2026'];
      if (!data) return;
      if (summaryEls.daysPresent) summaryEls.daysPresent.textContent = data.summary.daysPresent;
      if (summaryEls.workedHours) summaryEls.workedHours.textContent = data.summary.workedHours;
      if (summaryEls.lateArrivals) summaryEls.lateArrivals.textContent = data.summary.lateArrivals;

      if (calendarContainer) {
        calendarContainer.innerHTML = data.daily
          .map((item) => {
            const statusClass = item.status === 'half' ? 'half' : item.status === 'absent' ? 'absent' : 'present';
            return `
              <div class="attendance-calendar-cell ${statusClass}">
                <div class="cell-top"><span>${item.day}</span><span>${item.status === 'present' ? item.detail.split(' ')[0] : item.status === 'half' ? '½' : '✕'}</span></div>
                <div class="cell-detail">${item.status === 'present' ? item.detail : item.status === 'half' ? item.detail : 'Absent'}</div>
              </div>
            `;
          })
          .join('');
      }

      if (dayList) {
        dayList.innerHTML = data.daily
          .map((item) => {
            const statusLabel = item.status === 'present' ? 'Present' : item.status === 'half' ? 'Half Day' : 'Absent';
            return `
              <button type="button" class="card list-item">
                <div>
                  <h4>${item.day} ${month.split(' ')[0]}</h4>
                  <p>${statusLabel} · ${item.detail}</p>
                </div>
              </button>
            `;
          })
          .join('');
      }
    }

    if (monthSelect) {
      monthSelect.removeEventListener('change', handleMonthChange);
      monthSelect.addEventListener('change', handleMonthChange);
    }

    function handleMonthChange(event) {
      const selectedMonth = event.target.value;
      updateAttendance(selectedMonth);
    }

    const initialMonth = (monthSelect && monthSelect.value) || 'August 2026';
    updateAttendance(initialMonth);
  }

  function renderRoleAttendanceSummary(role) {
    const monthSelect = document.getElementById(`${role}-attendance-month`);
    const monthLabel = document.querySelector(`[data-attendance-summary="${role}-month"]`);
    const presentLabel = document.querySelector(`[data-attendance-summary="${role}-present"]`);
    const lateLabel = document.querySelector(`[data-attendance-summary="${role}-late"]`);
    const attendanceButtons = document.querySelectorAll(`#page-${role}-attendance .card.list-item[data-nav="attendance-detail"]`);

    function updateRoleSummary(month) {
      const report = ATTENDANCE_REPORTS[role]?.[month] || ATTENDANCE_REPORTS[role]?.['August 2026'];
      if (!report) return;
      if (monthLabel) monthLabel.textContent = report.month;
      if (presentLabel) presentLabel.textContent = report.present;
      if (lateLabel) lateLabel.textContent = report.late;
      attendanceButtons.forEach((button) => {
        if (button.dataset.paramId) {
          button.dataset.paramMonth = month;
        }
      });
    }

    if (!monthSelect) return;
    monthSelect.removeEventListener('change', handleMonthChange);
    monthSelect.addEventListener('change', handleMonthChange);
    function handleMonthChange(event) {
      updateRoleSummary(event.target.value);
    }
    updateRoleSummary(monthSelect.value || 'August 2026');
  }

  function renderAttendanceDetail(params) {
    const empId = params.id;
    const month = params.month || 'August 2026';
    const employee = EMPLOYEE_ATTENDANCE_DATA[empId];
    const detail = employee?.attendance?.[month] || employee?.attendance?.['August 2026'];
    if (!employee || !detail) return;

    const nameEl = document.querySelector('[data-attendance-detail="name"]');
    const deptEl = document.querySelector('[data-attendance-detail="dept"]');
    const monthEl = document.querySelector('[data-attendance-detail="month"]');
    const presentEl = document.querySelector('[data-attendance-detail="present"]');
    const lateEl = document.querySelector('[data-attendance-detail="late"]');
    const absentEl = document.querySelector('[data-attendance-detail="absent"]');
    const dayList = document.getElementById('attendance-detail-day-list');

    if (nameEl) nameEl.textContent = employee.name;
    if (deptEl) deptEl.textContent = employee.dept;
    if (monthEl) monthEl.textContent = month;
    if (presentEl) presentEl.textContent = detail.present;
    if (lateEl) lateEl.textContent = detail.late;
    if (absentEl) absentEl.textContent = detail.absent;

    if (dayList) {
      dayList.innerHTML = detail.daily
        .map((item) => {
          const statusLabel = item.status === 'present' ? 'Present' : item.status === 'half' ? 'Half Day' : item.status === 'absent' ? 'Absent' : 'Late';
          return `
            <button type="button" class="card list-item">
              <div>
                <h4>${item.day} ${month.split(' ')[0]}</h4>
                <p>${statusLabel} · ${item.detail}</p>
              </div>
            </button>
          `;
        })
        .join('');
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
    const drawerBtn = e.target.closest('[data-action="open-app-drawer"]');
    if (drawerBtn) {
      e.preventDefault();
      const role = loadSavedRole();
      if (role && window.AppDrawer && window.AppDrawer.show) window.AppDrawer.show(role);
      return;
    }

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
  'room-204': { name: 'Room 204 — Towels Request', room: '204', taskTitle: 'Towels Request', assignee: 'Ravi', status: 'Awaiting acceptance', priority: 'High', time: '2 min ago', dept: 'Housekeeping', durationMinutes: 15, guest: 'Mr. Sharma' },
  'room-305-hk': { name: 'Room 305 — Room Cleaning', room: '305', taskTitle: 'Room Cleaning', assignee: 'Ravi', status: 'In Progress', priority: 'Medium', time: 'In Progress', dept: 'Housekeeping', durationMinutes: 25, guest: 'Mr. Gupta' },
  'room-512': { name: 'Room 512 — Room Cleaning', room: '512', taskTitle: 'Room Cleaning', assignee: 'Ravi', status: 'Awaiting acceptance', priority: 'High', time: '15 min ago', dept: 'Housekeeping', durationMinutes: 20, guest: 'Ms. Patel' },
  'room-308': { name: 'Room 308 — Room Cleaning', room: '308', taskTitle: 'Room Cleaning', assignee: 'Ravi', status: 'Completed', priority: 'Medium', time: '45 min ago', dept: 'Housekeeping', durationMinutes: 25, guest: 'Mr. Lee' },
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
