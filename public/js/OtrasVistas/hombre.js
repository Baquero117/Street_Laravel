// ============================================================
//  TOAST PERSONALIZADO (igual que inicio)
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
//  Dropdown usuario — desktop Y móvil por separado
// ============================================================
function initDropdownUsuario() {
    // Inicializa un dropdown dado su toggle ID y menu ID
    function setupDropdown(toggleId, menuId) {
        const toggle = document.getElementById(toggleId);
        const menu   = document.getElementById(menuId);
        if (!toggle || !menu) return;

        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            menu.classList.contains('show') ? cerrar() : abrir();
        });

        // Cerrar al click fuera
        document.addEventListener('click', function (e) {
            if (!toggle.contains(e.target) && !menu.contains(e.target)) cerrar();
        });

        function abrir()  { menu.classList.add('show');    toggle.setAttribute('aria-expanded', 'true');  }
        function cerrar() { menu.classList.remove('show'); toggle.setAttribute('aria-expanded', 'false'); }
    }

    setupDropdown('userDropdownToggle',       'userDropdownMenu');       // desktop
    setupDropdown('userDropdownToggleMobile', 'userDropdownMenuMobile'); // móvil
}

// ============================================================
//  Variables globales — modal / carrito
// ============================================================
const modal = new bootstrap.Modal(document.getElementById('detalleModal'));
let tallaSeleccionada     = null;
let productoActual        = null;
let idDetalleSeleccionado = null;

// ============================================================
//  DOMContentLoaded
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    initDropdownUsuario();

    // Click en imagen abre modal
    document.querySelectorAll('.product-image').forEach(imagen => {
        imagen.addEventListener('click', function () {
            verDetalle(this.getAttribute('data-id'));
        });
        imagen.style.cursor = 'pointer';
    });

    // En móvil: toda la card es clickeable
    if (window.innerWidth < 768) {
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function (e) {
                if (e.target.closest('.btn-favorito')) return;
                const img = this.querySelector('.product-image');
                if (img) verDetalle(img.getAttribute('data-id'));
            });
        });
    }

    actualizarContadorCarrito();
    initScrollEffects();
    verificarFavoritosAlCargar();
});

// ============================================================
//  Scroll del navbar
// ============================================================
function initScrollEffects() {
    const brandLogo = document.getElementById('brandLogo');
    const navbar    = document.getElementById('mainNavbar');
    window.addEventListener('scroll', function () {
        if (window.pageYOffset > 50) {
            brandLogo.classList.add('fade-out');
            navbar.classList.add('scrolled');
        } else {
            brandLogo.classList.remove('fade-out');
            navbar.classList.remove('scrolled');
        }
    });
}

// ============================================================
//  Modal de detalle de producto
// ============================================================
function verDetalle(idProducto) {
    fetch(`/hombre/productos/${idProducto}/detalle`)
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

            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al cargar el producto', 'error');
        });
}

// ============================================================
//  Carrito
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
    .then(response => response.json())
    .then(data => {
        if (data.redirect) {
            mostrarNotificacion(data.mensaje, 'warning');
            setTimeout(() => window.location.href = data.redirect, 1500);
            return;
        }
        if (data.success) {
            mostrarNotificacion('✅ ' + data.mensaje, 'success');
            modal.hide();
            actualizarContadorCarrito();
        } else {
            mostrarNotificacion(data.mensaje || 'No se pudo agregar', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error de conexión', 'error');
    });
}

function actualizarContadorCarrito() {
    fetch('/carrito/contador')
        .then(response => response.json())
        .then(data => {
            const iconoCarrito = document.querySelector('.bi-bag');
            if (!iconoCarrito) return;
            const parent = iconoCarrito.parentElement;
            let badge    = parent.querySelector('.badge');
            if (data.cantidad > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className  = 'badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle';
                    badge.style.fontSize = '0.7rem';
                    parent.style.position = 'relative';
                    parent.appendChild(badge);
                }
                badge.textContent = data.cantidad;
            } else if (badge) {
                badge.remove();
            }
        })
        .catch(error => console.error('Error al actualizar contador:', error));
}

// ============================================================
//  FAVORITOS
// ============================================================
function verificarFavoritosAlCargar() {
    document.querySelectorAll('.btn-favorito').forEach(btn => {
        const idProducto = btn.getAttribute('data-id');
        fetch(`/favoritos/verificar/${idProducto}`, { headers: { 'Accept': 'application/json' } })
            .then(res => res.status === 401 ? null : res.json())
            .then(data => { if (data && data.esFavorito) marcarComoFavorito(btn); })
            .catch(() => {});
    });
}

function toggleFavorito(btnEl) {
    event.stopPropagation();
    const idProducto = parseInt(btnEl.getAttribute('data-id'));

    if (btnEl.classList.contains('is-favorito')) {
        window.location.href = '/favoritos';
        return;
    }

    btnEl.disabled = true;

    fetch('/favoritos/agregar', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Content-Type': 'application/json',
            'Accept':       'application/json',
        },
        body: JSON.stringify({ id_producto: idProducto }),
    })
    .then(res => {
        if (res.status === 401) { window.location.href = '/login'; return null; }
        return res.json();
    })
    .then(data => {
        if (!data) return;
        if (data.ok || (data.mensaje && data.mensaje.toLowerCase().includes('ya está'))) {
            marcarComoFavorito(btnEl);
        } else {
            mostrarNotificacion(data.mensaje || 'No se pudo agregar a favoritos.', 'error');
            btnEl.disabled = false;
        }
    })
    .catch(() => {
        mostrarNotificacion('Error de conexión. Inténtalo de nuevo.', 'error');
        btnEl.disabled = false;
    });
}

function marcarComoFavorito(btnEl) {
    btnEl.classList.add('is-favorito');
    btnEl.querySelector('i').className = 'bi bi-heart-fill';
    btnEl.title    = 'Guardado en favoritos';
    btnEl.disabled = false;
    btnEl.classList.add('pop');
    btnEl.addEventListener('animationend', () => btnEl.classList.remove('pop'), { once: true });
}