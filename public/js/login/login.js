const togglePassword = document.querySelector('#togglePassword');
const passwordInput = document.querySelector('#contrasena');

togglePassword.addEventListener('click', function () {
    
    // Cambia el tipo de input
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    // Cambia el ícono
    this.innerHTML = type === 'password' 
      ? '<i class="bi bi-eye"></i>' 
      : '<i class="bi bi-eye-slash"></i>';
      
  });