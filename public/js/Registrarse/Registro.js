const togglePassword = document.querySelector('#togglePassword');
const passwordInput  = document.querySelector('#contrasena');
const registroForm   = document.querySelector('#registroForm');
const registroBtn    = document.querySelector('#registroBtn');
const telefonoInput  = document.querySelector('#telefono');

// Iconos de requisitos
const letrasIcon  = document.querySelector('#letrasIcon');
const numerosIcon = document.querySelector('#numerosIcon');
const especiaIcon = document.querySelector('#especiaIcon');

// Patrones de validación
const patronLetras   = /[a-zA-Z]/g;
const patronNumeros  = /[0-9]/g;
const patronEspecial = /[_\-*!]/g;
const patronTelefono = /^[0-9]{10}$/;

// ============================================================
//  CONTRASEÑA
// ============================================================
function validarContrasena(password) {
    const letras  = (password.match(patronLetras)   || []).length;
    const numeros = (password.match(patronNumeros)  || []).length;
    const especial = (password.match(patronEspecial) || []).length;

    return {
        letras:  letras  >= 5,
        numeros: numeros >= 2,
        especial: especial >= 1
    };
}

function actualizarIndicadores() {
    const validacion = validarContrasena(passwordInput.value);

    validacion.letras   ? letrasIcon.classList.add('valid')   : letrasIcon.classList.remove('valid');
    validacion.numeros  ? numerosIcon.classList.add('valid')  : numerosIcon.classList.remove('valid');
    validacion.especial ? especiaIcon.classList.add('valid')  : especiaIcon.classList.remove('valid');

    const todasValid = validacion.letras && validacion.numeros && validacion.especial;
    registroBtn.disabled = !todasValid;
}

togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    this.innerHTML = type === 'password'
        ? '<i class="bi bi-eye"></i>'
        : '<i class="bi bi-eye-slash"></i>';
});

passwordInput.addEventListener('input', actualizarIndicadores);
actualizarIndicadores();

// ============================================================
//  TELÉFONO — validación en tiempo real
// ============================================================
if (telefonoInput) {
    telefonoInput.addEventListener('input', function () {
        if (patronTelefono.test(this.value)) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
            if (this.value.length > 0) this.classList.add('is-invalid');
        }
    });

    telefonoInput.addEventListener('blur', function () {
        if (!patronTelefono.test(this.value)) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        }
    });
}

// ============================================================
//  SUBMIT
// ============================================================
registroForm.addEventListener('submit', function (e) {
    const validacion = validarContrasena(passwordInput.value);

    if (!validacion.letras || !validacion.numeros || !validacion.especial) {
        e.preventDefault();
        alert('La contraseña no cumple con los requisitos mínimos.');
        return false;
    }

    if (!patronTelefono.test(telefonoInput.value)) {
        e.preventDefault();
        telefonoInput.classList.add('is-invalid');
        telefonoInput.classList.remove('is-valid');
        telefonoInput.focus();
        return false;
    }
});