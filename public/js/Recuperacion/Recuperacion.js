// public/js/Recuperacion/Recuperacion.js

// ── Elementos del DOM ──────────────────────────────────────────────────────────
const toggleContrasena = document.querySelector('#toggleContrasena');
const contrasenaInput  = document.querySelector('#contrasena');
const toggleConfirm    = document.querySelector('#toggleConfirm');
const confirmInput     = document.querySelector('#contrasena_confirm');
const restablecerForm  = document.querySelector('#restablecerForm');
const restablecerBtn   = document.querySelector('#restablecerBtn');

// Iconos de requisitos
const letrasIcon  = document.querySelector('#letrasIcon');
const numerosIcon = document.querySelector('#numerosIcon');
const especiaIcon = document.querySelector('#especiaIcon');

// ── Patrones de validación ─────────────────────────────────────────────────────
const patronLetras   = /[a-zA-Z]/g;
const patronNumeros  = /[0-9]/g;
const patronEspecial = /[_\-*!]/g;

// ── Funciones ──────────────────────────────────────────────────────────────────

/**
 * Valida si la contraseña cumple con los requisitos mínimos.
 * @param {string} password
 * @returns {{ letras: boolean, numeros: boolean, especial: boolean }}
 */
function validarContrasena(password) {
    const letras   = (password.match(patronLetras)   || []).length;
    const numeros  = (password.match(patronNumeros)  || []).length;
    const especial = (password.match(patronEspecial) || []).length;

    return {
        letras:   letras   >= 5,
        numeros:  numeros  >= 2,
        especial: especial >= 1
    };
}

/**
 * Actualiza visualmente los indicadores y habilita/deshabilita el botón.
 */
function actualizarIndicadores() {
    const validacion = validarContrasena(contrasenaInput.value);

    // Letras
    letrasIcon.classList.toggle('valid', validacion.letras);

    // Números
    numerosIcon.classList.toggle('valid', validacion.numeros);

    // Caracteres especiales
    especiaIcon.classList.toggle('valid', validacion.especial);

    // Habilitar botón solo si todos los requisitos se cumplen
    const todasValid = validacion.letras && validacion.numeros && validacion.especial;
    if (restablecerBtn) restablecerBtn.disabled = !todasValid;
}

// ── Toggle mostrar/ocultar: Nueva Contraseña ───────────────────────────────────
if (toggleContrasena && contrasenaInput) {
    toggleContrasena.addEventListener('click', function () {
        const type = contrasenaInput.getAttribute('type') === 'password' ? 'text' : 'password';
        contrasenaInput.setAttribute('type', type);
        this.innerHTML = type === 'password'
            ? '<i class="bi bi-eye"></i>'
            : '<i class="bi bi-eye-slash"></i>';
    });
}

// ── Toggle mostrar/ocultar: Confirmar Contraseña ───────────────────────────────
if (toggleConfirm && confirmInput) {
    toggleConfirm.addEventListener('click', function () {
        const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmInput.setAttribute('type', type);
        this.innerHTML = type === 'password'
            ? '<i class="bi bi-eye"></i>'
            : '<i class="bi bi-eye-slash"></i>';
    });
}

// ── Validación en tiempo real ──────────────────────────────────────────────────
if (contrasenaInput) {
    contrasenaInput.addEventListener('input', actualizarIndicadores);
}

// Ejecutar al cargar por si hay valor previo (ej: autocompletado)
actualizarIndicadores();

// ── Validación antes de enviar el formulario ───────────────────────────────────
if (restablecerForm) {
    restablecerForm.addEventListener('submit', function (e) {
        const validacion = validarContrasena(contrasenaInput.value);

        if (!validacion.letras || !validacion.numeros || !validacion.especial) {
            e.preventDefault();
            alert('La contraseña no cumple con los requisitos mínimos.');
            return false;
        }
    });
}