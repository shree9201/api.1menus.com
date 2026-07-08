(function () {
  const STORAGE_KEY = 'oneMenusTaskStates';

  const STATUS_ORDER = ['open', 'accepted', 'started', 'ended', 'done'];

  const STATUS_LABELS = {
    open: 'Open',
    accepted: 'Accepted',
    started: 'Started',
    ended: 'Ended',
    paused: 'Paused',
    done: 'Done',
  };

  function now() {
    return Date.now();
  }

  function loadAll() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      return raw ? JSON.parse(raw) : {};
    } catch (e) {
      return {};
    }
  }

  function saveAll(states) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(states));
  }

  function defaultOpenedAt(taskId) {
    const offsets = {
      'room-204': 2 * 60 * 1000,
      'room-305-hk': 8 * 60 * 1000,
      'room-308': 30 * 60 * 1000,
      'room-512': 15 * 60 * 1000,
    };
    return now() - (offsets[taskId] || 5 * 60 * 1000);
  }

  function seedDefaults(states) {
    const t = now();
    if (!states['room-305-hk']) {
      states['room-305-hk'] = {
        status: 'accepted',
        openedAt: t - 8 * 60 * 1000,
        acceptedAt: t - 3 * 60 * 1000,
      };
    }
    if (!states['room-308']) {
      states['room-308'] = {
        status: 'done',
        openedAt: t - 45 * 60 * 1000,
        acceptedAt: t - 40 * 60 * 1000,
        startedAt: t - 38 * 60 * 1000,
        endedAt: t - 15 * 60 * 1000,
        doneAt: t - 14 * 60 * 1000,
      };
    }
    return states;
  }

  function ensureTask(taskId) {
    const states = seedDefaults(loadAll());
    if (!states[taskId]) {
      states[taskId] = {
        status: 'open',
        openedAt: defaultOpenedAt(taskId),
      };
      saveAll(states);
    }
    return states[taskId];
  }

  function getStatus(taskId) {
    return ensureTask(taskId).status || 'open';
  }

  function setStatus(taskId, status, extra) {
    const states = seedDefaults(loadAll());
    const task = states[taskId] || { status: 'open', openedAt: now() };
    const ts = now();

    if (status === 'open' && !task.openedAt) task.openedAt = ts;
    if (status === 'accepted') task.acceptedAt = ts;
    if (status === 'started') task.startedAt = ts;
    if (status === 'ended') task.endedAt = ts;
    if (status === 'done') {
      task.endedAt = task.endedAt || ts;
      task.doneAt = ts;
    }
    if (status === 'paused') task.pausedAt = ts;
    if (extra) Object.assign(task, extra);

    task.status = status;
    states[taskId] = task;
    saveAll(states);
    return task;
  }

  function formatDuration(ms) {
    if (ms < 0) ms = 0;
    const sec = Math.floor(ms / 1000);
    if (sec < 60) return sec + 's';
    const min = Math.floor(sec / 60);
    const rem = sec % 60;
    if (min < 60) return rem ? min + 'm ' + rem + 's' : min + 'm';
    const hr = Math.floor(min / 60);
    const m = min % 60;
    return m ? hr + 'h ' + m + 'm' : hr + 'h';
  }

  function formatRelative(ts) {
    if (!ts) return '—';
    const diff = now() - ts;
    if (diff < 60000) return 'Just now';
    if (diff < 3600000) return Math.floor(diff / 60000) + ' min ago';
    if (diff < 86400000) return Math.floor(diff / 3600000) + ' hr ago';
    return new Date(ts).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  }

  const STEP_DEFS = [
    { key: 'open', field: 'openedAt', label: 'Open — Request received' },
    { key: 'accepted', field: 'acceptedAt', label: 'Accepted — Staff accepted request' },
    { key: 'started', field: 'startedAt', label: 'Started — Service timer began' },
    { key: 'ended', field: 'endedAt', label: 'Ended — Service completed' },
    { key: 'done', field: 'doneAt', label: 'Done — Request closed' },
  ];

  function effectiveStatus(task) {
    return task.status === 'paused' ? 'started' : task.status;
  }

  function renderTimelineHtml(taskId) {
    const task = ensureTask(taskId);
    const status = effectiveStatus(task);
    const statusIdx = STATUS_ORDER.indexOf(status);
    let html = '';
    let prevAt = null;

    STEP_DEFS.forEach((def, idx) => {
      const at = task[def.field];
      const isPast = idx < statusIdx;
      const isCurrent = def.key === status;
      const isFuture = idx > statusIdx;

      if (at) {
        const delta = prevAt ? '+' + formatDuration(at - prevAt) + ' from previous' : null;
        const state = isCurrent ? 'current' : 'done';
        const dot = state === 'done' ? '✓' : '●';
        html += `
          <div class="timeline-step ${state}">
            <span class="step-dot">${dot}</span>
            <div class="step-body">
              <h5>${def.label}</h5>
              <p>${formatRelative(at)}</p>
              ${delta ? `<p class="timeline-delta">${delta}</p>` : ''}
            </div>
          </div>`;
        prevAt = at;
      } else if (isFuture || (isCurrent && !at)) {
        html += `
          <div class="timeline-step pending">
            <span class="step-dot">○</span>
            <div class="step-body">
              <h5>${def.label}</h5>
              <p>Pending</p>
            </div>
          </div>`;
      } else if (isPast && !at) {
        html += `
          <div class="timeline-step pending">
            <span class="step-dot">○</span>
            <div class="step-body">
              <h5>${def.label}</h5>
              <p>Skipped</p>
            </div>
          </div>`;
      }
    });

    if (task.status === 'paused' && task.pausedAt) {
      html += `
        <div class="timeline-step current">
          <span class="step-dot">●</span>
          <div class="step-body">
            <h5>Paused — Service on hold</h5>
            <p>${formatRelative(task.pausedAt)}</p>
            ${prevAt ? `<p class="timeline-delta">+${formatDuration(task.pausedAt - prevAt)} from previous</p>` : ''}
          </div>
        </div>`;
    }

    return html;
  }

  function getMetaLabel(taskId) {
    const task = ensureTask(taskId);
    switch (task.status) {
      case 'open':
        return formatRelative(task.openedAt);
      case 'accepted':
        return 'Accepted · Ready to start';
      case 'started':
        return 'In Progress';
      case 'paused':
        return 'Paused';
      case 'ended':
      case 'done':
        return 'Completed';
      default:
        return '—';
    }
  }

  window.TaskState = {
    getStatus,
    ensureTask,
    setStatus,
    renderTimelineHtml,
    getMetaLabel,
    formatDuration,
    formatRelative,
    getStartedAt: (taskId) => ensureTask(taskId).startedAt || null,
  };
})();
