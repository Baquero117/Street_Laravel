// Variables globales corregidas para tu HTML
const modalElement = document.getElementById('detalleModal');
const modal = new bootstrap.Modal(modalElement);
let tallaSeleccionada = null;
let productoActual = null;
let idDetalleSeleccionado = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Página de Hombre cargada, buscando imágenes...');
    
    // En tu Blade, las imágenes tienen la clase 'product-image'
    const imagenes = document.querySelectorAll('.product-image');
    
    imagenes.forEach((imagen) => {
        imagen.addEventListener('click', function() {
            const idProducto = this.getAttribute('data-id');
            verDetalle(idProducto);
        });
        imagen.style.cursor = 'pointer';
    });
    
    // Funciones iniciales
    actualizarContadorCarrito();
    initScrollEffects();
});

// Efecto de scroll para el navbar (Logo desaparece, navbar cambia)
function initScrollEffects() {
    const brandLogo = document.getElementById('brandLogo');
    const navbar = document.getElementById('mainNavbar');

    if (!brandLogo || !navbar) return;

    window.addEventListener('scroll', function() {
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

// Función principal para ver el detalle (Ruta corregida para Hombre)
function verDetalle(idProducto) {
    // Usamos la ruta que tenías en el controlador de hombre
    fetch(`/hombre/productos/${idProducto}/detalle`)
        .then(response => response.ok ? response.json() : Promise.reject('Error en red'))
        .then(data => {
            if (data.error) {
                alert('Error al cargar el detalle');
                return;
            }
            
            productoActual = data;
            productoActual.id_producto = idProducto; // Aseguramos el ID
            tallaSeleccionada = null;
            idDetalleSeleccionado = null; 

            // Llenamos el modal usando los IDs exactos de tu Blade
            document.getElementById('modalNombre').textContent = data.nombre;
            document.getElementById('modalImagen').src = data.imagen ? '/storage/' + data.imagen : 'https://via.placeholder.com/400x400';
            document.getElementById('modalDescripcion').textContent = data.descripcion || 'Sin descripción';
            document.getElementById('modalColor').textContent = data.color || 'No especificado';
            
            // Formateo de precio
            document.getElementById('modalPrecio').textContent = new Intl.NumberFormat('es-CO').format(data.precio);
            
            const tallasContainer = document.getElementById('modalTallas');
            tallasContainer.innerHTML = '';

            // Manejo de tallas (Soporta el formato de 'detalles' con ID)
            const detalles = data.detalles || data.tallas;

            if (detalles && detalles.length > 0) {
                detalles.forEach(item => {
                    const cajaTalla = document.createElement('div');
                    // Usamos la clase 'talla-item' para el estilo de cuadrito
                    cajaTalla.className = 'talla-item'; 
                    
                    const nombreTalla = item.talla || item;
                    const idVariante = item.id_detalle_producto || null;
                    
                    cajaTalla.textContent = nombreTalla;

                    cajaTalla.onclick = function() {
                        document.querySelectorAll('.talla-item').forEach(t => t.classList.remove('selected'));
                        this.classList.add('selected');
                        
                        tallaSeleccionada = nombreTalla;
                        idDetalleSeleccionado = idVariante;
                        console.log("Seleccionado:", tallaSeleccionada, "ID Detalle:", idDetalleSeleccionado);
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

// Agregar al carrito con TOKEN CSRF
function agregarAlCarrito() {
    if (!productoActual) return;
    
    if (!tallaSeleccionada) {
        alert('Por favor selecciona una talla');
        return;
    }
    
    const datos = {
        id_producto: productoActual.id_producto,
        id_detalle_producto: idDetalleSeleccionado,
        talla: tallaSeleccionada,
        cantidad: 1,
        precio: productoActual.precio
    };

    fetch('/carrito/agregar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.redirect) {
            window.location.href = data.redirect;
            return;
        }

        if (data.success) {
            alert('✅ ' + data.mensaje);
            modal.hide();
            actualizarContadorCarrito();
        } else {
            alert('❌ ' + (data.mensaje || 'Error al agregar'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión');
    });
}

// Contador del carrito dinámico
function actualizarContadorCarrito() {
    fetch('/carrito/contador')
        .then(response => response.json())
        .then(data => {
            const iconoCarrito = document.querySelector('.bi-bag');
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
        .catch(() => console.log("Carrito vacío o error"));
}