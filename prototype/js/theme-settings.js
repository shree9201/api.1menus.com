(function () {
  const SETTINGS_KEY = 'oneMenusSettings';

  const DEFAULTS = {
    theme: 'light',
    mobile: '',
    email: '',
    notifPush: true,
    notifSound: true,
    notifVibration: true,
    notifTone: 'default',
    ringtone: 'classic',
    aiTaskSuggestions: true,
    aiShiftInsights: true,
    aiBreakReminders: true,
    aiSkillRecommendations: true,
    language: 'en',
  };

  const ROLE_PROFILE = {
    staff: {
      mobile: '+91 98765 43210',
      email: 'ravi.kumar@hotel.com',
      employeeId: 'HK1287',
      department: 'Housekeeping',
    },
    hod: {
      mobile: '+91 98220 11880',
      email: 'sunil.kumar@hotel.com',
      employeeId: 'MGR1042',
      department: 'Housekeeping',
    },
    hr: {
      mobile: '+91 98100 55443',
      email: 'priya.sharma@hotel.com',
      employeeId: 'HR2001',
      department: 'Human Resources',
    },
  };

  function loadSessionRole() {
    try {
      const raw = localStorage.getItem('oneMenusSession');
      if (!raw) return 'staff';
      const session = JSON.parse(raw);
      return session?.role || 'staff';
    } catch (e) {
      return 'staff';
    }
  }

  function loadSettings() {
    try {
      const raw = localStorage.getItem(SETTINGS_KEY);
      if (!raw) return { ...DEFAULTS };
      return { ...DEFAULTS, ...JSON.parse(raw) };
    } catch (e) {
      return { ...DEFAULTS };
    }
  }

  function saveSettings(partial) {
    const next = { ...loadSettings(), ...partial };
    localStorage.setItem(SETTINGS_KEY, JSON.stringify(next));
    return next;
  }

  function applyTheme(theme) {
    const frame = document.querySelector('.phone-frame');
    if (!frame) return;
    frame.classList.toggle('theme-dark', theme === 'dark');
    frame.classList.toggle('theme-light', theme !== 'dark');
    document.querySelectorAll('[data-theme-toggle] button').forEach((btn) => {
      btn.classList.toggle('active', btn.dataset.theme === theme);
    });
  }

  function prefillProfileFields(role) {
    const profile = ROLE_PROFILE[role] || ROLE_PROFILE.staff;
    const settings = loadSettings();
    const mobile = settings.mobile || profile.mobile;
    const email = settings.email || profile.email;

    document.querySelectorAll('[data-settings-mobile]').forEach((el) => {
      if (el.tagName === 'INPUT') el.value = mobile;
      else el.textContent = mobile;
    });
    document.querySelectorAll('[data-settings-email]').forEach((el) => {
      if (el.tagName === 'INPUT') el.value = email;
      else el.textContent = email;
    });
    document.querySelectorAll('[data-settings-emp-id]').forEach((el) => {
      el.textContent = profile.employeeId;
    });
    document.querySelectorAll('[data-settings-dept]').forEach((el) => {
      el.textContent = profile.department;
    });
  }

  function syncToggle(id, value) {
    const el = document.getElementById(id);
    if (el) el.checked = !!value;
  }

  function syncSelect(id, value) {
    const el = document.getElementById(id);
    if (el && value) el.value = value;
  }

  function initSettingsForm(pageId) {
    const role = pageId.split('-')[0];
    if (!['staff', 'hod', 'hr'].includes(role)) return;

    const s = loadSettings();
    applyTheme(s.theme);
    prefillProfileFields(role);

    syncToggle(role + '-notif-push', s.notifPush);
    syncToggle(role + '-notif-sound', s.notifSound);
    syncToggle(role + '-notif-vibration', s.notifVibration);
    syncToggle(role + '-ai-tasks', s.aiTaskSuggestions);
    syncToggle(role + '-ai-shift', s.aiShiftInsights);
    syncToggle(role + '-ai-break', s.aiBreakReminders);
    syncToggle(role + '-ai-skill', s.aiSkillRecommendations);

    syncSelect(role + '-notif-tone', s.notifTone);
    syncSelect(role + '-ringtone', s.ringtone);
    syncSelect(role + '-language', s.language);
  }

  function bindSettingsPage(page) {
    const role = page.id.replace('page-', '').replace('-settings', '');

    page.querySelector('[data-settings-save]')?.addEventListener('click', () => {
      const mobile = page.querySelector('[data-settings-mobile]')?.value?.trim() || '';
      const email = page.querySelector('[data-settings-email]')?.value?.trim() || '';
      saveSettings({
        mobile,
        email,
        notifPush: !!page.querySelector('#' + role + '-notif-push')?.checked,
        notifSound: !!page.querySelector('#' + role + '-notif-sound')?.checked,
        notifVibration: !!page.querySelector('#' + role + '-notif-vibration')?.checked,
        notifTone: page.querySelector('#' + role + '-notif-tone')?.value || 'default',
        ringtone: page.querySelector('#' + role + '-ringtone')?.value || 'classic',
        aiTaskSuggestions: !!page.querySelector('#' + role + '-ai-tasks')?.checked,
        aiShiftInsights: !!page.querySelector('#' + role + '-ai-shift')?.checked,
        aiBreakReminders: !!page.querySelector('#' + role + '-ai-break')?.checked,
        aiSkillRecommendations: !!page.querySelector('#' + role + '-ai-skill')?.checked,
        language: page.querySelector('#' + role + '-language')?.value || 'en',
      });
      alert('Settings saved successfully.');
    });
  }

  document.addEventListener('click', (e) => {
    const themeBtn = e.target.closest('[data-theme-toggle] button');
    if (themeBtn) {
      e.preventDefault();
      const theme = themeBtn.dataset.theme || 'light';
      saveSettings({ theme });
      applyTheme(theme);
      return;
    }

    const tabBtn = e.target.closest('[data-portfolio-tab]');
    if (tabBtn) {
      e.preventDefault();
      const tab = tabBtn.dataset.portfolioTab;
      const page = tabBtn.closest('.page');
      if (!page) return;
      page.querySelectorAll('[data-portfolio-tab]').forEach((b) => b.classList.toggle('active', b === tabBtn));
      page.querySelectorAll('[data-portfolio-panel]').forEach((p) => {
        p.hidden = p.dataset.portfolioPanel !== tab;
      });
    }
  });

  document.addEventListener('change', (e) => {
    if (e.target.matches('[data-settings-toggle]')) {
      const key = e.target.dataset.settingsToggle;
      if (key) saveSettings({ [key]: e.target.checked });
    }
    if (e.target.matches('[data-settings-select]')) {
      const key = e.target.dataset.settingsSelect;
      if (key) saveSettings({ [key]: e.target.value });
    }
  });

  document.addEventListener('DOMContentLoaded', () => {
    const s = loadSettings();
    applyTheme(s.theme);

    ['page-staff-settings', 'page-hod-settings', 'page-hr-settings'].forEach((id) => {
      const page = document.getElementById(id);
      if (page) bindSettingsPage(page);
    });
  });

  window.addEventListener('hashchange', () => {
    const pageId = (location.hash.slice(1) || '').split('?')[0].replace(/\//g, '-');
    if (pageId.endsWith('-settings')) initSettingsForm(pageId);
    if (pageId === 'staff-profile-tab') prefillProfileFields('staff');
  });

  window.ThemeSettings = {
    loadSettings,
    saveSettings,
    applyTheme,
    initSettingsForm,
    prefillProfileFields,
  };
})();
