(function () {
  function getTaskIdFromHash() {
    const q = location.hash.split('?')[1] || '';
    const m = q.match(/(?:^|&)id=([^&]+)/);
    return m ? decodeURIComponent(m[1]) : 'room-204';
  }

  function getTaskData(taskId) {
    return typeof PAGE_DATA !== 'undefined' ? PAGE_DATA[taskId] : null;
  }

  function syncRequestPage(taskId) {
    const page = document.getElementById('page-staff-task-request');
    if (!page || !window.TaskState) return;

    TaskState.ensureTask(taskId);
    const status = TaskState.getStatus(taskId);
    const data = getTaskData(taskId);

    page.querySelectorAll('[data-param-id]').forEach((el) => {
      el.dataset.paramId = taskId;
    });

    const badge = page.querySelector('[data-request-status-badge]');
    if (badge) {
      const labels = {
        open: 'New Request',
        accepted: 'Accepted',
        started: 'In Progress',
        paused: 'Paused',
        ended: 'Ended',
        done: 'Completed',
      };
      badge.textContent = labels[status] || status;
      badge.className = 'badge ' + (status === 'open' ? 'pending' : status === 'accepted' ? 'medium' : 'high');
    }

    const timeline = page.querySelector('[data-request-timeline]');
    if (timeline) timeline.innerHTML = TaskState.renderTimelineHtml(taskId);

    const actionsOpen = page.querySelector('[data-request-actions-open]');
    const actionsAccepted = page.querySelector('[data-request-actions-accepted]');
    const actionsStarted = page.querySelector('[data-request-actions-started]');

    if (actionsOpen) actionsOpen.hidden = status !== 'open';
    if (actionsAccepted) actionsAccepted.hidden = status !== 'accepted';
    if (actionsStarted) actionsStarted.hidden = !['started', 'paused'].includes(status);

    if (data) {
      const sla = (data.durationMinutes || 15) + ' min';
      page.querySelectorAll('[data-dynamic-field="sla"]').forEach((el) => {
        el.textContent = sla;
      });
    }
  }

  function syncRequestButtons(taskId) {
    syncRequestPage(taskId);
  }

  function renderTaskCardActions(taskId) {
    if (!window.TaskState) return '';
    const status = TaskState.getStatus(taskId);
    const data = getTaskData(taskId);
    const priority = (data?.priority || 'Medium').toLowerCase();
    const pillClass = priority.includes('high') ? 'high' : priority.includes('low') ? 'medium' : 'medium';

    if (status === 'open') {
      return `<span class="priority-pill ${pillClass}">${priority.toUpperCase().split(' ')[0]}</span>
        <button type="button" class="btn-task-accept" onclick="StaffActions.acceptRequest('${taskId}')">ACCEPT</button>`;
    }
    if (status === 'accepted') {
      return `<span class="priority-pill ${pillClass}">${priority.toUpperCase().split(' ')[0]}</span>
        <button type="button" class="btn-task-start" onclick="StaffActions.startRequest('${taskId}')">START</button>`;
    }
    if (status === 'started' || status === 'paused') {
      return `<span class="priority-pill ${pillClass}">${priority.toUpperCase().split(' ')[0]}</span>
        <button type="button" class="btn-task-start" onclick="StaffActions.openActiveTask('${taskId}')">VIEW</button>`;
    }
    return `<span class="badge high">Done</span>`;
  }

  function refreshTaskCards() {
    document.querySelectorAll('[data-task-card]').forEach((card) => {
      const taskId = card.dataset.taskCard;
      if (!taskId || !window.TaskState) return;

      const meta = card.querySelector('[data-task-meta]');
      if (meta) meta.textContent = TaskState.getMetaLabel(taskId);

      const actions = card.querySelector('[data-task-actions]');
      if (actions) actions.innerHTML = renderTaskCardActions(taskId);
    });
  }

  window.StaffActions = {
    syncRequestButtons,
    syncRequestPage,
    refreshTaskCards,

    acceptRequest(taskId) {
      if (window.TaskState) {
        TaskState.setStatus(taskId, 'accepted');
      }
      refreshTaskCards();
      if (window.navigate) {
        navigate('staff/task-request', { id: taskId }, true);
      } else {
        location.hash = 'staff/task-request?id=' + encodeURIComponent(taskId);
      }
    },

    startRequest(taskId) {
      if (window.TaskState) {
        TaskState.setStatus(taskId, 'started');
      }
      refreshTaskCards();
      if (window.navigate) {
        navigate('staff/task-active', { id: taskId, started: '1' }, true);
      } else {
        location.hash = 'staff/task-active?id=' + encodeURIComponent(taskId) + '&started=1';
      }
    },

    openActiveTask(taskId) {
      if (window.navigate) {
        navigate('staff/task-active', { id: taskId, started: '1' }, true);
      } else {
        location.hash = 'staff/task-active?id=' + encodeURIComponent(taskId) + '&started=1';
      }
    },

    openRequestDetails(taskId) {
      if (window.navigate) {
        navigate('staff/task-request', { id: taskId }, true);
      } else {
        location.hash = 'staff/task-request?id=' + encodeURIComponent(taskId);
      }
    },

    toggleServicePause() {
      const taskId = getTaskIdFromHash();
      const btn = document.getElementById('btn-pause-service');
      const banner = document.getElementById('service-status-banner');
      if (!window.TaskTimer) return;

      TaskTimer.toggle();
      const paused = TaskTimer.isPaused();

      if (window.TaskState) {
        if (paused) TaskState.setStatus(taskId, 'paused');
        else TaskState.setStatus(taskId, 'started');
      }

      if (btn) btn.textContent = paused ? '▶ Resume Service' : '⏸ Pause Service';
      if (banner) {
        banner.className = 'status-banner ' + (paused ? 'paused' : 'running');
        const status = banner.querySelector('[data-timer-status]');
        if (status) status.textContent = paused ? 'Service Paused' : 'In Progress';
      }
    },

    completeService() {
      const taskId = getTaskIdFromHash();
      if (window.TaskTimer) TaskTimer.stop();
      if (window.TaskState) {
        TaskState.setStatus(taskId, 'ended');
        TaskState.setStatus(taskId, 'done');
      }
      refreshTaskCards();
      alert('Service completed successfully!');
      if (window.navigate) navigate('staff/task-detail', { id: taskId }, true);
      else location.hash = 'staff/task-detail?id=' + encodeURIComponent(taskId);
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

    syncTaskDetailPage(taskId) {
      const page = document.getElementById('page-staff-task-detail');
      if (!page || !window.TaskState) return;

      const status = TaskState.getStatus(taskId);
      const timeline = page.querySelector('[data-request-timeline]');
      if (timeline) timeline.innerHTML = TaskState.renderTimelineHtml(taskId);

      const data = getTaskData(taskId);
      if (data) {
        const roomEl = page.querySelector('[data-dynamic-room]');
        if (roomEl) roomEl.textContent = data.room || '';
        page.querySelectorAll('[data-dynamic-field="taskTitle"]').forEach((el) => {
          el.textContent = data.taskTitle || data.name || '';
        });
        const timeEl = page.querySelector('[data-dynamic-field="time"]');
        if (timeEl) timeEl.textContent = data.time || '—';
      }

      const feedback = page.querySelector('[data-task-feedback]');
      const xpReward = page.querySelector('[data-task-xp]');
      const isDone = status === 'done' || status === 'ended';
      if (feedback) feedback.hidden = !isDone;
      if (xpReward) xpReward.hidden = !isDone;
    },
  };

  window.addEventListener('hashchange', () => {
    const path = (location.hash.slice(1) || '').split('?')[0];
    const taskId = getTaskIdFromHash();

    refreshTaskCards();

    if (path === 'staff/task-request') {
      syncRequestPage(taskId);
    }
    if (path === 'staff/task-detail') {
      StaffActions.syncTaskDetailPage(taskId);
    }
    if (path === 'staff/pass-request' || path === 'staff/reject-request' || path === 'staff/request-paused') {
      document.querySelectorAll('#page-staff-pass-request [data-param-id], #page-staff-request-paused [data-param-id], #page-staff-reject-request [data-param-id]').forEach((el) => {
        el.dataset.paramId = taskId;
      });
    }
    if (path === 'staff/task-active') {
      document.querySelectorAll('#page-staff-task-active [data-nav="staff/pass-request"]').forEach((el) => {
        el.dataset.paramId = taskId;
      });
      const timeline = document.querySelector('#page-staff-task-active [data-request-timeline]');
      if (timeline && window.TaskState) {
        timeline.innerHTML = TaskState.renderTimelineHtml(taskId);
      }
    }
  });

  document.addEventListener('DOMContentLoaded', () => {
    refreshTaskCards();
  });
})();
