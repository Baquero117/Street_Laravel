// ============================================================
//  DROPDOWN USUARIO
// ============================================================
function initDropdownUsuario() {
    const navbar = document.getElementById('mainNavbar');
    let abiertos = 0;

    function setupDropdown(toggleId, menuId) {
        const toggle = document.getElementById(toggleId);
        const menu   = document.getElementById(menuId);
        if (!toggle || !menu) return;

        let estaAbierto = false;

        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            estaAbierto ? cerrar() : abrir();
        });

        document.addEventListener('click', function(e) {
            if (estaAbierto && !toggle.contains(e.target) && !menu.contains(e.target)) cerrar();
        });

        function abrir() {
            if (estaAbierto) return;
            estaAbierto = true;
            abiertos++;
            menu.classList.add('show');
            toggle.setAttribute('aria-expanded', 'true');
        }

        function cerrar() {
            if (!estaAbierto) return;
            estaAbierto = false;
            abiertos = Math.max(0, abiertos - 1);
            menu.classList.remove('show');
            toggle.setAttribute('aria-expanded', 'false');
        }
    }

    setupDropdown('userDropdownToggle',       'userDropdownMenu');
    setupDropdown('userDropdownToggleMobile', 'userDropdownMenuMobile');
}

// ============================================================
//  MODAL DE CONFIRMACIÓN PERSONALIZADO
// ============================================================
function mostrarConfirmacion(mensaje, onAceptar) {
    const anterior = document.querySelector('.urban-modal-overlay');
    if (anterior) anterior.remove();

    const overlay = document.createElement('div');
    overlay.className = 'urban-modal-overlay';
    overlay.style.cssText = `
        position: fixed; inset: 0; z-index: 99999;
        background: rgba(0,0,0,0.6);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: opacity 0.25s ease;
        backdrop-filter: blur(4px);
    `;

    overlay.innerHTML = `
        <div class="urban-modal-box" style="
            background: #1a2332;
            border-radius: 14px;
            padding: 32px 28px 24px;
            max-width: 380px; width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            transform: translateY(20px);
            transition: transform 0.25s ease;
            border: 1px solid #2a3a4a;
            font-family: 'Segoe UI', sans-serif;
        ">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <i class="bi bi-exclamation-triangle-fill" style="color:#ffc107; font-size:1.4rem;"></i>
                <p style="color:#c8d6e5; font-size:0.95rem; margin:0; line-height:1.5;">${mensaje}</p>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <button class="urban-btn-cancelar" style="
                    background: transparent; color: #8a9ab0;
                    border: 1px solid #2a3a4a; border-radius: 8px;
                    padding: 9px 20px; cursor: pointer; font-size: 0.88rem;
                    transition: all 0.2s ease;
                ">Cancelar</button>
                <button class="urban-btn-aceptar" style="
                    background: #3b82f6; color: #fff;
                    border: none; border-radius: 8px;
                    padding: 9px 20px; cursor: pointer; font-size: 0.88rem;
                    transition: all 0.2s ease;
                ">Aceptar</button>
            </div>
        </div>
    `;

    document.body.appendChild(overlay);

    requestAnimationFrame(() => requestAnimationFrame(() => {
        overlay.style.opacity = '1';
        overlay.querySelector('.urban-modal-box').style.transform = 'translateY(0)';
    }));

    const cerrar = () => {
        overlay.style.opacity = '0';
        setTimeout(() => overlay.remove(), 250);
    };

    overlay.querySelector('.urban-btn-cancelar').addEventListener('click', cerrar);
    overlay.querySelector('.urban-btn-aceptar').addEventListener('click', () => {
        cerrar();
        onAceptar();
    });

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) cerrar();
    });
}

// ============================================================
//  TOAST PERSONALIZADO
// ============================================================
function mostrarNotificacion(mensaje, tipo) {
    const anterior = document.querySelector('.urban-toast');
    if (anterior) anterior.remove();

    const iconos  = { success: 'bi-check-circle-fill', error: 'bi-x-circle-fill', warning: 'bi-exclamation-triangle-fill' };
    const colores = { success: '#28a745', error: '#dc3545', warning: '#ffc107' };

    const toast = document.createElement('div');
    toast.className = 'urban-toast';
    toast.innerHTML = `
        <i class="bi ${iconos[tipo] || 'bi-info-circle-fill'}" style="font-size:1.1rem; color:${colores[tipo] || '#3b82f6'}; flex-shrink:0;"></i>
        <span>${mensaje}</span>
    `;
    toast.style.cssText = `
        position: fixed; bottom: 30px; right: 30px; z-index: 99999;
        display: flex; align-items: center; gap: 10px;
        background: #1a2332; color: #ffffff;
        padding: 14px 20px; border-radius: 10px;
        border-left: 4px solid ${colores[tipo] || '#3b82f6'};
        box-shadow: 0 8px 30px rgba(0,0,0,0.4);
        font-family: 'Segoe UI', sans-serif; font-size: 0.9rem;
        min-width: 260px; max-width: 360px;
        opacity: 0; transform: translateY(20px);
        transition: opacity 0.3s ease, transform 0.3s ease;
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
//  DOMContentLoaded
// ============================================================
document.addEventListener('DOMContentLoaded', () => {

    initDropdownUsuario();

    // ========== INCREMENTAR CANTIDAD ==========
    document.querySelectorAll('.btn-incrementar').forEach(btn => {
        btn.addEventListener('click', function() {
            const idCarrito = this.dataset.id;
            const input = document.querySelector(`.input-cantidad[data-id="${idCarrito}"]`);
            const item = document.querySelector(`.item-carrito[data-id-carrito="${idCarrito}"]`);
            const stockDisponible = parseInt(item.dataset.stock);
            const cantidadActual = parseInt(input.value);

            if (cantidadActual >= stockDisponible) {
                mostrarNotificacion(`Solo hay ${stockDisponible} unidades disponibles`, 'warning');
                return;
            }

            actualizarCantidad(idCarrito, cantidadActual + 1);
        });
    });

    // ========== DECREMENTAR CANTIDAD ==========
    document.querySelectorAll('.btn-decrementar').forEach(btn => {
        btn.addEventListener('click', function() {
            const idCarrito = this.dataset.id;
            const input = document.querySelector(`.input-cantidad[data-id="${idCarrito}"]`);
            const nuevaCantidad = Math.max(1, parseInt(input.value) - 1);
            if (nuevaCantidad >= 1) actualizarCantidad(idCarrito, nuevaCantidad);
        });
    });

    // ========== ELIMINAR ITEM ==========
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            const idCarrito = this.dataset.id;
            mostrarConfirmacion('¿Estás seguro de eliminar este producto del carrito?', () => {
                eliminarItem(idCarrito);
            });
        });
    });

    // ========== VACIAR CARRITO ==========
    const btnVaciar = document.getElementById('btn-vaciar-carrito');
    if (btnVaciar) {
        btnVaciar.addEventListener('click', function() {
            mostrarConfirmacion('¿Estás seguro de vaciar todo el carrito?', () => {
                vaciarCarrito();
            });
        });
    }
});

// ============================================================
//  ACTUALIZAR CANTIDAD
// ============================================================
function actualizarCantidad(idCarrito, cantidad) {
    fetch('/carrito/actualizar', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ id_carrito: idCarrito, cantidad: cantidad })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const input = document.querySelector(`.input-cantidad[data-id="${idCarrito}"]`);
            input.value = cantidad;

            const item = document.querySelector(`.item-carrito[data-id-carrito="${idCarrito}"]`);
            const precioTexto = item.querySelector('.card-text.text-muted.mb-2').textContent
                .replace('Precio unitario: $', '').replace(/\./g, '').replace(',', '.').trim();

            const precioUnitario = parseFloat(precioTexto);
            const nuevoSubtotal = precioUnitario * cantidad;

            item.querySelector('.subtotal-item').textContent =
                '$' + nuevoSubtotal.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            if (data.total !== undefined) {
                actualizarTotales(data.total);
            } else {
                recalcularTotales();
            }

            mostrarNotificacion('Cantidad actualizada', 'success');
        } else {
            mostrarNotificacion(data.mensaje || 'Error al actualizar cantidad', 'error');
        }
    })
    .catch(() => mostrarNotificacion('Error al actualizar cantidad', 'error'));
}

// ============================================================
//  ACTUALIZAR TOTALES
// ============================================================
function actualizarTotales(total) {
    const totalFormateado = '$' + total.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('subtotal-resumen').textContent = totalFormateado;
    document.getElementById('total-resumen').textContent = totalFormateado;
}

function recalcularTotales() {
    let total = 0;
    document.querySelectorAll('.subtotal-item').forEach(subtotalEl => {
        total += parseFloat(subtotalEl.textContent.replace('$', '').replace(/\./g, '').replace(',', '.'));
    });
    actualizarTotales(total);
}

// ============================================================
//  ELIMINAR ITEM
// ============================================================
function eliminarItem(idCarrito) {
    fetch(`/carrito/eliminar/${idCarrito}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`.item-carrito[data-id-carrito="${idCarrito}"]`);
            item.style.transition = 'opacity 0.3s';
            item.style.opacity = '0';

            setTimeout(() => {
                item.remove();
                const itemsRestantes = document.querySelectorAll('.item-carrito');
                if (itemsRestantes.length === 0) {
                    location.reload();
                } else {
                    if (data.total !== undefined) actualizarTotales(data.total);
                    if (data.cantidad_items !== undefined) actualizarContadorNavbar(data.cantidad_items);
                }
            }, 300);

            mostrarNotificacion('Producto eliminado del carrito', 'success');
        } else {
            mostrarNotificacion('Error al eliminar producto', 'error');
        }
    })
    .catch(() => mostrarNotificacion('Error al eliminar producto', 'error'));
}

// ============================================================
//  VACIAR CARRITO
// ============================================================
function vaciarCarrito() {
    fetch('/carrito/vaciar', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarNotificacion('Carrito vaciado', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarNotificacion('Error al vaciar carrito', 'error');
        }
    })
    .catch(() => mostrarNotificacion('Error al vaciar carrito', 'error'));
}

// ============================================================
//  ACTUALIZAR CONTADOR NAVBAR
// ============================================================
function actualizarContadorNavbar(cantidad) {
    const iconoCarrito = document.querySelector('.bi-bag')?.parentElement;
    if (!iconoCarrito) return;

    let badge = iconoCarrito.querySelector('.badge');

    if (cantidad > 0) {
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle';
            badge.style.fontSize = '0.7rem';
            iconoCarrito.style.position = 'relative';
            iconoCarrito.appendChild(badge);
        }
        badge.textContent = cantidad;
    } else if (badge) {
        badge.remove();
    }
}