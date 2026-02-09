// Variables globales integradas
const modalElement = document.getElementById('detalleModalMujer');
const modal = new bootstrap.Modal(modalElement);
let tallaSeleccionada = null;
let productoActual = null;
let idDetalleSeleccionado = null; // Para manejar el ID específico de la variante si existe

document.addEventListener('DOMContentLoaded', function() {
    console.log('Página de Mujer cargada');
    
    // Inicializar efectos visuales
    initScrollEffects();
    actualizarContadorCarrito();

    // Eventos para abrir el detalle (clic en imagen o botón "Ver más")
    const disparadores = document.querySelectorAll('.ver-detalle-mujer');
    
    disparadores.forEach((el) => {
        el.addEventListener('click', function() {
            const idProducto = this.getAttribute('data-id');
            verDetalleMujer(idProducto);
        });
        el.style.cursor = 'pointer';
    });
});

/**
 * Efecto de scroll para el navbar (estilo premium)
 */
function initScrollEffects() {
    const navbar = document.getElementById('mainNavbar');
    const brandLogo = document.getElementById('brandLogo');

    if (!navbar) return;

    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
            if (brandLogo) brandLogo.classList.add('fade-out');
        } else {
            navbar.classList.remove('scrolled');
            if (brandLogo) brandLogo.classList.remove('fade-out');
        }
    });
}

/**
 * Obtiene los datos del producto y llena el modal
 */
function verDetalleMujer(idProducto) {
    fetch(`/mujer/productos/${idProducto}/detalle`)
        .then(response => response.ok ? response.json() : Promise.reject('Error en red'))
        .then(data => {
            if (data.error) {
                alert('No se pudo cargar el detalle del producto');
                return;
            }
            
            productoActual = data;
            productoActual.id_producto = idProducto;
            tallaSeleccionada = null;
            idDetalleSeleccionado = null; 

            // Llenar campos del modal
            document.getElementById('modalNombreMujer').textContent = data.nombre;
            document.getElementById('modalImagenMujer').src = data.imagen ? '/storage/' + data.imagen : 'https://via.placeholder.com/400x400?text=Sin+Imagen';
            document.getElementById('modalDescripcionMujer').textContent = data.descripcion || 'Sin descripción disponible';
            document.getElementById('modalColorMujer').textContent = data.color || 'Multicolor';
            document.getElementById('modalPrecioMujer').textContent = new Intl.NumberFormat('es-CO').format(data.precio);
            
            // Renderizar tallas como cuadritos (talla-item)
            const tallasContainer = document.getElementById('modalTallasMujer');
            tallasContainer.innerHTML = '';
            
            // Soporta tanto array simple ['S','M'] como array de objetos de base de datos
            const detalles = data.detalles || data.tallas;

            if (detalles && detalles.length > 0) {
                detalles.forEach(item => {
                    const cajaTalla = document.createElement('div');
                    cajaTalla.className = 'talla-item'; // Clase definida en tu CSS
                    
                    const nombreTalla = item.talla || item;
                    const idVariante = item.id_detalle_producto || null;
                    
                    cajaTalla.textContent = nombreTalla;

                    cajaTalla.onclick = function() {
                        document.querySelectorAll('.talla-item').forEach(t => t.classList.remove('selected'));
                        this.classList.add('selected');
                        
                        tallaSeleccionada = nombreTalla;
                        idDetalleSeleccionado = idVariante;
                    };
                    tallasContainer.appendChild(cajaTalla);
                });
            } else {
                tallasContainer.innerHTML = '<span class="text-muted small">Talla única / Sin stock</span>';
            }
            
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al conectar con el servidor');
        });
}

/**
 * Envía el producto al carrito mediante POST
 */
function agregarAlCarritoMujer() {
    if (!productoActual) return;
    
    if (!tallaSeleccionada && (productoActual.tallas?.length > 0 || productoActual.detalles?.length > 0)) {
        alert('Por favor, selecciona una talla antes de añadir.');
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
            window.location.href = data.redirect; // Redirige al login si no está autenticado
            return;
        }

        if (data.success) {
            alert('✨ ' + data.mensaje);
            modal.hide();
            actualizarContadorCarrito();
        } else {
            alert('❌ ' + (data.mensaje || 'Error al agregar'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión al carrito');
    });
}

/**
 * Actualiza el número de productos en el icono del carrito
 */
function actualizarContadorCarrito() {
    fetch('/carrito/contador')
        .then(response => response.json())
        .then(data => {
            const iconoCarrito = document.querySelector('.bi-bag') || document.querySelector('.bi-cart3');
            if (!iconoCarrito) return;
            
            const parent = iconoCarrito.parentElement;
            let badge = parent.querySelector('.badge');
            
            if (data.cantidad > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle';
                    badge.style.fontSize = '0.65rem';
                    parent.style.position = 'relative';
                    parent.appendChild(badge);
                }
                badge.textContent = data.cantidad;
            } else if (badge) {
                badge.remove();
            }
        })
        .catch(() => console.log("Carrito sin items"));
}