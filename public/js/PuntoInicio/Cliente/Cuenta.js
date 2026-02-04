document.addEventListener('DOMContentLoaded', function() {
    const formCuenta = document.getElementById('formCuenta');
    const contrasena = document.getElementById('contrasena');
    const contrasenaConfirmacion = document.getElementById('contrasena_confirmacion');
    const telefono = document.getElementById('telefono');
    const correoElectronico = document.getElementById('correo_electronico');

    // Validación al enviar el formulario
    if (formCuenta) {
        formCuenta.addEventListener('submit', function(e) {
            let isValid = true;
            let mensajesError = [];

            // Limpiar errores previos
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });

            // Validar contraseñas si se están cambiando
            if (contrasena.value !== '' || contrasenaConfirmacion.value !== '') {
                if (contrasena.value !== contrasenaConfirmacion.value) {
                    isValid = false;
                    mensajesError.push('Las contraseñas no coinciden');
                    contrasena.classList.add('is-invalid');
                    contrasenaConfirmacion.classList.add('is-invalid');
                } else if (contrasena.value.length < 6) {
                    isValid = false;
                    mensajesError.push('La contraseña debe tener al menos 6 caracteres');
                    contrasena.classList.add('is-invalid');
                }
            }

            // Validar formato de correo electrónico
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(correoElectronico.value)) {
                isValid = false;
                mensajesError.push('Por favor, ingrese un correo electrónico válido');
                correoElectronico.classList.add('is-invalid');
            }

            // Validar formato de teléfono (solo números, espacios, guiones y paréntesis)
            const telefonoRegex = /^[0-9\s\-+()]+$/;
            if (!telefonoRegex.test(telefono.value) || telefono.value.length < 7) {
                isValid = false;
                mensajesError.push('El teléfono debe contener al menos 7 dígitos');
                telefono.classList.add('is-invalid');
            }

            // Mostrar errores si los hay
            if (!isValid) {
                e.preventDefault();
                mostrarAlerta(mensajesError.join('<br>'), 'danger');
                
                // Scroll al primer error
                const primerError = document.querySelector('.is-invalid');
                if (primerError) {
                    primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }

    // Remover clase de error al escribir
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });

    // Validación en tiempo real de contraseñas
    if (contrasenaConfirmacion) {
        contrasenaConfirmacion.addEventListener('input', function() {
            if (contrasena.value !== '' && this.value !== '') {
                if (contrasena.value !== this.value) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                    contrasena.classList.remove('is-invalid');
                }
            }
        });
    }

    // Cerrar alertas automáticamente después de 5 segundos
    const alertas = document.querySelectorAll('.alert');
    alertas.forEach(alerta => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alerta);
            bsAlert.close();
        }, 5000);
    });
});

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo) {
    const alertaExistente = document.querySelector('.alert-custom');
    if (alertaExistente) {
        alertaExistente.remove();
    }

    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show alert-custom`;
    alerta.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const formularioContainer = document.querySelector('.formulario-contenedor');
    formularioContainer.insertBefore(alerta, formularioContainer.firstChild);

    // Scroll suave hacia la alerta
    alerta.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

    // Auto cerrar después de 5 segundos
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alerta);
        bsAlert.close();
    }, 5000);
}