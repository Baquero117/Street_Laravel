// Modal de Bootstrap
const modal = new bootstrap.Modal(document.getElementById('detalleModalMujer'));
let tallaSeleccionada = null;
let productoActual = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Página de Mujer cargada');
    
    const botones = document.querySelectorAll('.ver-detalle-mujer');
    console.log('Botones encontrados:', botones.length);
    
    botones.forEach((boton, index) => {
        console.log(`Botón ${index + 1} con ID:`, boton.getAttribute('data-id'));
        
        boton.addEventListener('click', function() {
            const idProducto = this.getAttribute('data-id');
            console.log('Click en botón con ID:', idProducto);
            verDetalleMujer(idProducto);
        });
    });
});

// Función para obtener y mostrar el detalle del producto
function verDetalleMujer(idProducto) {
    fetch(`/mujer/productos/${idProducto}/detalle`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                alert('Error al cargar el detalle del producto');
                return;
            }
            
            productoActual = data;
            productoActual.id_producto = idProducto;
            
            // Llenar el modal con los datos
            document.getElementById('modalNombreMujer').textContent = data.nombre;
            if (data.imagen) {
                document.getElementById('modalImagenMujer').src = '/storage/' + data.imagen;
            } else {
                document.getElementById('modalImagenMujer').src = 'https://via.placeholder.com/400x400?text=Sin+Imagen';
            }
            document.getElementById('modalDescripcionMujer').textContent = data.descripcion || 'Sin descripción';
            document.getElementById('modalColorMujer').textContent = data.color || 'No especificado';
            document.getElementById('modalPrecioMujer').textContent = 
                new Intl.NumberFormat('es-CO').format(data.precio);
            
            // Mostrar tallas
            const tallasContainer = document.getElementById('modalTallasMujer');
            tallasContainer.innerHTML = '';
            
            if (data.tallas && data.tallas.length > 0) {
                data.tallas.forEach(talla => {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-secondary me-2 mb-2';
                    badge.style.cursor = 'pointer';
                    badge.style.fontSize = '1rem';
                    badge.style.padding = '8px 15px';
                    badge.textContent = talla;
                    badge.onclick = function() {
                        document.querySelectorAll('#modalTallasMujer .badge').forEach(b => {
                            b.classList.remove('bg-success');
                            b.classList.add('bg-secondary');
                        });
                        this.classList.remove('bg-secondary');
                        this.classList.add('bg-success');
                        tallaSeleccionada = talla;
                    };
                    tallasContainer.appendChild(badge);
                });
            } else {
                tallasContainer.innerHTML = '<span class="text-muted">Talla única</span>';
            }
            
            tallaSeleccionada = null;
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar el detalle del producto');
        });
}

// Función para agregar al carrito
function agregarAlCarritoMujer() {
    if (!productoActual) return;
    
    if (productoActual.tallas && productoActual.tallas.length > 0 && !tallaSeleccionada) {
        alert('Por favor selecciona una talla');
        return;
    }
    
    console.log('Agregar al carrito:', {
        producto: productoActual,
        talla: tallaSeleccionada
    });
    
    alert('Producto agregado al carrito');
    modal.hide();
}