// ========== VARIABLES GLOBALES ==========
let pasoActual = 1;
let idPedidoCreado = null;
let metodoPagoSeleccionado = null;
let carritoProductos = [];

// ========== INICIALIZACIÓN ==========
document.addEventListener('DOMContentLoaded', function() {
    cargarDatosCarrito();
    inicializarEventos();
});

// ========== CARGAR DATOS DEL CARRITO ==========
function cargarDatosCarrito() {
    // Obtener datos desde el elemento HTML (pasados desde Blade)
    const datosCarritoElement = document.getElementById('datos-carrito');
    
    if (datosCarritoElement) {
        const carritoData = datosCarritoElement.dataset.carrito;
        if (carritoData) {
            try {
                const carrito = JSON.parse(carritoData);
                
                // Verificar si tiene la estructura con 'items'
                if (carrito.items && Array.isArray(carrito.items)) {
                    carritoProductos = carrito.items;
                } else if (Array.isArray(carrito)) {
                    // Si es un array directo
                    carritoProductos = carrito;
                } else {
                    console.error('Estructura de carrito no reconocida:', carrito);
                    carritoProductos = [];
                }
                
                console.log('Productos cargados:', carritoProductos);
            } catch (e) {
                console.error('Error al parsear datos del carrito:', e);
                carritoProductos = [];
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
    cargarDatosCarrito();
    inicializarEventos();
    
    // 👇 DEBUG TEMPORAL - Ver estructura del carrito
    console.log('=== DEBUG CARRITO ===');
    console.log('Total de productos:', carritoProductos.length);
    console.log('Productos:', carritoProductos);
    console.log('Primer producto:', carritoProductos[0]);
    console.log('====================');
    });
    
    // Si no hay datos, el array queda vacío
    if (carritoProductos.length === 0) {
        console.warn('No se encontraron productos en el carrito');
    }
}

// ========== EVENTOS ==========
function inicializarEventos() {
    // Event listeners para los métodos de pago
    document.querySelectorAll('input[name="metodo_pago"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Remover clase selected de todas las tarjetas
            document.querySelectorAll('.payment-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Agregar clase selected a la tarjeta seleccionada
            this.closest('.payment-card').classList.add('selected');
            metodoPagoSeleccionado = this.value;
        });
    });

    // Event listener para el botón de descargar factura
    const btnDescargar = document.getElementById('btnDescargarFactura');
    if (btnDescargar) {
        btnDescargar.addEventListener('click', descargarFactura);
    }
}

// ========== NAVEGACIÓN ENTRE PASOS ==========
function irAPaso2() {
    // Validar que los datos estén completos
    const correo = document.getElementById('correo').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const direccion = document.getElementById('direccion').value.trim();

    if (!correo) {
        mostrarAlerta('Por favor actualiza tu correo electrónico en tu perfil', 'warning');
        return;
    }

    if (!telefono) {
        mostrarAlerta('Por favor actualiza tu número de teléfono en tu perfil', 'warning');
        return;
    }

    if (!direccion) {
        mostrarAlerta('Por favor actualiza tu dirección de envío en tu perfil', 'warning');
        return;
    }

    cambiarPaso(2);
}

function irAPaso3() {
    // Validar que se haya seleccionado un método de pago
    if (!metodoPagoSeleccionado) {
        mostrarAlerta('Por favor selecciona un método de pago', 'warning');
        return;
    }

    // Renderizar resumen del pedido
    renderizarResumen();
    cambiarPaso(3);
}

function volverAPaso1() {
    cambiarPaso(1);
}

function volverAPaso2() {
    cambiarPaso(2);
}

function cambiarPaso(numeroPaso) {
    // Ocultar todos los paneles
    document.querySelectorAll('.step-panel').forEach(panel => {
        panel.classList.remove('active');
    });

    // Mostrar el panel correspondiente
    document.getElementById('paso' + numeroPaso).classList.add('active');

    // Actualizar barra de progreso
    actualizarProgreso(numeroPaso);
    
    pasoActual = numeroPaso;

    // Scroll al top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function actualizarProgreso(paso) {
    // Actualizar círculos
    document.querySelectorAll('.progress-step').forEach((step, index) => {
        const stepNum = index + 1;
        step.classList.remove('active', 'completed');
        
        if (stepNum < paso) {
            step.classList.add('completed');
        } else if (stepNum === paso) {
            step.classList.add('active');
        }
    });

    // Actualizar líneas
    if (paso >= 2) {
        document.getElementById('line1').classList.add('active');
    } else {
        document.getElementById('line1').classList.remove('active');
    }

    if (paso >= 3) {
        document.getElementById('line2').classList.add('active');
    } else {
        document.getElementById('line2').classList.remove('active');
    }
}

// ========== RENDERIZAR RESUMEN ==========
function renderizarResumen() {
    const productosList = document.getElementById('productos-list');
    let html = '';
    let subtotal = 0;

    if (carritoProductos.length === 0) {
        html = '<p class="text-muted">No hay productos en el carrito</p>';
        productosList.innerHTML = html;
        return;
    }

    carritoProductos.forEach(producto => {
        // Manejar diferentes estructuras de datos
        const nombre = producto.nombre || producto.nombre_producto || 'Producto';
        const cantidad = producto.cantidad || 1;
        const precioUnitario = producto.precio_unitario || producto.precio || 0;
        const imagen = producto.imagen || 'https://via.placeholder.com/60';
        const talla = producto.talla || 'N/A';
        const color = producto.color || 'N/A';
        
        const precioTotal = precioUnitario * cantidad;
        subtotal += precioTotal;

        html += `
            <div class="product-item">
                <div class="product-image">
                    <img src="${getImagenUrl(imagen)}" 
                         alt="${nombre}"
                         onerror="this.src='https://via.placeholder.com/60'">
                </div>
                <div class="product-details">
                    <h5>${nombre}</h5>
                    <p>Talla: ${talla} | Color: ${color}</p>
                    <p>Cantidad: ${cantidad}</p>
                </div>
                <div class="product-price">
                    $${formatearPrecio(precioTotal)}
                </div>
            </div>
        `;
    });

    productosList.innerHTML = html;
    document.getElementById('subtotal').textContent = '$' + formatearPrecio(subtotal);
    document.getElementById('total').textContent = '$' + formatearPrecio(subtotal);
}

// Función auxiliar para obtener la URL correcta de la imagen
function getImagenUrl(imagen) {
    if (!imagen) {
        return 'https://via.placeholder.com/60';
    }
    
    // Si ya es una URL completa
    if (imagen.startsWith('http')) {
        return imagen;
    }
    
    // Si es una ruta de Laravel storage
    if (imagen.startsWith('storage/')) {
        return '/' + imagen;
    }
    
    // Si solo tiene el nombre del archivo
    return '/storage/' + imagen;
}

async function confirmarPedido() {
    const btnConfirmar = document.getElementById('btnConfirmar');
    btnConfirmar.disabled = true;
    btnConfirmar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Procesando...';

    // Calcular total
    const total = carritoProductos.reduce((sum, p) => {
        const precio = parseFloat(p.precio_unitario || p.precio || 0);
        const cantidad = parseInt(p.cantidad || 1);
        return sum + (precio * cantidad);
    }, 0);

    const idCliente = document.getElementById('usuario-id')?.value || null;
    const token = document.getElementById('usuario-token')?.value || '';

    if (!token) {
        mostrarAlerta('No se encontró el token de autenticación. Por favor inicia sesión nuevamente.', 'danger');
        btnConfirmar.disabled = false;
        btnConfirmar.innerHTML = '<i class="bi bi-check-circle"></i> Confirmar Pedido';
        return;
    }

    // 👇 PREPARAR ITEMS PARA LA FACTURA
    const items = carritoProductos.map(p => ({
        nombre: p.nombre || p.nombre_producto,
        cantidad: p.cantidad || 1,
        precio_unitario: parseFloat(p.precio_unitario || p.precio || 0),
        subtotal: parseFloat(p.subtotal || (p.precio_unitario * p.cantidad)),
        talla: p.talla || 'N/A',
        color: p.color || 'N/A',
        imagen: p.imagen || '' // 👈 IMPORTANTE: La ruta de la imagen
    }));

    // Preparar datos del pedido CON ITEMS
    const datosPedido = {
        id_cliente: parseInt(idCliente),
        fecha_pedido: new Date().toISOString().split('T')[0],
        total: total,
        estado: 'pendiente',
        metodo_pago: metodoPagoSeleccionado,
        numero_factura: generarNumeroFactura(),
        ruta_factura: '',
        items: items // 👈 AGREGAR ITEMS
    };

    const headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token
    };

    console.log('Datos del pedido:', datosPedido);

    try {
        const response = await fetch('http://localhost:8080/pedido', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(datosPedido)
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('❌ Error del servidor:', errorText);
            throw new Error('Error al crear el pedido: ' + response.status);
        }

        const resultado = await response.json();
        console.log('✅ Respuesta exitosa:', resultado);
        
        if (resultado.id_pedido) {
            idPedidoCreado = resultado.id_pedido;
        }

        // Mostrar modal de éxito
        const modal = new bootstrap.Modal(document.getElementById('modalExito'));
        modal.show();

        btnConfirmar.disabled = false;
        btnConfirmar.innerHTML = '<i class="bi bi-check-circle"></i> Confirmar Pedido';

    } catch (error) {
        console.error('❌ Error completo:', error);
        mostrarAlerta('Hubo un error al procesar el pedido. Intente nuevamente.', 'danger');
        btnConfirmar.disabled = false;
        btnConfirmar.innerHTML = '<i class="bi bi-check-circle"></i> Confirmar Pedido';
    }
}

async function descargarFactura() {
    const boton = document.getElementById('btnDescargarFactura');
    boton.disabled = true;
    boton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Descargando...';

    const token = document.getElementById('usuario-token')?.value || '';

    if (!token) {
        mostrarAlerta('No se encontró el token de autenticación.', 'danger');
        boton.disabled = false;
        boton.innerHTML = '<i class="bi bi-download"></i> Descargar Factura';
        return;
    }

    try {
        const response = await fetch(`http://localhost:8080/pedido/${idPedidoCreado}/factura`, {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });
        
        if (!response.ok) {
            throw new Error('Factura no encontrada');
        }

        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `factura_${idPedidoCreado}.pdf`;
        document.body.appendChild(a);
        a.click();

        setTimeout(() => {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }, 100);

        boton.disabled = false;
        boton.innerHTML = '<i class="bi bi-download"></i> Descargar Factura';

    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('No se pudo descargar la factura. Intente nuevamente.', 'danger');
        boton.disabled = false;
        boton.innerHTML = '<i class="bi bi-download"></i> Descargar Factura';
    }
}

// Ver factura en nueva ventana (funciona sin CORS)
async function verFacturaEnNavegador() {
    const token = document.getElementById('usuario-token')?.value || '';

    if (!token) {
        mostrarAlerta('No se encontró el token de autenticación.', 'danger');
        return;
    }

    if (!idPedidoCreado) {
        mostrarAlerta('No se encontró el ID del pedido.', 'danger');
        return;
    }

    const boton = document.getElementById('btnVerFactura');
    boton.disabled = true;
    boton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Abriendo...';

    try {
        // Descargar el PDF como blob
        const response = await fetch(`http://localhost:8080/pedido/${idPedidoCreado}/factura/ver`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        if (!response.ok) {
            throw new Error('Error al obtener la factura');
        }

        const blob = await response.blob();
        const urlBlob = window.URL.createObjectURL(blob);
        
        // Abrir en nueva ventana
        const ventana = window.open(urlBlob, '_blank');
        
        if (!ventana) {
            mostrarAlerta('Habilita los pop-ups en tu navegador', 'warning');
        }

        console.log('✅ Factura abierta');

        // Limpiar después de 5 segundos
        setTimeout(() => {
            window.URL.revokeObjectURL(urlBlob);
        }, 5000);

    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('No se pudo abrir la factura. Intenta descargarla.', 'danger');
    }

    boton.disabled = false;
    boton.innerHTML = '<i class="bi bi-eye"></i> Ver Factura';
}

// Opción 2: Descargar como blob y mostrar
async function descargarYMostrarFactura() {
    const token = document.getElementById('usuario-token')?.value || '';

    if (!token) {
        mostrarAlerta('No se encontró el token.', 'danger');
        return;
    }

    if (!idPedidoCreado) {
        mostrarAlerta('No se encontró el ID del pedido.', 'danger');
        return;
    }

    try {
        const response = await fetch(`http://localhost:8080/pedido/${idPedidoCreado}/factura/ver`, {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        if (!response.ok) {
            throw new Error('Error al obtener la factura');
        }

        const blob = await response.blob();
        const urlBlob = window.URL.createObjectURL(blob);
        
        // Abrir en nueva ventana
        window.open(urlBlob, '_blank');

        // Limpiar después
        setTimeout(() => {
            window.URL.revokeObjectURL(urlBlob);
        }, 5000);

    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('No se pudo abrir la factura. Intente descargarla.', 'danger');
    }
}

// ========== UTILIDADES ==========
function formatearPrecio(precio) {
    return precio.toLocaleString('es-CO');
}

function generarNumeroFactura() {
    const fecha = new Date();
    const año = fecha.getFullYear();
    const random = Math.floor(Math.random() * 10000);
    return `FAC-${año}-${random.toString().padStart(4, '0')}`;
}

async function obtenerUltimoPedido(token) {
    try {
        const response = await fetch('http://localhost:8080/pedido', {
            headers: {
                'Authorization': 'Bearer ' + token  // 👈 AGREGAR TOKEN
            }
        });
        const pedidos = await response.json();
        
        if (pedidos.length > 0) {
            return pedidos[pedidos.length - 1].id_pedido;
        }
    } catch (error) {
        console.error('Error al obtener último pedido:', error);
    }
    return null;
}

function mostrarAlerta(mensaje, tipo) {
    // Crear elemento de alerta
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
    alerta.style.zIndex = '9999';
    alerta.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alerta);

    // Auto-remover después de 3 segundos
    setTimeout(() => {
        alerta.remove();
    }, 3000);
}