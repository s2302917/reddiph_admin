// Pulse Alert — front-end behavior
// Extracted from the inline onsubmit="" handler in index.php

document.addEventListener('DOMContentLoaded', function () {
  var contactForm = document.querySelector('.contact-form');

  if (contactForm) {
    contactForm.addEventListener('submit', function (event) {
      event.preventDefault();
      alert('Thanks! This is a static prototype — hook this form up to your backend.');
    });
  }
});