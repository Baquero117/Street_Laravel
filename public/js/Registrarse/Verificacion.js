// public/js/Verificacion/Verificacion.js

const inputs = document.querySelectorAll('.codigo-input');
const codigoCompleto = document.querySelector('#codigoCompleto');
const verificarBtn = document.querySelector('#verificarBtn');
const verificacionForm = document.querySelector('#verificacionForm');

// Enfocar el primer input al cargar
inputs[0].focus();

inputs.forEach((input, index) => {

    // Solo permitir números
    input.addEventListener('keypress', function (e) {
        if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
        }
    });

    input.addEventListener('input', function () {
        // Limpiar valor si no es número
        this.value = this.value.replace(/[^0-9]/g, '');

        if (this.value) {
            this.classList.add('filled');
            // Pasar al siguiente input
            if (index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        } else {
            this.classList.remove('filled');
        }

        actualizarCodigo();
    });

    // Retroceder con Backspace
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace' && !this.value && index > 0) {
            inputs[index - 1].focus();
            inputs[index - 1].value = '';
            inputs[index - 1].classList.remove('filled');
            actualizarCodigo();
        }
    });

    // Permitir pegar el código completo
    input.addEventListener('paste', function (e) {
        e.preventDefault();
        const pegado = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
        if (pegado.length === 6) {
            inputs.forEach((inp, i) => {
                inp.value = pegado[i] || '';
                inp.classList.toggle('filled', !!inp.value);
            });
            inputs[5].focus();
            actualizarCodigo();
        }
    });
});

function actualizarCodigo() {
    const codigo = Array.from(inputs).map(inp => inp.value).join('');
    codigoCompleto.value = codigo;

    // Habilitar botón solo si los 6 dígitos están llenos
    verificarBtn.disabled = codigo.length !== 6;
}

// Validar antes de enviar
verificacionForm.addEventListener('submit', function (e) {
    const codigo = codigoCompleto.value;
    if (codigo.length !== 6) {
        e.preventDefault();
        alert('Ingresa los 6 dígitos del código.');
    }
});