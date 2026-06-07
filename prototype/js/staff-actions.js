(function () {
  function getTaskIdFromHash() {
    const q = (location.hash.split('?')[1] || '');
    const m = q.match(/(?:^|&)id=([^&]+)/);
    return m ? decodeURIComponent(m[1]) : 'room-204';
  }

  function syncRequestButtons(taskId) {
    const page = document.getElementById('page-staff-task-request');
    if (!page) return;
    page.querySelectorAll('[data-param-id]').forEach((el) => {
      el.dataset.paramId = taskId;
    });
    const accept = document.getElementById('staff-accept-btn');
    if (accept) {
      accept.dataset.paramId = taskId;
      accept.dataset.nav = 'staff/task-active';
      accept.dataset.paramStarted = '1';
    }
    const data = typeof PAGE_DATA !== 'undefined' ? PAGE_DATA[taskId] : null;
    if (data) {
      const slaEls = page.querySelectorAll('[data-dynamic-field="sla"]');
      const sla = (data.durationMinutes || 15) + ' min';
      slaEls.forEach((el) => (el.textContent = sla));
    }
  }

  window.StaffActions = {
    syncRequestButtons,

    acceptRequest(taskId) {
      if (window.navigate) {
        navigate('staff/task-active', { id: taskId, started: '1' }, true);
      } else {
        location.hash = 'staff/task-active?id=' + encodeURIComponent(taskId) + '&started=1';
      }
    },

    toggleServicePause() {
      const btn = document.getElementById('btn-pause-service');
      const banner = document.getElementById('service-status-banner');
      if (!window.TaskTimer) return;
      TaskTimer.toggle();
      const paused = TaskTimer.isPaused();
      if (btn) btn.textContent = paused ? '▶ Resume Service' : '⏸ Pause Service';
      if (banner) {
        banner.className = 'status-banner ' + (paused ? 'paused' : 'running');
        const status = banner.querySelector('[data-timer-status]');
        if (status) status.textContent = paused ? 'Service Paused' : 'In Progress';
      }
    },

    completeService() {
      if (window.TaskTimer) TaskTimer.stop();
      alert('Service completed successfully!');
      navigate('staff/history', null, true);
    },

    confirmPass(name) {
      alert('Request passed to ' + name);
      navigate('staff/home', null, true);
    },

    confirmReject() {
      const reason = document.getElementById('reject-reason')?.value || 'Other';
      alert('Request rejected: ' + reason);
      navigate('staff/home', null, true);
    },
  };

  window.addEventListener('hashchange', () => {
    const path = (location.hash.slice(1) || '').split('?')[0];
    if (path === 'staff/task-request') {
      syncRequestButtons(getTaskIdFromHash());
    }
    if (path === 'staff/pass-request' || path === 'staff/reject-request' || path === 'staff/request-paused') {
      const id = getTaskIdFromHash();
      document.querySelectorAll('#page-staff-pass-request [data-param-id], #page-staff-request-paused [data-param-id], #page-staff-reject-request [data-param-id]').forEach((el) => {
        el.dataset.paramId = id;
      });
    }
    if (path === 'staff/task-active') {
      const btn = document.querySelector('#page-staff-task-active [data-nav="staff/pass-request"]');
      if (btn) btn.dataset.paramId = getTaskIdFromHash();
    }
  });
})();
