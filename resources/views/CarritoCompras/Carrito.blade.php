<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carrito de Compras - Urban Street</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/CarritoCompras/Carrito.css') }}" rel="stylesheet">
</head>

    <body class="bg-light">

        <nav class="navbar navbar-expand-lg navbar-light fixed-top my-0">
            <div class="container-fluid bg-white shadow-sm fixed-top py-2 d-flex align-items-center">

                <a href="{{ url('/inicio') }}" class="navbar-brand fw-bold logo-urbano px-5">
                        Urban Street
                    </a>

                    <div class="d-flex align-items-center gap-3 position-absolute end-0 me-3">
                        
                        <div class="dropdown">

                            <a href="#" class="text-dark fs-5 dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a class="dropdown-item" href="{{ url('cuenta') }}">
                                        Ver Perfil
                                    </a>
                                </li>

                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </li>

                            </ul>
                        </div>

                        <a href="{{ url('/carrito') }}" class="text-dark fs-5">
                            <i class="bi bi-cart3"></i>
                        </a>
                    </div>

            </div>
        </nav>

    <!-- MAIN -->
    <main>
        <div class="container mt-4 mb-0">
            <div class="row">

                <!-- Columna productos -->
                <div class="col-lg-8">
                    <h3 class="mb-4">Mi Carrito</h3>

    @if(isset($carrito['items']) && count($carrito['items']) > 0)
    <div id="items-carrito">


        <!-- En el loop de items -->
        @foreach($carrito['items'] as $item)
        <div class="card mb-3 item-carrito" data-id-carrito="{{ $item['id_detalle_carrito'] }}">
            
            <div class="row g-0 align-items-center">
                <div class="col-md-2 text-center">
                    <img src="{{ asset('storage/' . $item['imagen']) }}" 
                        class="img-fluid rounded p-2" 
                        alt="{{ $item['nombre'] }}"
                        onerror="this.src='https://via.placeholder.com/150x150?text=Sin+Imagen'"
                        style="max-height: 150px; object-fit: contain;">
                </div>

                <div class="col-md-7">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item['nombre'] }}</h5>
                        <p class="card-text text-muted mb-1">Color: {{ $item['color'] ?? 'No especificado' }}</p>
                        <p class="card-text text-muted mb-1">Talla: {{ $item['talla'] }}</p>
                        <!-- 👇 CAMBIADO: Mostrar con 2 decimales -->
                        <p class="card-text text-muted mb-2">Precio unitario: ${{ number_format($item['precio_unitario'], 2, ',', '.') }}</p>

                        <div class="d-flex align-items-center gap-2">
                            <!-- Cantidad -->
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary btn-sm btn-decrementar" 
                                        data-id="{{ $item['id_detalle_carrito'] }}"
                                        type="button">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" 
                                    class="form-control form-control-sm text-center input-cantidad" 
                                    data-id="{{ $item['id_detalle_carrito'] }}"
                                    value="{{ $item['cantidad'] }}" 
                                    min="1" 
                                    readonly>
                                <button class="btn btn-outline-secondary btn-sm btn-incrementar" 
                                        data-id="{{ $item['id_detalle_carrito'] }}"
                                        type="button">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>

                            <!-- Eliminar -->
                            <button class="btn btn-sm btn-outline-danger btn-eliminar" 
                                    data-id="{{ $item['id_detalle_carrito'] }}">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 text-end pe-4">
                    <!-- 👇 CAMBIADO: Mostrar subtotal con 2 decimales -->
                    <h5 class="text-dark subtotal-item">${{ number_format($item['subtotal'], 2, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        @endforeach

    </div>

                        <!-- Botón vaciar carrito -->
                        <div class="text-end mb-3">
                            <button class="btn btn-outline-danger" id="btn-vaciar-carrito">
                                <i class="bi bi-trash"></i> Vaciar Carrito
                            </button>
                        </div>

                    @else
                        <div class="alert alert-info text-center">
                            <i class="bi bi-cart-x fs-1 d-block mb-3"></i>
                            <h5>Tu carrito está vacío</h5>
                            <p>¡Agrega productos para empezar a comprar!</p>
                            <a href="{{ route('inicio') }}" class="btn btn-primary mt-2">
                                Ir a la tienda
                            </a>
                        </div>
                    @endif
                </div>

            <!-- Columna resumen -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card p-4 shadow-sm" id="resumen-carrito">
                    <h5 class="mb-3 border-bottom pb-2">Resumen del pedido</h5>

                    <p class="d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <!-- 👇 CAMBIADO: Mostrar con 2 decimales -->
                        <span class="fw-bold text-dark" id="subtotal-resumen">
                            ${{ number_format($carrito['total'] ?? 0, 2, ',', '.') }}
                        </span>
                    </p>

                    <p class="d-flex justify-content-between text-success">
                        <span>Envío:</span>
                        <span class="fw-bold">GRATIS</span>
                    </p>

                    <div class="border-top pt-3 mt-2">
                        <p class="d-flex justify-content-between fs-5">
                            <span class="fw-bold">Total:</span>
                            <!-- 👇 CAMBIADO: Mostrar con 2 decimales -->
                            <span class="fw-bold text-dark" id="total-resumen">
                                ${{ number_format($carrito['total'] ?? 0, 2, ',', '.') }}
                            </span>
                        </p>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-primary btn-lg" id="btnProcederPago">
                            Proceder al pago
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer-section container-fluid py-4 px-md-5 bg-black text-white">
        <div class="row text-center">
            
            <div class="col-md-4 mb-3">
                <h5 class="footer-title">Síguenos en</h5>
                <div class="d-flex justify-content-center gap-2">
                    <a class="social-icon-circleF"><i class="bi bi-facebook"></i></a>
                    <a class="social-icon-circleI"><i class="bi bi-instagram"></i></a>
                    <a class="social-icon-circleY"><i class="bi bi-youtube"></i></a>
                    <a class="social-icon-circleT"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <h5 class="footer-title">Acerca de Urban Street</h5>
                <ul class="list-unstyled footer-links">
                    <li><a>Aviso de Privacidad</a></li>
                    <li><a>Términos y condiciones</a></li>
                    <li><a>Formas de pago</a></li>
                </ul>
            </div>

            <div class="col-md-4 mb-3">
                <h5 class="footer-title">Información adicional</h5>
                <ul class="list-unstyled footer-links">
                    <li><a>Contáctanos</a></li>
                    <li><a>Registro</a></li>
                    <li><a>Soporte</a></li>
                </ul>
            </div>

            <div class="col-12 mt-3">
                <p class="mb-0 small">
                    © 2025 Urban Street - Proyecto Educativo
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/CarritoCompras/Carrito.js') }}"></script>

</body>
</html>