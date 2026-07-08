(function () {
  let intervalId = null;
  let startedAt = null;
  let pausedAt = null;
  let totalPausedMs = 0;
  let durationSec = 900;
  let taskId = null;
  let isPaused = false;

  function formatTime(sec) {
    const s = Math.max(0, Math.floor(sec));
    const m = Math.floor(s / 60);
    const r = s % 60;
    return String(m).padStart(2, '0') + ':' + String(r).padStart(2, '0');
  }

  function getElapsedSec() {
    if (!startedAt) return 0;
    const now = isPaused && pausedAt ? pausedAt : Date.now();
    return (now - startedAt - totalPausedMs) / 1000;
  }

  function tick() {
    const page = document.getElementById('page-staff-task-active');
    if (!page || !page.classList.contains('active')) return;

    const elapsed = getElapsedSec();
    const remaining = Math.max(0, durationSec - elapsed);
    const progress = Math.min(100, (elapsed / durationSec) * 100);

    const elapsedEl = page.querySelector('[data-timer-elapsed]');
    const remainEl = page.querySelector('[data-timer-remaining]');
    const pctEl = page.querySelector('[data-timer-percent]');
    const barEl = page.querySelector('[data-timer-bar]');
    const ringEl = page.querySelector('[data-timer-ring]');
    const statusEl = page.querySelector('[data-timer-status]');

    if (elapsedEl) elapsedEl.textContent = formatTime(elapsed);
    if (remainEl) remainEl.textContent = formatTime(remaining);
    if (pctEl) pctEl.textContent = Math.round(progress) + '%';
    if (barEl) barEl.style.width = progress + '%';
    if (ringEl) {
      const offset = 283 - (283 * progress) / 100;
      ringEl.style.strokeDashoffset = offset;
    }
    if (statusEl) {
      if (isPaused) statusEl.textContent = 'Paused';
      else if (remaining <= 0) statusEl.textContent = 'Over SLA';
      else if (remaining < durationSec * 0.2) statusEl.textContent = 'Almost due';
      else statusEl.textContent = 'In Progress';
    }

    page.querySelectorAll('[data-timer-bar-wrap]').forEach((wrap) => {
      wrap.classList.toggle('overdue', remaining <= 0 && !isPaused);
      wrap.classList.toggle('warning', remaining > 0 && remaining < durationSec * 0.2);
    });
  }

  function startTimer(id, durationMinutes, existingStartedAt) {
    stopTimer();
    taskId = id;
    durationSec = (durationMinutes || 15) * 60;
    startedAt = existingStartedAt || Date.now();
    pausedAt = null;
    totalPausedMs = 0;
    isPaused = false;
    intervalId = setInterval(tick, 1000);
    tick();
  }

  function pauseTimer() {
    if (!startedAt || isPaused) return;
    isPaused = true;
    pausedAt = Date.now();
    tick();
  }

  function resumeTimer() {
    if (!isPaused || !pausedAt) return;
    totalPausedMs += Date.now() - pausedAt;
    pausedAt = null;
    isPaused = false;
    tick();
  }

  function stopTimer() {
    if (intervalId) clearInterval(intervalId);
    intervalId = null;
    startedAt = null;
    pausedAt = null;
    totalPausedMs = 0;
    isPaused = false;
    taskId = null;
  }

  function togglePause() {
    if (isPaused) resumeTimer();
    else pauseTimer();
  }

  window.TaskTimer = {
    start: startTimer,
    pause: pauseTimer,
    resume: resumeTimer,
    stop: stopTimer,
    toggle: togglePause,
    isPaused: () => isPaused,
    getTaskId: () => taskId,
  };

  window.addEventListener('hashchange', () => {
    const path = (location.hash.slice(1) || '').split('?')[0];
    if (path !== 'staff/task-active') stopTimer();
  });
})();
