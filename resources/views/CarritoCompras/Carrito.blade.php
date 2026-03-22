<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carrito de Compras - Urban Street</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rubik:wght@300;400;500;700&family=Permanent+Marker&display=swap" rel="stylesheet">
    <link href="{{ asset('css/CarritoCompras/Carrito.css') }}" rel="stylesheet">
</head>

<body class="bg-light">

        <nav class="navbar navbar-expand-lg fixed-top" id="mainNavbar">
            <div class="container-fluid px-3 py-2">

                <!-- Logo -->
                <a href="{{ url('/inicio') }}" class="navbar-brand logo-urbano mb-0">
                    URBAN STREET
                </a>

                <!-- Iconos móvil: perfil + carrito + favoritos -->
                <div class="d-flex align-items-center gap-2 d-lg-none ms-auto me-2">

                    <!-- Dropdown usuario móvil -->
                    <div class="icon-wrapper position-relative" id="dropdownUsuarioMobile">
                        <a href="#" class="text-dark" id="userDropdownToggleMobile" aria-expanded="false">
                            <i class="bi bi-person fs-5"></i>
                        </a>
                        <ul class="inicio-dropdown-menu" id="userDropdownMenuMobile">
                            @if(Session::has('token'))
                                <li><a class="inicio-dropdown-item" href="{{ url('cuenta') }}">Perfil</a></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="inicio-dropdown-item w-100 text-start border-0 bg-transparent">Cerrar sesión</button>
                                    </form>
                                </li>
                            @else
                                <li><a class="inicio-dropdown-item" href="{{ route('login') }}">Iniciar sesión</a></li>
                                <li><a class="inicio-dropdown-item" href="{{ route('registro') }}">Registrarse</a></li>
                            @endif
                        </ul>
                    </div>

                    <!-- Carrito -->
                    <a href="{{ url('/carrito') }}" class="text-dark icon-wrapper position-relative">
                        <i class="bi bi-bag fs-5"></i>
                    </a>

                    <!-- Favoritos -->
                    <a href="{{ route('favoritos') }}" class="text-dark icon-wrapper position-relative">
                        <i class="bi bi-heart fs-5"></i>
                    </a>
                </div>

                <!-- Hamburguesa -->
                <button class="navbar-toggler border-0 shadow-none" type="button"
                        data-bs-toggle="collapse" data-bs-target="#navbarMenu"
                        aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menú colapsable -->
                <div class="collapse navbar-collapse" id="navbarMenu">

                    <!-- Categorías centradas -->
                    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-lg-center flex-grow-1 gap-2 gap-lg-4 py-3 py-lg-0">
                        <a href="{{ url('/hombre') }}" class="nav-link-custom">HOMBRE</a>
                        <a href="{{ url('/mujer') }}" class="nav-link-custom">MUJER</a>
                        <a href="{{ url('/moda') }}" class="nav-link-custom">LO MEJOR DE LA MODA</a>
                    </div>

                    <!-- Iconos solo desktop -->
                    <div class="d-none d-lg-flex align-items-center gap-3">

                        <!-- Dropdown usuario desktop -->
                        <div class="icon-wrapper position-relative" id="dropdownUsuario">
                            <a href="#" class="text-dark" id="userDropdownToggle" aria-expanded="false">
                                <i class="bi bi-person fs-5"></i>
                            </a>
                            <ul class="inicio-dropdown-menu" id="userDropdownMenu">
                                @if(Session::has('token'))
                                    <li><a class="inicio-dropdown-item" href="{{ url('cuenta') }}">Perfil</a></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="inicio-dropdown-item w-100 text-start border-0 bg-transparent">Cerrar sesión</button>
                                        </form>
                                    </li>
                                @else
                                    <li><a class="inicio-dropdown-item" href="{{ route('login') }}">Iniciar sesión</a></li>
                                    <li><a class="inicio-dropdown-item" href="{{ route('registro') }}">Registrarse</a></li>
                                @endif
                            </ul>
                        </div>

                        <!-- Carrito -->
                        <a href="{{ url('/carrito') }}" class="text-dark icon-wrapper position-relative">
                            <i class="bi bi-bag fs-5"></i>
                        </a>

                        <!-- Favoritos -->
                        <a href="{{ route('favoritos') }}" class="text-dark icon-wrapper position-relative">
                            <i class="bi bi-heart fs-5"></i>
                        </a>

                    </div>
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

                        @foreach($carrito['items'] as $item)
                        <div class="card mb-3 item-carrito"
                             data-id-carrito="{{ $item['id_detalle_carrito'] }}"
                             data-stock="{{ $item['stock_disponible'] ?? 999 }}">

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
                                        <p class="card-text text-muted mb-2">Precio unitario: ${{ number_format($item['precio_unitario'], 2, ',', '.') }}</p>
                                        <p class="card-text text-muted mb-2">
                                            <small class="text-success">
                                                <i class="bi bi-check-circle"></i>
                                                {{ $item['stock_disponible'] ?? 0 }} disponibles
                                            </small>
                                        </p>

                                        <div class="d-flex align-items-center gap-2">
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
                                                    max="{{ $item['stock_disponible'] ?? 999 }}"
                                                    readonly>
                                                <button class="btn btn-outline-secondary btn-sm btn-incrementar"
                                                        data-id="{{ $item['id_detalle_carrito'] }}"
                                                        type="button">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>

                                            <button class="btn btn-sm btn-outline-danger btn-eliminar"
                                                    data-id="{{ $item['id_detalle_carrito'] }}">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 text-end pe-4">
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
                                <span class="fw-bold text-dark" id="total-resumen">
                                    ${{ number_format($carrito['total'] ?? 0, 2, ',', '.') }}
                                </span>
                            </p>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg">
                                Proceder al pago
                            </a>
                        </div>
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
                <p class="mb-0 small">© 2025 Urban Street - Proyecto Educativo</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/CarritoCompras/Carrito.js') }}"></script>
    

</body>
</html>