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
  // FORGOT PASSWORD MODALS
  // =========================================================

  // --- 1. Hospital Admin Forgot Password Modal ---
  var adminForgotModal = document.getElementById('adminForgotPassModal');
  var adminForgotLink = document.getElementById('hospitaladminForgotPassLink');
  var adminForgotClose = document.getElementById('adminForgotPassBackBtn');

  function openAdminForgotModal() {
    if (adminForgotModal) {
      adminForgotModal.classList.add('is-open');
      adminForgotModal.setAttribute('aria-hidden', 'false');
      var emailInput = document.getElementById('adminforgotpass-email');
      if (emailInput) {
        setTimeout(function () {
          emailInput.focus();
        }, 150);
      }
    }
  }

  function closeAdminForgotModal() {
    if (adminForgotModal) {
      adminForgotModal.classList.remove('is-open');
      adminForgotModal.setAttribute('aria-hidden', 'true');
    }
  }

  if (adminForgotLink) {
    adminForgotLink.addEventListener('click', function (e) {
      e.preventDefault();
      openAdminForgotModal();
    });
  }

  if (adminForgotClose) {
    adminForgotClose.addEventListener('click', function (e) {
      e.preventDefault();
      closeAdminForgotModal();
    });
  }

  if (adminForgotModal) {
    adminForgotModal.addEventListener('click', function (e) {
      if (e.target === adminForgotModal) {
        closeAdminForgotModal();
      }
    });
  }

  // --- 2. Doctor Forgot Password Modal ---
  var doctorForgotModal = document.getElementById('doctorForgotPassModal');
  var doctorForgotLink = document.getElementById('doctorForgotPassLink');
  var doctorForgotClose = document.getElementById('doctorForgotPassBackBtn');

  function openDoctorForgotModal() {
    if (doctorForgotModal) {
      doctorForgotModal.classList.add('is-open');
      doctorForgotModal.setAttribute('aria-hidden', 'false');
      var emailInput = document.getElementById('doctorforgotpass-email');
      if (emailInput) {
        setTimeout(function () {
          emailInput.focus();
        }, 150);
      }
    }
  }

  function closeDoctorForgotModal() {
    if (doctorForgotModal) {
      doctorForgotModal.classList.remove('is-open');
      doctorForgotModal.setAttribute('aria-hidden', 'true');
    }
  }

  if (doctorForgotLink) {
    doctorForgotLink.addEventListener('click', function (e) {
      e.preventDefault();
      openDoctorForgotModal();
    });
  }

  if (doctorForgotClose) {
    doctorForgotClose.addEventListener('click', function (e) {
      e.preventDefault();
      closeDoctorForgotModal();
    });
  }

  if (doctorForgotModal) {
    doctorForgotModal.addEventListener('click', function (e) {
      if (e.target === doctorForgotModal) {
        closeDoctorForgotModal();
      }
    });
  }

  // --- 3. Nurse Forgot Password Modal ---
  var nurseForgotModal = document.getElementById('nurseForgotPassModal');
  var nurseForgotLink = document.getElementById('nurseForgotPassLink');
  var nurseForgotClose = document.getElementById('nurseForgotPassBackBtn');

  function openNurseForgotModal() {
    if (nurseForgotModal) {
      nurseForgotModal.classList.add('is-open');
      nurseForgotModal.setAttribute('aria-hidden', 'false');
      var emailInput = document.getElementById('nurseforgotpass-email');
      if (emailInput) {
        setTimeout(function () {
          emailInput.focus();
        }, 150);
      }
    }
  }

  function closeNurseForgotModal() {
    if (nurseForgotModal) {
      nurseForgotModal.classList.remove('is-open');
      nurseForgotModal.setAttribute('aria-hidden', 'true');
    }
  }

  if (nurseForgotLink) {
    nurseForgotLink.addEventListener('click', function (e) {
      e.preventDefault();
      openNurseForgotModal();
    });
  }

  if (nurseForgotClose) {
    nurseForgotClose.addEventListener('click', function (e) {
      e.preventDefault();
      closeNurseForgotModal();
    });
  }

  if (nurseForgotModal) {
    nurseForgotModal.addEventListener('click', function (e) {
      if (e.target === nurseForgotModal) {
        closeNurseForgotModal();
      }
    });
  }

  // Global ESC key listener for all modals
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' || e.key === 'Esc') {
      closeAdminForgotModal();
      closeDoctorForgotModal();
      closeNurseForgotModal();
    }
  });

  // Admin forgot password form submission handler
  var adminForgotForm = document.getElementById('adminForgotPassForm');
  if (adminForgotForm) {
    adminForgotForm.addEventListener('submit', function (event) {
      var action = adminForgotForm.getAttribute('action');
      if (!action || action === '#') {
        event.preventDefault();
        var email = document.getElementById('adminforgotpass-email').value;
        alert('Reset link request sent for ' + email + ' (prototype).');
        closeAdminForgotModal();
      }
    });
  }

  // Doctor forgot password form submission handler
  var doctorForgotForm = document.getElementById('doctorForgotPassForm');
  if (doctorForgotForm) {
    doctorForgotForm.addEventListener('submit', function (event) {
      var action = doctorForgotForm.getAttribute('action');
      if (!action || action === '#') {
        event.preventDefault();
        var input = document.getElementById('doctorforgotpass-email').value;
        alert('Reset link request sent for ' + input + ' (prototype).');
        closeDoctorForgotModal();
      }
    });
  }

  // Nurse forgot password form submission handler
  var nurseForgotForm = document.getElementById('nurseForgotPassForm');
  if (nurseForgotForm) {
    nurseForgotForm.addEventListener('submit', function (event) {
      var action = nurseForgotForm.getAttribute('action');
      if (!action || action === '#') {
        event.preventDefault();
        var input = document.getElementById('nurseforgotpass-email').value;
        alert('Reset link request sent for ' + input + ' (prototype).');
        closeNurseForgotModal();
      }
    });
  }

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
});