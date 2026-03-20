// ============================================================
//  TOAST PERSONALIZADO
// ============================================================
function mostrarNotificacion(mensaje, tipo) {
    const anterior = document.querySelector('.urban-toast');
    if (anterior) anterior.remove();

    const iconos  = { success: 'bi-check-circle-fill', error: 'bi-x-circle-fill', warning: 'bi-exclamation-triangle-fill', info: 'bi-info-circle-fill' };
    const colores = { success: '#28a745', error: '#dc3545', warning: '#ffc107', info: '#3b82f6' };

    const toast = document.createElement('div');
    toast.className = 'urban-toast';
    toast.innerHTML = `
        <i class="bi ${iconos[tipo] || 'bi-info-circle-fill'}" style="font-size:1.1rem;color:${colores[tipo] || '#3b82f6'};flex-shrink:0;"></i>
        <span>${mensaje}</span>
    `;
    toast.style.cssText = `
        position:fixed; bottom:30px; right:30px; z-index:99999;
        display:flex; align-items:center; gap:10px;
        background:#1a2332; color:#ffffff;
        padding:14px 20px; border-radius:10px;
        border-left:4px solid ${colores[tipo] || '#3b82f6'};
        box-shadow:0 8px 30px rgba(0,0,0,0.4);
        font-family:'Segoe UI',sans-serif; font-size:0.9rem;
        min-width:260px; max-width:360px;
        opacity:0; transform:translateY(20px);
        transition:opacity 0.3s ease, transform 0.3s ease;
    `;
    document.body.appendChild(toast);
    requestAnimationFrame(() => requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }));
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

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
//  Modal de confirmación
// ============================================================
function mostrarConfirmacion(mensaje, onAceptar) {
    const anterior = document.querySelector('.urban-modal-overlay');
    if (anterior) anterior.remove();

    const overlay = document.createElement('div');
    overlay.className = 'urban-modal-overlay';
    overlay.style.cssText = `
        position:fixed; inset:0; z-index:99999;
        background:rgba(0,0,0,0.6);
        display:flex; align-items:center; justify-content:center;
        opacity:0; transition:opacity 0.25s ease;
        backdrop-filter:blur(4px);
    `;
    overlay.innerHTML = `
        <div style="
            background:#1a2332; border-radius:14px;
            padding:32px 28px 24px; max-width:380px; width:90%;
            box-shadow:0 20px 60px rgba(0,0,0,0.5);
            transform:translateY(20px); transition:transform 0.25s ease;
            border:1px solid #2a3a4a; font-family:'Segoe UI',sans-serif;
        ">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <i class="bi bi-exclamation-triangle-fill" style="color:#ffc107;font-size:1.4rem;"></i>
                <p style="color:#c8d6e5; font-size:0.95rem; margin:0; line-height:1.5;">${mensaje}</p>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <button class="urban-btn-cancelar" style="
                    background:transparent; color:#8a9ab0;
                    border:1px solid #2a3a4a; border-radius:8px;
                    padding:9px 20px; cursor:pointer; font-size:0.88rem;
                ">Cancelar</button>
                <button class="urban-btn-aceptar" style="
                    background:#3b82f6; color:#fff;
                    border:none; border-radius:8px;
                    padding:9px 20px; cursor:pointer; font-size:0.88rem;
                ">Aceptar</button>
            </div>
        </div>
    `;
    document.body.appendChild(overlay);
    const box = overlay.querySelector('div');
    requestAnimationFrame(() => requestAnimationFrame(() => {
        overlay.style.opacity = '1';
        box.style.transform = 'translateY(0)';
    }));
    const cerrar = () => {
        overlay.style.opacity = '0';
        setTimeout(() => overlay.remove(), 250);
    };
    overlay.querySelector('.urban-btn-cancelar').addEventListener('click', cerrar);
    overlay.querySelector('.urban-btn-aceptar').addEventListener('click', () => { cerrar(); onAceptar(); });
    overlay.addEventListener('click', (e) => { if (e.target === overlay) cerrar(); });
}

// ============================================================
//  Ver más / Ver menos
// ============================================================
let expandido = false;

function toggleVerMas() {
    const btn     = document.getElementById('btnVerMas');
    const ocultas = document.querySelectorAll('.favorito-oculto');
    const total   = ocultas.length;

    if (!expandido) {
        ocultas.forEach((card, i) => {
            card.classList.add('mostrando');
            card.style.animationDelay = `${i * 60}ms`;
        });
        btn.classList.add('expandido');
        btn.innerHTML = '<i class="bi bi-chevron-up me-1"></i> Ver menos';
        expandido = true;
    } else {
        ocultas.forEach(card => {
            card.classList.remove('mostrando');
            card.style.animationDelay = '';
        });
        btn.classList.remove('expandido');
        btn.innerHTML = `<i class="bi bi-chevron-down me-1"></i> Ver ${total} productos más`;
        expandido = false;
        document.getElementById('lista-favoritos')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// ============================================================
//  Variables globales
// ============================================================
let modalDetalle          = null;
let tallaSeleccionada     = null;
let productoActual        = null;
let idDetalleSeleccionado = null;

// ============================================================
//  DOMContentLoaded
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    initDropdownUsuario();
    modalDetalle = new bootstrap.Modal(document.getElementById('detalleModal'));

    const csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    // Quitar favorito
    document.querySelectorAll('.btn-quitar-favorito').forEach(btn => {
        btn.addEventListener('click', function () {
            const idFavorito = this.dataset.id;
            const card       = document.getElementById(`card-favorito-${idFavorito}`);
            const btnEl      = this;

            mostrarConfirmacion('¿Deseas quitar este producto de tus favoritos?', () => {
                btnEl.disabled  = true;
                btnEl.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                fetch(`/favoritos/${idFavorito}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.ok) {
                        card.style.transition = 'opacity 0.3s, transform 0.3s';
                        card.style.opacity    = '0';
                        card.style.transform  = 'translateX(20px)';
                        setTimeout(() => { card.remove(); actualizarContador(); recalcularVerMas(); }, 300);
                        mostrarNotificacion('Producto quitado de favoritos', 'success');
                    } else {
                        mostrarNotificacion('Error al quitar favorito: ' + data.mensaje, 'error');
                        btnEl.disabled  = false;
                        btnEl.innerHTML = '<i class="bi bi-heart-fill me-1"></i> Quitar';
                    }
                })
                .catch(() => {
                    mostrarNotificacion('Error de conexión. Inténtalo de nuevo.', 'error');
                    btnEl.disabled  = false;
                    btnEl.innerHTML = '<i class="bi bi-heart-fill me-1"></i> Quitar';
                });
            });
        });
    });

    function actualizarContador() {
        const lista      = document.getElementById('lista-favoritos');
        const contadorEl = document.getElementById('favoritos-contador');
        if (!lista) return;

        const total = lista.querySelectorAll('.favorito-card').length;
        if (contadorEl) {
            contadorEl.innerHTML = `<i class="bi bi-heart-fill text-danger me-1"></i>
                ${total} ${total === 1 ? 'producto guardado' : 'productos guardados'}`;
        }
        if (total === 0) {
            lista.innerHTML = `
                <div class="formulario-contenedor text-center py-5">
                    <i class="bi bi-heart" style="font-size:3.5rem;color:#ccc;"></i>
                    <h5 class="mt-4 fw-semibold">Aún no tienes favoritos</h5>
                    <p class="text-muted" style="font-size:14px;">Cuando marques un producto con ♥, aparecerá aquí.</p>
                    <a href="/inicio" class="btn btn-guardar mt-2">Ir a la tienda</a>
                </div>`;
            if (contadorEl) contadorEl.remove();
            document.getElementById('ver-mas-wrap')?.remove();
        }
    }

    function recalcularVerMas() {
        const lista      = document.getElementById('lista-favoritos');
        const verMasWrap = document.getElementById('ver-mas-wrap');
        if (!lista) return;

        const cards = Array.from(lista.querySelectorAll('.favorito-card'));
        const total = cards.length;

        cards.forEach((card, i) => {
            if (i >= 4 && !expandido) {
                card.classList.add('favorito-oculto');
                card.classList.remove('mostrando');
            } else {
                card.classList.remove('favorito-oculto', 'mostrando');
            }
        });

        if (total <= 4 && verMasWrap) {
            verMasWrap.remove();
        } else if (total > 4 && verMasWrap) {
            const btn = document.getElementById('btnVerMas');
            if (btn && !expandido) btn.innerHTML = `<i class="bi bi-chevron-down me-1"></i> Ver ${total - 4} productos más`;
        }
    }
});

// ============================================================
//  Modal detalle
// ============================================================
function verDetalleFavorito(idProducto) {
    fetch(`/productos/${idProducto}/detalle`)
        .then(response => response.ok ? response.json() : Promise.reject('Error en red'))
        .then(data => {
            if (data.error) { mostrarNotificacion('Error al cargar el detalle', 'error'); return; }

            productoActual        = data;
            tallaSeleccionada     = null;
            idDetalleSeleccionado = null;

            document.getElementById('modalNombre').textContent      = data.nombre;
            document.getElementById('modalImagen').src              = data.imagen ? '/storage/' + data.imagen : 'https://via.placeholder.com/400x400';
            document.getElementById('modalDescripcion').textContent = data.descripcion || 'Sin descripción';
            document.getElementById('modalColor').textContent       = data.color || 'No especificado';
            document.getElementById('modalPrecio').textContent      = new Intl.NumberFormat('es-CO').format(data.precio);

            const tallasContainer = document.getElementById('modalTallas');
            tallasContainer.innerHTML = '';

            if (data.detalles && data.detalles.length > 0) {
                data.detalles.forEach(detalle => {
                    const cajaTalla       = document.createElement('div');
                    cajaTalla.className   = 'talla-item';
                    cajaTalla.textContent = detalle.talla;
                    cajaTalla.onclick = function () {
                        document.querySelectorAll('.talla-item').forEach(t => t.classList.remove('selected'));
                        this.classList.add('selected');
                        tallaSeleccionada     = detalle.talla;
                        idDetalleSeleccionado = detalle.id_detalle_producto;
                    };
                    tallasContainer.appendChild(cajaTalla);
                });
            } else {
                tallasContainer.innerHTML = '<span class="text-muted">Sin stock</span>';
            }

            modalDetalle.show();
        })
        .catch(() => mostrarNotificacion('Error al cargar el producto', 'error'));
}

// ============================================================
//  Carrito — robusto ante respuestas no-JSON
// ============================================================
function agregarAlCarrito() {
    if (!productoActual)        { mostrarNotificacion('Error: No hay producto seleccionado', 'error'); return; }
    if (!idDetalleSeleccionado) { mostrarNotificacion('Por favor selecciona una talla', 'warning'); return; }

    fetch('/carrito/agregar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({
            id_detalle_producto: idDetalleSeleccionado,
            talla:               tallaSeleccionada,
            cantidad:            1,
            precio:              productoActual.precio
        })
    })
    .then(response => {
        const contentType = response.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            mostrarNotificacion('Sesión expirada, inicia sesión de nuevo', 'warning');
            setTimeout(() => window.location.href = '/login', 1500);
            return null;
        }
        return response.json();
    })
    .then(data => {
        if (!data) return;
        if (data.redirect) {
            mostrarNotificacion(data.mensaje || 'Inicia sesión para continuar', 'warning');
            setTimeout(() => window.location.href = data.redirect, 1500);
            return;
        }
        if (data.success) {
            mostrarNotificacion('✅ Producto agregado al carrito', 'success');
            modalDetalle.hide();
        } else {
            mostrarNotificacion(data.mensaje || 'No se pudo agregar', 'error');
        }
    })
    .catch(error => {
        console.error('Error carrito:', error);
        mostrarNotificacion('Error de conexión al agregar', 'error');
    });
}