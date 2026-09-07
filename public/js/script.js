// Reddi PH / Pulse Alert — Global front-end behavior

document.addEventListener('DOMContentLoaded', function () {
  // Back button navigation (excluding modal back buttons)
  var backButtons = document.querySelectorAll(
    '#backBtn, #hospitaladminBackBtn, #doctorBackBtn, #nurseBackBtn, .back-btn, .login-back-btn, .hospitaladmin-login-back-btn, .doctor-login-back-btn, .nurse-login-back-btn'
  );

  backButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (window.history.length > 1) {
        window.history.back();
      } else {
        window.location.href = '/';
      }
    });
  });

  // =========================================================
  // FORGOT PASSWORD MODALS (Generic Handler)
  // =========================================================
  function setupForgotPasswordModal(role) {
    var modalId = role === 'admin' ? 'adminForgotPassModal' : (role + 'ForgotPassModal');
    var linkId = role === 'admin' ? 'hospitaladminForgotPassLink' : (role + 'ForgotPassLink');
    var closeId = role === 'admin' ? 'adminForgotPassBackBtn' : (role + 'ForgotPassBackBtn');
    var emailId = role === 'admin' ? 'adminforgotpass-email' : (role + 'forgotpass-email');

    var modal = document.getElementById(modalId);
    var link = document.getElementById(linkId);
    var closeBtn = document.getElementById(closeId);

    function openModal() {
      if (modal) {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        var emailInput = document.getElementById(emailId);
        if (emailInput) {
          setTimeout(function () { emailInput.focus(); }, 150);
        }
      }
    }

    function closeModal() {
      if (modal) {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
      }
    }

    if (link) {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        openModal();
      });
    }

    if (closeBtn) {
      closeBtn.addEventListener('click', function (e) {
        e.preventDefault();
        closeModal();
      });
    }

    if (modal) {
      modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
      });
    }

    return { open: openModal, close: closeModal };
  }

  var adminForgotModal = setupForgotPasswordModal('admin');
  var doctorForgotModal = setupForgotPasswordModal('doctor');
  var nurseForgotModal = setupForgotPasswordModal('nurse');

  // Global ESC key listener for all modals
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' || e.key === 'Esc') {
      adminForgotModal.close();
      doctorForgotModal.close();
      nurseForgotModal.close();
    }
  });

  // Forgot password form submission handler (Generic for all roles)
  function setupForgotPasswordForm(role, modalHandler) {
    var formId = role === 'admin' ? 'adminForgotPassForm' : (role + 'ForgotPassForm');
    var emailId = role === 'admin' ? 'adminforgotpass-email' : (role + 'forgotpass-email');
    var form = document.getElementById(formId);

    if (form) {
      form.addEventListener('submit', function (event) {
        var action = form.getAttribute('action');
        if (!action || action === '#') {
          event.preventDefault();
          var email = document.getElementById(emailId).value;
          alert('Reset link request sent for ' + email + ' (prototype).');
          modalHandler.close();
        }
      });
    }
  }

  setupForgotPasswordForm('admin', adminForgotModal);
  setupForgotPasswordForm('doctor', doctorForgotModal);
  setupForgotPasswordForm('nurse', nurseForgotModal);

  // =========================================================
  // FORM PROTOTYPES
  // =========================================================

  // Contact form submission prototype
  var contactForm = document.querySelector('.contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', function (event) {
      event.preventDefault();
      alert('Thanks! This is a static prototype — hook this form up to your backend.');
    });
  }

  // Login form submission prototype (supports Hospital Admin, Doctor, Nurse modules)
  var loginForms = document.querySelectorAll(
    '#loginForm, #hospitaladminLoginForm, #doctorLoginForm, #nurseLoginForm, .login-form, .hospitaladmin-login-form, .doctor-login-form, .nurse-login-form'
  );
  loginForms.forEach(function (form) {
    form.addEventListener('submit', function (event) {
      var action = form.getAttribute('action');
      if (!action || action === '#') {
        event.preventDefault();
        console.log('Login form submitted');
      }
    });
  });

  // =========================================================
  // SIGN UP ANIMATIONS & INTERACTIONS (Generic Handler)
  // =========================================================
  function setupSignupForm(role) {
    var formId = role + 'SignupForm';
    var shellSelector = '.' + role + '-signup-shell';
    var buttonSelector = '.' + role + '-signup-submit';
    var fieldSelector = '.' + role + '-signup-field input';

    // Add visibility class to shell
    var shell = document.querySelector(shellSelector);
    if (shell) {
      shell.classList.add('is-visible');
    }

    // Setup form submission handler
    var form = document.getElementById(formId);
    if (form) {
      form.addEventListener('submit', function () {
        var submitButton = form.querySelector(buttonSelector);
        if (submitButton) {
          submitButton.disabled = true;
          submitButton.textContent = 'Creating account...';
          submitButton.style.opacity = '0.9';
        }
      });
    }

    // Setup input focus/blur handlers
    var inputs = document.querySelectorAll(fieldSelector);
    inputs.forEach(function (input) {
      input.addEventListener('focus', function () {
        input.parentElement.classList.add('is-focused');
      });

      input.addEventListener('blur', function () {
        input.parentElement.classList.remove('is-focused');
      });
    });
  }

  setupSignupForm('nurse');
  setupSignupForm('doctor');
  setupSignupForm('hospitaladmin');

  // =========================================================
  // INCOMING DISPATCHES
  // =========================================================
  // Animation + light interaction layer only.
  // Capacity/color logic is handled by the Controller + CSS.

  incomingdispatchAnimateFacilityBars();
  incomingdispatchStaggerCardEntrance();
  incomingdispatchPulseActiveCards();
  incomingdispatchBindCardClicks();
  incomingdispatchBindBellShake();
  incomingdispatchBindSearchFocus();

  /**
   * Animates the facility-status progress bars.
   * The Controller provides data-percent.
   * CSS provides the capacity color.
   */
  function incomingdispatchAnimateFacilityBars() {
    var fills = document.querySelectorAll(
      '.incomingdispatch-metric-bar-fill'
    );

    fills.forEach(function (fill) {
      var target = parseFloat(fill.getAttribute('data-percent')) || 0;

      // Start from 0
      fill.style.width = '0%';

      // Keep the existing smooth animation
      requestAnimationFrame(function () {
        setTimeout(function () {
          fill.style.width = target + '%';
        }, 120);
      });
    });
  }

  /**
   * Gives the barangay cards a quick staggered fade/slide-up entrance.
   */
  function incomingdispatchStaggerCardEntrance() {
    var cards = document.querySelectorAll(
      '#incomingdispatch-barangay-grid .incomingdispatch-card'
    );

    cards.forEach(function (card, index) {
      card.classList.add('incomingdispatch-fade-in');
      card.style.animationDelay = (index * 40) + 'ms';
    });
  }

  /**
   * Adds a subtle pulse to cards with active dispatches.
   */
  function incomingdispatchPulseActiveCards() {
    var activeCards = document.querySelectorAll(
      '.incomingdispatch-card--active'
    );

    activeCards.forEach(function (card) {
      card.classList.add('incomingdispatch-card--pulse');
    });
  }

  /**
   * Barangay card click interaction.
   */
  function incomingdispatchBindCardClicks() {
    var cards = document.querySelectorAll(
      '.incomingdispatch-card'
    );

    cards.forEach(function (card) {
      card.addEventListener('click', function () {
        card.style.transform = 'scale(0.97)';

        setTimeout(function () {
          card.style.transform = '';
        }, 120);
      });
    });
  }

  /**
   * Notification bell animation.
   */
  function incomingdispatchBindBellShake() {
    var bell = document.getElementById(
      'incomingdispatch-bell-btn'
    );

    var dot = document.getElementById(
      'incomingdispatch-bell-dot'
    );

    if (!bell || !dot) {
      return;
    }

    bell.style.transition = 'transform 0.15s ease';

    setTimeout(function () {
      var ticks = 0;

      var shake = setInterval(function () {
        bell.style.transform =
          ticks % 2 === 0
            ? 'rotate(-12deg)'
            : 'rotate(12deg)';

        ticks++;

        if (ticks > 3) {
          clearInterval(shake);
          bell.style.transform = 'rotate(0deg)';
        }
      }, 90);
    }, 600);
  }

  /**
   * Search bar focus interaction.
   */
  function incomingdispatchBindSearchFocus() {
    var input = document.getElementById(
      'incomingdispatch-search-input'
    );

    if (!input) {
      return;
    }

    input.addEventListener('focus', function () {
      input.style.transition = 'width 0.2s ease';
      input.style.width = '340px';
    });

    input.addEventListener('blur', function () {
      input.style.width = '300px';
    });
  }
});

/* ===============================================
   DASHBOARD ANIMATIONS
   =============================================== */

/**
 * Animates dashboard metric bars from 0 to target percentage on load.
 */
function dashboardAnimateMetricBars() {
  const fills = document.querySelectorAll('.dashboard-metric-bar-fill');
  fills.forEach((fill) => {
    const target = fill.dataset.percent || 0;
    requestAnimationFrame(() => {
      setTimeout(() => {
        fill.style.width = target + '%';
      }, 120);
    });
  });
}

/**
 * Animates hospital network bed capacity bars on load.
 */
function dashboardAnimateHospitalBars() {
  const fills = document.querySelectorAll('.dashboard-hospital-bar-fill');
  fills.forEach((fill) => {
    const target = fill.dataset.percent || 0;
    requestAnimationFrame(() => {
      setTimeout(() => {
        fill.style.width = target + '%';
      }, 150);
    });
  });
}

/**
 * Initializes response performance line chart using Canvas API.
 * Reads chart data from the canvas element's data attribute.
 */
function dashboardInitChart() {
  const canvas = document.getElementById('dashboard-response-chart');
  if (!canvas) return;

  const dataStr = canvas.getAttribute('data-chart-data');
  if (!dataStr) return;

  try {
    const data = JSON.parse(dataStr);
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    // Set canvas size based on parent container
    const rect = canvas.parentElement.getBoundingClientRect();
    canvas.width = rect.width - 40;  // Account for padding
    canvas.height = 300;

    drawResponseChart(ctx, canvas, data);
  } catch (e) {
    console.error('Failed to initialize dashboard chart:', e);
  }
}

/**
 * Draws a smooth line chart for response time performance.
 * Uses bezier curves, gradient fills, and interactive hover effects.
 */
function drawResponseChart(ctx, canvas, data) {
  const { labels, values } = data;
  if (!labels || !values || labels.length === 0) return;

  const padding = 60;
  const chartWidth = canvas.width - padding * 2;
  const chartHeight = canvas.height - padding * 2;

  // Calculate scaling
  const maxValue = Math.max(...values);
  const minValue = Math.min(...values, 0);
  const range = maxValue - minValue || 1;
  const padding2 = range * 0.1; // Add 10% padding to range

  // Helper: Convert value to Y coordinate
  const valueToY = (value) => canvas.height - padding - ((value - minValue) / (range + padding2 * 2)) * chartHeight;
  const xStep = chartWidth / Math.max(labels.length - 1, 1);

  // Calculate all coordinates
  const points = values.map((value, index) => ({
    x: padding + index * xStep,
    y: valueToY(value),
    value: value,
    label: labels[index]
  }));

  // Clear canvas
  ctx.fillStyle = '#ffffff';
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  // Draw grid
  ctx.strokeStyle = '#e8e4df';
  ctx.lineWidth = 1;
  ctx.font = '12px Inter, sans-serif';
  ctx.fillStyle = '#999';
  ctx.textAlign = 'right';

  for (let i = 0; i <= 4; i++) {
    const y = padding + (chartHeight / 4) * i;
    ctx.beginPath();
    ctx.moveTo(padding - 5, y);
    ctx.lineTo(canvas.width - padding, y);
    ctx.stroke();

    // Y-axis labels
    const labelValue = Math.round(maxValue - (range / 4) * i);
    ctx.fillText(labelValue, padding - 12, y + 4);
  }

  // Draw axes
  ctx.strokeStyle = '#2a2620';
  ctx.lineWidth = 2;
  ctx.beginPath();
  ctx.moveTo(padding, padding);
  ctx.lineTo(padding, canvas.height - padding);
  ctx.lineTo(canvas.width - padding, canvas.height - padding);
  ctx.stroke();

  // Draw filled area under the curve with gradient
  const gradient = ctx.createLinearGradient(0, padding, 0, canvas.height - padding);
  gradient.addColorStop(0, 'rgba(212, 49, 70, 0.2)');
  gradient.addColorStop(1, 'rgba(212, 49, 70, 0)');

  ctx.fillStyle = gradient;
  ctx.beginPath();
  ctx.moveTo(points[0].x, canvas.height - padding);

  // Draw smooth curve using quadratic curves
  for (let i = 0; i < points.length; i++) {
    if (i === 0) {
      ctx.lineTo(points[0].x, points[0].y);
    } else {
      const prevPoint = points[i - 1];
      const currPoint = points[i];
      const controlX = (prevPoint.x + currPoint.x) / 2;
      const controlY = (prevPoint.y + currPoint.y) / 2;
      ctx.quadraticCurveTo(controlX, controlY, currPoint.x, currPoint.y);
    }
  }

  ctx.lineTo(points[points.length - 1].x, canvas.height - padding);
  ctx.closePath();
  ctx.fill();

  // Draw smooth line
  ctx.strokeStyle = '#d43146';
  ctx.lineWidth = 3;
  ctx.lineJoin = 'round';
  ctx.lineCap = 'round';

  ctx.beginPath();
  for (let i = 0; i < points.length; i++) {
    if (i === 0) {
      ctx.moveTo(points[0].x, points[0].y);
    } else {
      const prevPoint = points[i - 1];
      const currPoint = points[i];
      const controlX = (prevPoint.x + currPoint.x) / 2;
      const controlY = (prevPoint.y + currPoint.y) / 2;
      ctx.quadraticCurveTo(controlX, controlY, currPoint.x, currPoint.y);
    }
  }
  ctx.stroke();

  // Draw data points (circles)
  points.forEach((point, index) => {
    // Point shadow
    ctx.fillStyle = 'rgba(212, 49, 70, 0.1)';
    ctx.beginPath();
    ctx.arc(point.x, point.y, 6, 0, Math.PI * 2);
    ctx.fill();

    // Point
    ctx.fillStyle = '#d43146';
    ctx.beginPath();
    ctx.arc(point.x, point.y, 4, 0, Math.PI * 2);
    ctx.fill();

    // White center
    ctx.fillStyle = '#ffffff';
    ctx.beginPath();
    ctx.arc(point.x, point.y, 2, 0, Math.PI * 2);
    ctx.fill();
  });

  // Draw X-axis labels
  ctx.fillStyle = '#999';
  ctx.font = '12px Inter, sans-serif';
  ctx.textAlign = 'center';
  ctx.textBaseline = 'top';
  points.forEach((point, index) => {
    if (index % 2 === 0 || points.length <= 4) {
      ctx.fillText(point.label, point.x, canvas.height - padding + 12);
    }
  });

  // Store points for hover interaction
  canvas.chartPoints = points;
  canvas.chartData = { labels, values, range, minValue, maxValue };

  // Add hover interaction
  setupChartHover(canvas, ctx, padding);
}

/**
 * Setup hover tooltips on chart
 */
function setupChartHover(canvas, ctx, padding) {
  canvas.addEventListener('mousemove', function (event) {
    const rect = canvas.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;

    // Find if hovering over a point
    const hoveredPoint = canvas.chartPoints.find(p => {
      const distance = Math.sqrt((p.x - x) ** 2 + (p.y - y) ** 2);
      return distance < 10;
    });

    canvas.style.cursor = hoveredPoint ? 'pointer' : 'default';

    if (hoveredPoint) {
      // Redraw with highlight
      drawResponseChart(ctx, canvas, {
        labels: canvas.chartData.labels,
        values: canvas.chartData.values
      });

      // Draw tooltip
      ctx.fillStyle = 'rgba(42, 38, 32, 0.9)';
      ctx.fillRect(hoveredPoint.x - 40, hoveredPoint.y - 40, 80, 35);

      ctx.fillStyle = '#ffffff';
      ctx.font = 'bold 13px Inter, sans-serif';
      ctx.textAlign = 'center';
      ctx.fillText(hoveredPoint.value + ' min', hoveredPoint.x, hoveredPoint.y - 25);

      ctx.font = '11px Inter, sans-serif';
      ctx.fillStyle = '#e8e4df';
      ctx.fillText(hoveredPoint.label, hoveredPoint.x, hoveredPoint.y - 12);
    }
  });

  canvas.addEventListener('mouseleave', function () {
    canvas.style.cursor = 'default';
    // Optionally redraw without tooltip
    drawResponseChart(ctx, canvas, {
      labels: canvas.chartData.labels,
      values: canvas.chartData.values
    });
  });
}
/* ===============================================
   DASHBOARD HEADER INTERACTIONS
   =============================================== */

/**
 * Dashboard search input focus expansion (matching incomingdispatch design)
 */
function dashboardBindSearchFocus() {
  const input = document.getElementById('dashboard-search-input');
  if (!input) return;

  input.addEventListener('focus', function () {
    this.style.transition = 'width 0.2s ease';
    this.style.width = '340px';
  });

  input.addEventListener('blur', function () {
    this.style.width = '300px';
  });
}

/**
 * Dashboard bell notification shake animation
 */
function dashboardBindBellShake() {
  const bell = document.getElementById('dashboard-bell-btn');
  const dot = document.getElementById('dashboard-bell-dot');

  if (!bell || !dot) return;

  bell.style.transition = 'transform 0.15s ease';

  setTimeout(function () {
    let ticks = 0;
    const shake = setInterval(function () {
      bell.style.transform = ticks % 2 === 0 ? 'rotate(-12deg)' : 'rotate(12deg)';
      ticks++;

      if (ticks > 3) {
        clearInterval(shake);
        bell.style.transform = 'rotate(0deg)';
      }
    }, 90);
  }, 600);
}

/**
 * Dashboard user menu interaction
 */
function dashboardBindUserMenu() {
  const userMenu = document.getElementById('dashboard-user-menu');
  if (!userMenu) return;

  userMenu.addEventListener('click', function () {
    console.log('User menu clicked - ready for dropdown implementation');
  });
}

/**
 * Initialize all dashboard header interactions
 */
function dashboardInitHeaderInteractions() {
  dashboardBindSearchFocus();
  dashboardBindBellShake();
  dashboardBindUserMenu();
}