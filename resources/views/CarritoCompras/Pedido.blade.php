<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - PULL&BEAR</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="{{ asset('css/CarritoCompras/Pedido.css') }}">
</head>
<body>

    <!-- Datos del usuario para JavaScript -->
    <input type="hidden" id="usuario-id" value="{{ $usuario->id ?? '' }}">
    <input type="hidden" id="usuario-token" value="{{ session('token') }}">
    
    <!-- Datos del carrito en formato JSON para JavaScript -->
    <div id="datos-carrito" 
         data-carrito='@json($carrito)' 
         style="display: none;">
    </div>
    
    <div class="checkout-container">


    <div class="checkout-container">
        <!-- Datos del carrito en formato JSON para JavaScript -->
        <div id="datos-carrito" 
            data-carrito='@json($carrito)' 
            style="display: none;">
        </div>
    
    <div class="checkout-container">
        <header class="checkout-header">
            <button class="btn-back" onclick="window.history.back()">
                <i class="bi bi-arrow-left"></i>
            </button>
            <h1 class="logo">URBAN STREET</h1>
        </header>

        <!-- Progress Bar -->
        <div class="progress-bar-container">
            <div class="progress-step active" data-step="1">
                <div class="step-circle">1</div>
                <span class="step-label">Datos</span>
            </div>
            <div class="progress-line" id="line1"></div>
            <div class="progress-step" data-step="2">
                <div class="step-circle">2</div>
                <span class="step-label">Envío</span>
            </div>
            <div class="progress-line" id="line2"></div>
            <div class="progress-step" data-step="3">
                <div class="step-circle">3</div>
                <span class="step-label">Pago</span>
            </div>
        </div>

        <!-- Contenido de los pasos -->
        <div class="steps-content">
            
            <!-- PASO 1: Datos del usuario -->
            <div class="step-panel active" id="paso1">
                <div class="step-header">
                    <h2>Verifica tus datos de contacto</h2>
                    <p class="text-muted">Asegúrate de que tu información esté correcta</p>
                </div>

                <div class="data-card">
                    <div class="data-item">
                        <i class="bi bi-person"></i>
                        <div class="data-content">
                            <label>Nombre completo</label>
                            <input type="text" class="form-control" id="nombre" 
                                value="{{ $usuario->nombre ?? '' }} {{ $usuario->apellido ?? '' }}" 
                                readonly>
                        </div>
                    </div>

                    <div class="data-item">
                        <i class="bi bi-envelope"></i>
                        <div class="data-content">
                            <label>Correo electrónico</label>
                            <input type="email" class="form-control" id="correo" 
                                value="{{ $usuario->email ?? '' }}" 
                                readonly>
                        </div>
                    </div>

                    <div class="data-item">
                        <i class="bi bi-telephone"></i>
                        <div class="data-content">
                            <label>Número de teléfono</label>
                            <input type="tel" class="form-control" id="telefono" 
                                value="{{ $usuario->telefono ?? '' }}" 
                                readonly>
                        </div>
                    </div>

                    <div class="data-item">
                        <i class="bi bi-geo-alt"></i>
                        <div class="data-content">
                            <label>Dirección de envío</label>
                            <input type="text" class="form-control" id="direccion" 
                                value="{{ $usuario->direccion ?? '' }}" 
                                readonly>
                        </div>
                    </div>
                </div>

                @if(empty($usuario->telefono) || empty($usuario->direccion))
                    <div class="alert alert-warning d-flex gap-2 align-items-center">
                        <i class="bi bi-exclamation-triangle"></i>
                        <div>
                            <strong>Información incompleta</strong>
                            <p class="mb-0">Por favor actualiza tu teléfono y dirección en tu perfil antes de continuar.</p>
                            <a href="{{ route('perfil') }}" class="btn btn-sm btn-warning mt-2">
                                <i class="bi bi-pencil"></i> Ir a mi perfil
                            </a>
                        </div>
                    </div>
                @else
                    <div class="info-box">
                        <i class="bi bi-info-circle"></i>
                        <p>¿Los datos están correctos? Si necesitas modificarlos, ve a tu 
                            <a href="{{ route('perfil') }}" class="fw-bold">perfil</a>.
                        </p>
                    </div>

                    <button class="btn-primary-custom" onclick="irAPaso2()">
                        Siguiente
                    </button>
                @endif
            </div>

            <!-- PASO 2: Método de pago -->
            <div class="step-panel" id="paso2">
                <div class="step-header">
                    <h2>Selecciona tu método de pago</h2>
                    <p class="text-muted">Elige cómo deseas pagar tu pedido</p>
                </div>

                <div class="payment-methods">
                    <label class="payment-card">
                        <input type="radio" name="metodo_pago" value="nequi" class="d-none">
                        <div class="payment-content">
                            <div class="payment-image">
                                <img src="{{ asset('img/nequi.png') }}" alt="Nequí">
                            </div>
                            <div class="payment-info">
                                <h4>Pagar con Nequí</h4>
                                <p>Transferencia instantánea</p>
                            </div>
                            <i class="bi bi-check-circle-fill check-icon"></i>
                        </div>
                    </label>

                    <label class="payment-card">
                        <input type="radio" name="metodo_pago" value="efectivo" class="d-none">
                        <div class="payment-content">
                            <div class="payment-image">
                                <img src="{{ asset('img/efectivo.jpg') }}" alt="Efectivo">
                            </div>
                            <div class="payment-info">
                                <h4>Pagar en Efectivo</h4>
                                <p>Pago contra entrega</p>
                            </div>
                            <i class="bi bi-check-circle-fill check-icon"></i>
                        </div>
                    </label>
                </div>

                <div class="alert-custom">
                    <i class="bi bi-exclamation-circle"></i>
                    <p>Por el momento no contamos con pago en línea, así que se le cobrará al momento de entregarle el pedido</p>
                </div>

                <div class="button-group">
                    <button class="btn-secondary-custom" onclick="volverAPaso1()">
                        <i class="bi bi-arrow-left"></i> Atrás
                    </button>
                    <button class="btn-primary-custom" onclick="irAPaso3()">
                        Continuar
                    </button>
                </div>
            </div>

            <!-- PASO 3: Confirmación -->
            <div class="step-panel" id="paso3">
                <div class="step-header">
                    <h2>Resumen de tu pedido</h2>
                    <p class="text-muted">Revisa los detalles antes de confirmar</p>
                </div>

                <div class="order-summary">
                    <h4>Productos</h4>
                    <div id="productos-list">
                        <!-- Se llenará dinámicamente con JS -->
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="subtotal">$0</span>
                    </div>
                    <div class="summary-row">
                        <span>Envío</span>
                        <span id="envio">Gratis</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span id="total">$0</span>
                    </div>
                </div>

                <div class="delivery-info">
                    <i class="bi bi-truck"></i>
                    <div>
                        <strong>Tiempo de entrega estimado</strong>
                        <p>3-5 días hábiles</p>
                    </div>
                </div>

                <div class="button-group">
                    <button class="btn-secondary-custom" onclick="volverAPaso2()">
                        <i class="bi bi-arrow-left"></i> Atrás
                    </button>
                    <button class="btn-primary-custom" onclick="confirmarPedido()" id="btnConfirmar">
                        <i class="bi bi-check-circle"></i> Confirmar Pedido
                    </button>
                </div>
            </div>
        </div>
    </div>

<!-- Modal de éxito -->
<div class="modal fade" id="modalExito" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <div class="success-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h3 class="mt-4 mb-3">¡Pedido tomado con éxito!</h3>
                <p class="text-muted mb-4">Estaremos informándole sobre su pedido</p>
                
                <!-- 👇 BOTÓN PARA VER EN NAVEGADOR -->
                <button class="btn-download" id="btnVerFactura" onclick="descargarYMostrarFactura()">
                    <i class="bi bi-eye"></i> Ver Factura
                </button>
                
                <!-- BOTÓN PARA DESCARGAR -->
                <button class="btn-download" id="btnDescargarFactura">
                    <i class="bi bi-download"></i> Descargar Factura
                </button>

                <button class="btn-close-modal mt-3" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JS Personalizado -->
    <script src="{{ asset('js/CarritoCompras/Pedido.js') }}"></script>
</body>
</html>