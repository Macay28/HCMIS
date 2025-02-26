document.addEventListener('DOMContentLoaded', function() {
  const togglePassword = document.querySelector('.show-hide-toggle');
  const passwordField = document.querySelector('#password');

  togglePassword.addEventListener('click', function() {
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    this.classList.toggle('show-password');
  });
});