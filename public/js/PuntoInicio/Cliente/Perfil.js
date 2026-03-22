// ============================================================
//  Dropdown usuario — desktop Y móvil con contador
// ============================================================
function initDropdownUsuario() {
    const navbar = document.getElementById('mainNavbar');
    let abiertos = 0;

    function setupDropdown(toggleId, menuId) {
        const toggle = document.getElementById(toggleId);
        const menu   = document.getElementById(menuId);
        if (!toggle || !menu) return;

        let estaAbierto = false;

        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            estaAbierto ? cerrar() : abrir();
        });

        document.addEventListener('click', function (e) {
            if (estaAbierto && !toggle.contains(e.target) && !menu.contains(e.target)) cerrar();
        });

        function abrir() {
            if (estaAbierto) return;
            estaAbierto = true;
            abiertos++;
            menu.classList.add('show');
            toggle.setAttribute('aria-expanded', 'true');
            if (navbar) navbar.classList.add('dropdown-open');
        }

        function cerrar() {
            if (!estaAbierto) return;
            estaAbierto = false;
            abiertos = Math.max(0, abiertos - 1);
            menu.classList.remove('show');
            toggle.setAttribute('aria-expanded', 'false');
            if (abiertos === 0 && navbar) navbar.classList.remove('dropdown-open');
        }
    }

    setupDropdown('userDropdownToggle',       'userDropdownMenu');
    setupDropdown('userDropdownToggleMobile', 'userDropdownMenuMobile');
}

// ============================================================
//  DOMContentLoaded
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    initDropdownUsuario();

    const formCuenta             = document.getElementById('formCuenta');
    const contrasena             = document.getElementById('contrasena');
    const contrasenaConfirmacion = document.getElementById('contrasena_confirmacion');
    const telefono               = document.getElementById('telefono');
    const correoElectronico      = document.getElementById('correo_electronico');
    const patronTelefono         = /^[0-9]{10}$/;

    // ========== TELÉFONO — validación en tiempo real ==========
    if (telefono) {
        telefono.addEventListener('input', function () {
            if (patronTelefono.test(this.value)) {
                this.classList.remove('is-invalid');
            } else if (this.value.length > 0) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });

        telefono.addEventListener('blur', function () {
            if (this.value.length > 0 && !patronTelefono.test(this.value)) {
                this.classList.add('is-invalid');
            }
        });
    }

    // ========== VALIDACIÓN AL ENVIAR ==========
    if (formCuenta) {
        formCuenta.addEventListener('submit', function (e) {
            let isValid = true;
            let mensajesError = [];

            // Limpiar errores previos
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            const departamento = document.getElementById('selectDepartamento');
            const municipio    = document.getElementById('selectMunicipio');

            if (!departamento.value) {
                isValid = false;
                mensajesError.push('Por favor selecciona un departamento');
                departamento.classList.add('is-invalid');
            }

            if (!municipio.value) {
                isValid = false;
                mensajesError.push('Por favor selecciona un municipio');
                municipio.classList.add('is-invalid');
            }

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

            // Validar correo electrónico
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(correoElectronico.value)) {
                isValid = false;
                mensajesError.push('Por favor, ingrese un correo electrónico válido');
                correoElectronico.classList.add('is-invalid');
            }

            // Validar teléfono — exactamente 10 dígitos
            if (!patronTelefono.test(telefono.value)) {
                isValid = false;
                mensajesError.push('El número de teléfono debe tener exactamente 10 dígitos');
                telefono.classList.add('is-invalid');
            }

            if (!isValid) {
                e.preventDefault();
                mostrarAlerta(mensajesError.join('<br>'), 'danger');
                const primerError = document.querySelector('.is-invalid');
                if (primerError) primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }

    // Remover clase de error al escribir
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
        input.addEventListener('input', function () {
            this.classList.remove('is-invalid');
        });
    });

    // Validación en tiempo real de contraseñas
    if (contrasenaConfirmacion) {
        contrasenaConfirmacion.addEventListener('input', function () {
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
    document.querySelectorAll('.alert').forEach(alerta => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alerta);
            bsAlert.close();
        }, 5000);
    });
});

// ============================================================
//  Mostrar alerta en el formulario
// ============================================================
function mostrarAlerta(mensaje, tipo) {
    const alertaExistente = document.querySelector('.alert-custom');
    if (alertaExistente) alertaExistente.remove();

    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show alert-custom`;
    alerta.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    const formularioContainer = document.querySelector('.formulario-contenedor');
    formularioContainer.insertBefore(alerta, formularioContainer.firstChild);

    alerta.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alerta);
        bsAlert.close();
    }, 5000);
}