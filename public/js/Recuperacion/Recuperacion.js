// public/js/Recuperacion/Recuperacion.js

// Toggle para "Nueva Contraseña"
const toggleContrasena = document.querySelector('#toggleContrasena');
const contrasenaInput = document.querySelector('#contrasena');

if (toggleContrasena && contrasenaInput) {
    toggleContrasena.addEventListener('click', function () {
        const type = contrasenaInput.getAttribute('type') === 'password' ? 'text' : 'password';
        contrasenaInput.setAttribute('type', type);
        this.innerHTML = type === 'password'
            ? '<i class="bi bi-eye"></i>'
            : '<i class="bi bi-eye-slash"></i>';
    });
}

// Toggle para "Confirmar Contraseña"
const toggleConfirm = document.querySelector('#toggleConfirm');
const confirmInput = document.querySelector('#contrasena_confirm');

if (toggleConfirm && confirmInput) {
    toggleConfirm.addEventListener('click', function () {
        const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmInput.setAttribute('type', type);
        this.innerHTML = type === 'password'
            ? '<i class="bi bi-eye"></i>'
            : '<i class="bi bi-eye-slash"></i>';
    });
}