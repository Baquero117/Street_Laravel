// Variables globales
const modal = new bootstrap.Modal(document.getElementById('detalleModal'));
let tallaSeleccionada = null;
let productoActual = null;
let idDetalleSeleccionado = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Página cargada, buscando botones...');
    const botones = document.querySelectorAll('.ver-detalle-dinamico');
    
    botones.forEach((boton) => {
        boton.addEventListener('click', function() {
            const idProducto = this.getAttribute('data-id');
            verDetalle(idProducto);
        });
    });
    
    // 👇 Actualizar contador al cargar la página
    actualizarContadorCarrito();
});

function verDetalle(idProducto) {
    fetch(`/productos/${idProducto}/detalle`)
        .then(response => response.ok ? response.json() : Promise.reject('Error en red'))
        .then(data => {
            if (data.error) {
                alert('Error al cargar el detalle');
                return;
            }
            
            productoActual = data;
            tallaSeleccionada = null;
            idDetalleSeleccionado = null; 

            // Llenar datos básicos
            document.getElementById('modalNombre').textContent = data.nombre;
            document.getElementById('modalImagen').src = data.imagen ? '/storage/' + data.imagen : 'https://via.placeholder.com/400x400';
            document.getElementById('modalDescripcion').textContent = data.descripcion || 'Sin descripción';
            document.getElementById('modalColor').textContent = data.color || 'No especificado';
            document.getElementById('modalPrecio').textContent = new Intl.NumberFormat('es-CO').format(data.precio);
            
            // Renderizar Tallas
            const tallasContainer = document.getElementById('modalTallas');
            tallasContainer.innerHTML = '';
            
            if (data.detalles && data.detalles.length > 0) {
                data.detalles.forEach(detalle => {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-secondary me-2 mb-2';
                    badge.style.cursor = 'pointer';
                    badge.style.fontSize = '1rem';
                    badge.style.padding = '8px 15px';
                    badge.textContent = detalle.talla;

                    badge.onclick = function() {
                        document.querySelectorAll('#modalTallas .badge').forEach(b => {
                            b.classList.remove('bg-success');
                            b.classList.add('bg-secondary');
                        });
                        this.classList.remove('bg-secondary');
                        this.classList.add('bg-success');
                        
                        tallaSeleccionada = detalle.talla;
                        idDetalleSeleccionado = detalle.id_detalle_producto;
                        console.log("Talla:", tallaSeleccionada, "ID:", idDetalleSeleccionado);
                    };
                    tallasContainer.appendChild(badge);
                });
            } else {
                tallasContainer.innerHTML = '<span class="text-muted">Sin stock disponible</span>';
            }
            
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar el producto');
        });
}

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
        talla: tallaSeleccionada,
        cantidad: 1,
        precio: productoActual.precio
    };

    console.log('📦 Enviando al carrito:', datos);

    fetch('/carrito/agregar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
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

// 🔢 Actualizar contador del carrito
function actualizarContadorCarrito() {
    fetch('/carrito/contador')
        .then(response => response.json())
        .then(data => {
            console.log('🔢 Contador:', data);
            const iconoCarrito = document.querySelector('.bi-cart3');
            if (!iconoCarrito) return;
            
            const parent = iconoCarrito.parentElement;
            let badge = parent.querySelector('.badge');
            
            if (data.cantidad > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle';
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