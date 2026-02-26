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
    console.log('Página cargada, buscando imágenes...');

    // Clicks en imágenes → abrir modal de detalle
    const imagenes = document.querySelectorAll('.product-image');
    imagenes.forEach((imagen) => {
        imagen.addEventListener('click', function () {
            const idProducto = this.getAttribute('data-id');
            verDetalle(idProducto);
        });
        imagen.style.cursor = 'pointer';
    });

    actualizarContadorCarrito();
    initScrollEffects();

    // Marcar los productos que ya son favoritos al cargar la página
    verificarFavoritosAlCargar();
});

// ============================================================
//  Scroll del navbar
// ============================================================
function initScrollEffects() {
    const brandLogo = document.getElementById('brandLogo');
    const navbar    = document.getElementById('mainNavbar');

    window.addEventListener('scroll', function () {
        const currentScroll = window.pageYOffset;
        if (currentScroll > 50) {
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
    fetch(`/productos/${idProducto}/detalle`)
        .then(response => response.ok ? response.json() : Promise.reject('Error en red'))
        .then(data => {
            if (data.error) {
                alert('Error al cargar el detalle');
                return;
            }

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
                    const cajaTalla = document.createElement('div');
                    cajaTalla.className  = 'talla-item';
                    cajaTalla.textContent = detalle.talla;

                    cajaTalla.onclick = function () {
                        document.querySelectorAll('.talla-item').forEach(t => t.classList.remove('selected'));
                        this.classList.add('selected');
                        tallaSeleccionada     = detalle.talla;
                        idDetalleSeleccionado = detalle.id_detalle_producto;
                        console.log('Seleccionado:', tallaSeleccionada);
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
            alert('Error al cargar el producto');
        });
}

// ============================================================
//  Carrito
// ============================================================
function agregarAlCarrito() {
    if (!productoActual) {
        alert('Error: No hay producto seleccionado');
        return;
    }
    if (!idDetalleSeleccionado) {
        alert('Por favor selecciona una talla');
        return;
    }

    const datos = {
        id_detalle_producto: idDetalleSeleccionado,
        talla:               tallaSeleccionada,
        cantidad:            1,
        precio:              productoActual.precio
    };

    console.log('📦 Enviando al carrito:', datos);

    fetch('/carrito/agregar', {
        method: 'POST',
        headers: {
            'Content-Type':  'application/json',
            'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify(datos)
    })
    .then(response => {
        console.log('📥 Respuesta recibida:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('📥 Datos:', data);
        if (data.redirect) {
            alert(data.mensaje);
            window.location.href = data.redirect;
            return;
        }
        if (data.success) {
            alert('✅ ' + data.mensaje);
            modal.hide();
            actualizarContadorCarrito();
        } else {
            alert('❌ ' + (data.mensaje || 'No se pudo agregar'));
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        alert('Error de conexión');
    });
}

function actualizarContadorCarrito() {
    fetch('/carrito/contador')
        .then(response => response.json())
        .then(data => {
            console.log('🔢 Contador:', data);
            const iconoCarrito = document.querySelector('.bi-bag');
            if (!iconoCarrito) return;

            const parent = iconoCarrito.parentElement;
            let badge    = parent.querySelector('.badge');

            if (data.cantidad > 0) {
                if (!badge) {
                    badge              = document.createElement('span');
                    badge.className    = 'badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle';
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

/**
 * Al cargar la página, consulta por cada producto si ya es favorito
 * y pinta los corazones que correspondan.
 * Solo corre si hay sesión activa (el botón existe en el DOM).
 */
function verificarFavoritosAlCargar() {
    const botones = document.querySelectorAll('.btn-favorito');
    if (!botones.length) return;

    botones.forEach(btn => {
        const idProducto = btn.getAttribute('data-id');

        fetch(`/favoritos/verificar/${idProducto}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => {
            // Si devuelve 401 el cliente no está logueado → silencioso
            if (res.status === 401) return null;
            return res.json();
        })
        .then(data => {
            if (data && data.esFavorito) {
                marcarComoFavorito(btn);
            }
        })
        .catch(() => { /* silencioso, no interrumpe la página */ });
    });
}

/**
 * Toggle favorito al hacer clic en el corazón.
 * - Si NO es favorito → lo agrega
 * - Si YA es favorito → lo quita  (redirige a favoritos para eliminarlo
 *   ya que necesitamos el id_favorito, no el id_producto)
 *
 * @param {number}      idProducto
 * @param {HTMLElement} btnEl
 */
function toggleFavorito(idProducto, btnEl) {
    // Evitar que el click propague al product-image y abra el modal
    event.stopPropagation();

    const esFavorito = btnEl.classList.contains('is-favorito');

    if (esFavorito) {
        // Redirige a la vista de favoritos para que el usuario lo quite
        window.location.href = '/favoritos';
        return;
    }

    // ── Agregar favorito ──────────────────────────────────────────
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
        if (res.status === 401) {
            // No logueado → redirigir al login
            window.location.href = '/login';
            return null;
        }
        return res.json();
    })
    .then(data => {
        if (!data) return;

        if (data.ok) {
            marcarComoFavorito(btnEl);
        } else {
            // Si ya existía como favorito, marcarlo igual visualmente
            if (data.mensaje && data.mensaje.toLowerCase().includes('ya está')) {
                marcarComoFavorito(btnEl);
            } else {
                alert(data.mensaje || 'No se pudo agregar a favoritos.');
                btnEl.disabled = false;
            }
        }
    })
    .catch(() => {
        alert('Error de conexión. Inténtalo de nuevo.');
        btnEl.disabled = false;
    });
}

/**
 * Aplica el estado visual de "favorito activo" al botón
 * y lanza la animación de pulso.
 *
 * @param {HTMLElement} btnEl
 */
function marcarComoFavorito(btnEl) {
    btnEl.classList.add('is-favorito');
    btnEl.querySelector('i').className = 'bi bi-heart-fill';
    btnEl.title    = 'Guardado en favoritos';
    btnEl.disabled = false;

    // Animación de pulso
    btnEl.classList.add('pop');
    btnEl.addEventListener('animationend', () => btnEl.classList.remove('pop'), { once: true });
}