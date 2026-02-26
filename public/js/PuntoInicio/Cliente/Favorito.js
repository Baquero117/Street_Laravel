/**
 * Favorito.js — Urban Street
 * Maneja: quitar favoritos + modal de detalle de producto
 */

// ── Variables globales modal ─────────────────────────────────
let modalDetalle          = null;
let tallaSeleccionada     = null;
let productoActual        = null;
let idDetalleSeleccionado = null;

document.addEventListener('DOMContentLoaded', () => {

    // Inicializar modal Bootstrap
    modalDetalle = new bootstrap.Modal(document.getElementById('detalleModal'));

    const csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    // ════════════════════════════════════════════════════════════════
    // QUITAR FAVORITO
    // ════════════════════════════════════════════════════════════════
    document.querySelectorAll('.btn-quitar-favorito').forEach(btn => {
        btn.addEventListener('click', function () {
            const idFavorito = this.dataset.id;
            const card       = document.getElementById(`card-favorito-${idFavorito}`);

            if (!confirm('¿Deseas quitar este producto de tus favoritos?')) return;

            this.disabled  = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            fetch(`/favoritos/${idFavorito}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken(),
                    'Accept':       'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    card.style.transition = 'opacity 0.3s, transform 0.3s';
                    card.style.opacity    = '0';
                    card.style.transform  = 'translateX(20px)';
                    setTimeout(() => {
                        card.remove();
                        actualizarContador();
                    }, 300);
                } else {
                    alert('Error al quitar favorito: ' + data.mensaje);
                    this.disabled  = false;
                    this.innerHTML = '<i class="bi bi-heart-fill me-1"></i> Quitar';
                }
            })
            .catch(() => {
                alert('Error de conexión. Inténtalo de nuevo.');
                this.disabled  = false;
                this.innerHTML = '<i class="bi bi-heart-fill me-1"></i> Quitar';
            });
        });
    });

    // ── Contador ─────────────────────────────────────────────────
    function actualizarContador() {
        const listaFavoritos = document.getElementById('lista-favoritos');
        const contadorEl     = document.querySelector('.favoritos-contador');

        if (!listaFavoritos) return;

        const total = listaFavoritos.querySelectorAll('.favorito-card').length;

        if (contadorEl) {
            contadorEl.innerHTML = `<i class="bi bi-heart-fill text-danger me-1"></i>
                ${total} ${total === 1 ? 'producto guardado' : 'productos guardados'}`;
        }

        if (total === 0) {
            listaFavoritos.innerHTML = `
                <div class="formulario-contenedor text-center py-5">
                    <i class="bi bi-heart" style="font-size: 3.5rem; color: #ccc;"></i>
                    <h5 class="mt-4 fw-semibold">Aún no tienes favoritos</h5>
                    <p class="text-muted" style="font-size: 14px;">
                        Cuando marques un producto con ♥, aparecerá aquí.
                    </p>
                    <a href="/inicio" class="btn btn-guardar mt-2">Ir a la tienda</a>
                </div>`;
            if (contadorEl) contadorEl.remove();
        }
    }

});

// ════════════════════════════════════════════════════════════════
// MODAL DETALLE — igual que Inicio.js
// ════════════════════════════════════════════════════════════════

function verDetalleFavorito(idProducto) {
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
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar el producto');
        });
}

// ── Agregar al carrito desde el modal ────────────────────────
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

    fetch('/carrito/agregar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.redirect) {
            alert(data.mensaje);
            window.location.href = data.redirect;
            return;
        }
        if (data.success) {
            alert('✅ ' + data.mensaje);
            modalDetalle.hide();
        } else {
            alert('❌ ' + (data.mensaje || 'No se pudo agregar'));
        }
    })
    .catch(() => alert('Error de conexión'));
}