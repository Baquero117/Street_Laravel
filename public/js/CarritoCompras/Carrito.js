document.addEventListener('DOMContentLoaded', () => {
    
    // ========== INCREMENTAR CANTIDAD ==========
    document.querySelectorAll('.btn-incrementar').forEach(btn => {
        btn.addEventListener('click', function() {
            const idCarrito = this.dataset.id;
            const input = document.querySelector(`.input-cantidad[data-id="${idCarrito}"]`);
            const item = document.querySelector(`.item-carrito[data-id-carrito="${idCarrito}"]`);
            const stockDisponible = parseInt(item.dataset.stock);
            const cantidadActual = parseInt(input.value);
            
            // ✅ Validar stock disponible ANTES de enviar
            if (cantidadActual >= stockDisponible) {
                mostrarNotificacion(`Solo hay ${stockDisponible} unidades disponibles`, 'warning');
                return;
            }
            
            const nuevaCantidad = cantidadActual + 1;
            actualizarCantidad(idCarrito, nuevaCantidad);
        });
    });

    // ========== DECREMENTAR CANTIDAD ==========
    document.querySelectorAll('.btn-decrementar').forEach(btn => {
        btn.addEventListener('click', function() {
            const idCarrito = this.dataset.id;
            const input = document.querySelector(`.input-cantidad[data-id="${idCarrito}"]`);
            const nuevaCantidad = Math.max(1, parseInt(input.value) - 1);
            
            if (nuevaCantidad >= 1) {
                actualizarCantidad(idCarrito, nuevaCantidad);
            }
        });
    });

    // ========== ELIMINAR ITEM ==========
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            const idCarrito = this.dataset.id;
            
            if (confirm('¿Estás seguro de eliminar este producto del carrito?')) {
                eliminarItem(idCarrito);
            }
        });
    });

    // ========== VACIAR CARRITO ==========
    const btnVaciar = document.getElementById('btn-vaciar-carrito');
    if (btnVaciar) {
        btnVaciar.addEventListener('click', function() {
            if (confirm('¿Estás seguro de vaciar todo el carrito?')) {
                vaciarCarrito();
            }
        });
    }
});

// ========== FUNCIÓN: ACTUALIZAR CANTIDAD ==========
function actualizarCantidad(idCarrito, cantidad) {
    fetch('/carrito/actualizar', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            id_carrito: idCarrito,
            cantidad: cantidad
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const input = document.querySelector(`.input-cantidad[data-id="${idCarrito}"]`);
            input.value = cantidad;
            
            const item = document.querySelector(`.item-carrito[data-id-carrito="${idCarrito}"]`);
            const precioTexto = item.querySelector('.card-text.text-muted.mb-2').textContent
                .replace('Precio unitario: $', '')
                .replace(/\./g, '')
                .replace(',', '.')
                .trim();
            
            const precioUnitario = parseFloat(precioTexto);
            const nuevoSubtotal = precioUnitario * cantidad;
            
            item.querySelector('.subtotal-item').textContent = 
                '$' + nuevoSubtotal.toLocaleString('es-CO', {
                    minimumFractionDigits: 2, 
                    maximumFractionDigits: 2
                });
            
            if (data.total !== undefined) {
                actualizarTotales(data.total);
            } else {
                recalcularTotales();
            }
            
            mostrarNotificacion('Cantidad actualizada', 'success');
        } else {
            // ✅ Mostrar mensaje específico de error de stock
            const mensaje = data.mensaje || 'Error al actualizar cantidad';
            mostrarNotificacion(mensaje, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al actualizar cantidad', 'error');
    });
}

// ========== FUNCIÓN: ACTUALIZAR TOTALES EN RESUMEN ==========
function actualizarTotales(total) {
    const totalFormateado = '$' + total.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    document.getElementById('subtotal-resumen').textContent = totalFormateado;
    document.getElementById('total-resumen').textContent = totalFormateado;
}

// ✅ NUEVA FUNCIÓN: Recalcular totales desde el DOM
function recalcularTotales() {
    let total = 0;
    document.querySelectorAll('.subtotal-item').forEach(subtotalEl => {
        const subtotal = parseFloat(
            subtotalEl.textContent
                .replace('$', '')
                .replace(/\./g, '')
                .replace(',', '.')
        );
        total += subtotal;
    });
    actualizarTotales(total);
}

// ========== FUNCIÓN: ELIMINAR ITEM ==========
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
                    if (data.total !== undefined) {
                        actualizarTotales(data.total);
                    }
                    if (data.cantidad_items !== undefined) {
                        actualizarContadorNavbar(data.cantidad_items);
                    }
                }
            }, 300);
            
            mostrarNotificacion('Producto eliminado del carrito', 'success');
        } else {
            mostrarNotificacion('Error al eliminar producto', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al eliminar producto', 'error');
    });
}

// ========== FUNCIÓN: VACIAR CARRITO ==========
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
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            mostrarNotificacion('Error al vaciar carrito', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al vaciar carrito', 'error');
    });
}

// ========== FUNCIÓN: ACTUALIZAR CONTADOR DEL NAVBAR ==========
function actualizarContadorNavbar(cantidad) {
    const iconoCarrito = document.querySelector('.bi-cart3').parentElement;
    let badge = iconoCarrito.querySelector('.badge');
    
    if (cantidad > 0) {
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle';
            badge.style.fontSize = '0.7rem';
            iconoCarrito.appendChild(badge);
        }
        badge.textContent = cantidad;
    } else if (badge) {
        badge.remove();
    }
}

// ========== FUNCIÓN: MOSTRAR NOTIFICACIÓN ==========
function mostrarNotificacion(mensaje, tipo) {
    const colores = {
        'success': 'bg-success',
        'error': 'bg-danger',
        'warning': 'bg-warning text-dark'
    };
    
    const color = colores[tipo] || 'bg-secondary';
    
    const toast = document.createElement('div');
    toast.className = `position-fixed top-0 end-0 m-3 p-3 ${color} text-white rounded`;
    toast.style.zIndex = '9999';
    toast.textContent = mensaje;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transition = 'opacity 0.3s';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}