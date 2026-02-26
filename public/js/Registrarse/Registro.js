const togglePassword = document.querySelector('#togglePassword');
const passwordInput = document.querySelector('#contrasena');
const registroForm = document.querySelector('#registroForm');
const registroBtn = document.querySelector('#registroBtn');

// Iconos de requisitos
const letrasIcon = document.querySelector('#letrasIcon');
const numerosIcon = document.querySelector('#numerosIcon');
const especiaIcon = document.querySelector('#especiaIcon');

// Patrones de validación
const patronLetras = /[a-zA-Z]/g;
const patronNumeros = /[0-9]/g;
const patronEspecial = /[_\-*!]/g;

/**
 * Valida si la contraseña cumple con los requisitos
 * @param {string} password - La contraseña a validar
 * @returns {object} Objeto con el estado de cada requisito
 */
function validarContrasena(password) {
  const letras = (password.match(patronLetras) || []).length;
  const numeros = (password.match(patronNumeros) || []).length;
  const especial = (password.match(patronEspecial) || []).length;

  return {
    letras: letras >= 5,
    numeros: numeros >= 2,
    especial: especial >= 1
  };
}

/**
 * Actualiza visualmente los indicadores de requisitos
 */
function actualizarIndicadores() {
  const validacion = validarContrasena(passwordInput.value);

  // Actualizar ícono de letras
  if (validacion.letras) {
    letrasIcon.classList.add('valid');
  } else {
    letrasIcon.classList.remove('valid');
  }

  // Actualizar ícono de números
  if (validacion.numeros) {
    numerosIcon.classList.add('valid');
  } else {
    numerosIcon.classList.remove('valid');
  }

  // Actualizar ícono de caracteres especiales
  if (validacion.especial) {
    especiaIcon.classList.add('valid');
  } else {
    especiaIcon.classList.remove('valid');
  }

  // Habilitar/deshabilitar botón de registro
  const todasValid = validacion.letras && validacion.numeros && validacion.especial;
  registroBtn.disabled = !todasValid;
}

// Event listener para mostrar/ocultar contraseña
togglePassword.addEventListener('click', function () {
  const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordInput.setAttribute('type', type);

  this.innerHTML = type === 'password' 
    ? '<i class="bi bi-eye"></i>' 
    : '<i class="bi bi-eye-slash"></i>';
});

// Event listener para validar contraseña en tiempo real
passwordInput.addEventListener('input', actualizarIndicadores);

// Validar al cargar la página (en caso de que haya valor previo)
actualizarIndicadores();

// Validar antes de enviar el formulario
registroForm.addEventListener('submit', function (e) {
  const validacion = validarContrasena(passwordInput.value);
  
  if (!validacion.letras || !validacion.numeros || !validacion.especial) {
    e.preventDefault();
    alert('La contraseña no cumple con los requisitos mínimos.');
    return false;
  }
});