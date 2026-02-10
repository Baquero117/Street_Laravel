document.addEventListener('DOMContentLoaded', () => {
    
    // ========== INCREMENTAR CANTIDAD ==========
    document.querySelectorAll('.btn-incrementar').forEach(btn => {
        btn.addEventListener('click', function() {
            const idCarrito = this.dataset.id;
            const input = document.querySelector(`.input-cantidad[data-id="${idCarrito}"]`);
            const nuevaCantidad = parseInt(input.value) + 1;
            
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

    // ========== PROCEDER AL PAGO ==========
    const btnPago = document.getElementById('btnProcederPago');
    if (btnPago) {
        btnPago.addEventListener('click', () => {
            btnPago.disabled = true;
            btnPago.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Procesando...
            `;
            btnPago.classList.remove('btn-boton-pago');
            btnPago.classList.add('btn-secondary');
            
            setTimeout(() => {
                alert('¡Pedido confirmado! Redirigiendo al pago...');
                // Aquí puedes redirigir a la página de pago
                // window.location.href = '/pago';
            }, 2000);
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
            // Actualizar input
            const input = document.querySelector(`.input-cantidad[data-id="${idCarrito}"]`);
            input.value = cantidad;
            
            // Actualizar subtotal del item
            const item = document.querySelector(`.item-carrito[data-id-carrito="${idCarrito}"]`);
            const precioUnitario = parseFloat(
                item.querySelector('.card-text.text-muted.mb-2').textContent
                    .replace('Precio unitario: $', '')
                    .replace(/\./g, '')
                    .replace(',', '.')
            );
            
            const nuevoSubtotal = precioUnitario * cantidad;
            // 👇 CAMBIADO: Mostrar con 2 decimales
            item.querySelector('.subtotal-item').textContent = 
                '$' + nuevoSubtotal.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            // Actualizar totales
            if (data.total !== undefined) {
                actualizarTotales(data.total);
            } else {
                recalcularTotales();
            }
            
            mostrarNotificacion('Cantidad actualizada', 'success');
        } else {
            mostrarNotificacion('Error al actualizar cantidad', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al actualizar cantidad', 'error');
    });
}

// ========== FUNCIÓN: ACTUALIZAR TOTALES EN RESUMEN ==========
function actualizarTotales(total) {
    // 👇 CAMBIADO: Mostrar con 2 decimales
    const totalFormateado = '$' + total.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    document.getElementById('subtotal-resumen').textContent = totalFormateado;
    document.getElementById('total-resumen').textContent = totalFormateado;
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
            // Eliminar visualmente el item
            const item = document.querySelector(`.item-carrito[data-id-carrito="${idCarrito}"]`);
            item.style.transition = 'opacity 0.3s';
            item.style.opacity = '0';
            
            setTimeout(() => {
                item.remove();
                
                // Verificar si el carrito está vacío
                const itemsRestantes = document.querySelectorAll('.item-carrito');
                if (itemsRestantes.length === 0) {
                    location.reload(); // Recargar para mostrar mensaje de carrito vacío
                } else {
                    // Actualizar totales
                    if (data.total !== undefined) {
                        actualizarTotales(data.total);
                    }
                    // Actualizar contador del navbar
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
            // Actualizar input
            const input = document.querySelector(`.input-cantidad[data-id="${idCarrito}"]`);
            input.value = cantidad;
            
            // Actualizar subtotal del item
            const item = document.querySelector(`.item-carrito[data-id-carrito="${idCarrito}"]`);
            const precioTexto = item.querySelector('.card-text.text-muted.mb-2').textContent
                .replace('Precio unitario: $', '')
                .replace(/\./g, '')      // Quitar puntos
                .replace(',', '.')       // Cambiar coma por punto
                .trim();
            
            const precioUnitario = parseFloat(precioTexto);
            
            const nuevoSubtotal = precioUnitario * cantidad;
            
            // Actualizar el subtotal con formato correcto
            item.querySelector('.subtotal-item').textContent = 
                '$' + nuevoSubtotal.toLocaleString('es-CO', {
                    minimumFractionDigits: 2, 
                    maximumFractionDigits: 2
                });
            
            // Actualizar totales
            if (data.total !== undefined) {
                actualizarTotales(data.total);
            } else {
                recalcularTotales();
            }
            
            mostrarNotificacion('Cantidad actualizada', 'success');
        } else {
            mostrarNotificacion('Error al actualizar cantidad', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al actualizar cantidad', 'error');
    });
}

// ========== FUNCIÓN: ACTUALIZAR TOTALES EN RESUMEN ==========
function actualizarTotales(total) {
    // 👇 CAMBIADO: Mostrar con 2 decimales
    const totalFormateado = '$' + total.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    document.getElementById('subtotal-resumen').textContent = totalFormateado;
    document.getElementById('total-resumen').textContent = totalFormateado;
}

// ========== FUNCIÓN: ACTUALIZAR CONTADOR DEL NAVBAR ==========
function actualizarContadorNavbar(cantidad) {
    const iconoCarrito = document.querySelector('.bi-cart').parentElement;
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
    // Crear toast/notificación
    const color = tipo === 'success' ? 'bg-success' : 'bg-danger';
    
    const toast = document.createElement('div');
    toast.className = `position-fixed top-0 end-0 m-3 p-3 ${color} text-white rounded`;
    toast.style.zIndex = '9999';
    toast.textContent = mensaje;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transition = 'opacity 0.3s';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}