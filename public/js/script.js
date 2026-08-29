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
});