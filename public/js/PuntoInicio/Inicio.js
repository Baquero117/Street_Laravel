// Modal de Bootstrap
const modal = new bootstrap.Modal(document.getElementById('detalleModal'));
let tallaSeleccionada = null;
let productoActual = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Página cargada, buscando botones...');
    
    // Buscar todos los botones después de que la página cargue
    const botones = document.querySelectorAll('.ver-detalle-dinamico');
    console.log('Botones encontrados:', botones.length);
    
    botones.forEach((boton, index) => {
        console.log(`Botón ${index + 1} con ID:`, boton.getAttribute('data-id'));
        
        boton.addEventListener('click', function() {
            const idProducto = this.getAttribute('data-id');
            console.log('Click en botón con ID:', idProducto);
            verDetalle(idProducto);
        });
    });
});

// Función para obtener y mostrar el detalle del producto
function verDetalle(idProducto) {
    fetch(`/productos/${idProducto}/detalle`)
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
            document.getElementById('modalNombre').textContent = data.nombre;
            // Verificar si hay imagen antes de asignarla
            if (data.imagen) {
                document.getElementById('modalImagen').src = '/storage/' + data.imagen;
            } else {
                document.getElementById('modalImagen').src = 'https://via.placeholder.com/400x400?text=Sin+Imagen';
            }
            document.getElementById('modalDescripcion').textContent = data.descripcion || 'Sin descripción';
            document.getElementById('modalColor').textContent = data.color || 'No especificado';
            document.getElementById('modalPrecio').textContent = 
                new Intl.NumberFormat('es-CO').format(data.precio);
            
            // Mostrar tallas
            const tallasContainer = document.getElementById('modalTallas');
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
                        // Deseleccionar todas las tallas
                        document.querySelectorAll('#modalTallas .badge').forEach(b => {
                            b.classList.remove('bg-success');
                            b.classList.add('bg-secondary');
                        });
                        // Seleccionar esta talla
                        this.classList.remove('bg-secondary');
                        this.classList.add('bg-success');
                        tallaSeleccionada = talla;
                    };
                    tallasContainer.appendChild(badge);
                });
            } else {
                tallasContainer.innerHTML = '<span class="text-muted">Talla única</span>';
            }
            
            // Resetear talla seleccionada
            tallaSeleccionada = null;
            
            // Mostrar el modal
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar el detalle del producto');
        });
}

// Función para agregar al carrito
function agregarAlCarrito() {
    if (!productoActual) return;
    
    // Si hay tallas y no se seleccionó ninguna
    if (productoActual.tallas && productoActual.tallas.length > 0 && !tallaSeleccionada) {
        alert('Por favor selecciona una talla');
        return;
    }
    
    // Aquí implementarías la lógica del carrito
    console.log('Agregar al carrito:', {
        producto: productoActual,
        talla: tallaSeleccionada
    });
    
    alert('Producto agregado al carrito');
    modal.hide();
}