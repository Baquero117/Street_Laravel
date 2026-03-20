<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Pedidos - Urban Street</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rubik:wght@300;400;500;700&family=Permanent+Marker&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/PuntoInicio/Cliente/Pedidos.css') }}">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNavbar">
        <div class="container-fluid px-3 py-2">

            <!-- Logo -->
            <a href="{{ url('/inicio') }}" class="navbar-brand logo-urbano mb-0">
                URBAN STREET
            </a>

            <!-- Iconos móvil: perfil → carrito -->
            <div class="d-flex align-items-center gap-2 d-lg-none ms-auto me-2">

                <div class="icon-wrapper position-relative" id="dropdownUsuarioMobile">
                    <a href="#" class="text-dark" id="userDropdownToggleMobile" aria-expanded="false">
                        <i class="bi bi-person fs-5"></i>
                    </a>
                    <ul class="pedidos-dropdown-menu" id="userDropdownMenuMobile">
                        @if(Session::has('token'))
                            <li><a class="pedidos-dropdown-item" href="{{ url('cuenta') }}">Perfil</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="pedidos-dropdown-item w-100 text-start border-0 bg-transparent">Cerrar sesión</button>
                                </form>
                            </li>
                        @else
                            <li><a class="pedidos-dropdown-item" href="{{ route('login') }}">Iniciar sesión</a></li>
                            <li><a class="pedidos-dropdown-item" href="{{ route('registro') }}">Registrarse</a></li>
                        @endif
                    </ul>
                </div>

                <a href="{{ url('/carrito') }}" class="text-dark icon-wrapper position-relative">
                    <i class="bi bi-bag fs-5"></i>
                </a>
            </div>

            <!-- Toggler hamburguesa -->
            <button class="navbar-toggler border-0 shadow-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarPedidosMenu"
                aria-controls="navbarPedidosMenu" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú colapsable -->
            <div class="collapse navbar-collapse" id="navbarPedidosMenu">

                <!-- Links centrados -->
                <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-lg-center flex-grow-1 gap-2 gap-lg-4 py-3 py-lg-0">
                    <a href="{{ url('/hombre') }}" class="nav-link-custom">HOMBRE</a>
                    <a href="{{ url('/mujer') }}" class="nav-link-custom">MUJER</a>
                    <a href="{{ url('/moda') }}" class="nav-link-custom">LO MEJOR DE LA MODA</a>
                </div>

                <!-- Iconos desktop: perfil → carrito -->
                <div class="d-none d-lg-flex align-items-center gap-3">

                    <div class="icon-wrapper position-relative" id="dropdownUsuario">
                        <a href="#" class="text-dark" id="userDropdownToggle" aria-expanded="false">
                            <i class="bi bi-person fs-5"></i>
                        </a>
                        <ul class="pedidos-dropdown-menu" id="userDropdownMenu">
                            @if(Session::has('token'))
                                <li><a class="pedidos-dropdown-item" href="{{ url('cuenta') }}">Perfil</a></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="pedidos-dropdown-item w-100 text-start border-0 bg-transparent">Cerrar sesión</button>
                                    </form>
                                </li>
                            @else
                                <li><a class="pedidos-dropdown-item" href="{{ route('login') }}">Iniciar sesión</a></li>
                                <li><a class="pedidos-dropdown-item" href="{{ route('registro') }}">Registrarse</a></li>
                            @endif
                        </ul>
                    </div>

                    <a href="{{ url('/carrito') }}" class="text-dark icon-wrapper position-relative">
                        <i class="bi bi-bag fs-5"></i>
                    </a>

                </div>
            </div>

        </div>
    </nav>

    <!-- CONTENIDO -->
    <div class="container pedidos-container mt-5 pt-5">
        <div class="row">

            <!-- Sidebar izquierda -->
            <div class="col-md-6 col-lg-5">
                <h2 class="pedidos-titulo">Mis Pedidos</h2>

                <a href="{{ route('perfil') }}" class="text-decoration-none text-dark">
                    <div class="cuenta-card">
                        <div class="cuenta-card-title">Perfil</div>
                        <div class="cuenta-card-sub">Mi información personal</div>
                    </div>
                </a>

                <a href="{{ route('mis-pedidos') }}" class="text-decoration-none text-dark">
                    <div class="cuenta-card active">
                        <div class="cuenta-card-title">Pedidos</div>
                        <div class="cuenta-card-sub">Historial de pedidos</div>
                    </div>
                </a>

                <a href="{{ route('favoritos') }}" class="text-decoration-none text-dark">
                    <div class="cuenta-card">
                        <div class="cuenta-card-title">Favoritos</div>
                        <div class="cuenta-card-sub">Productos que me gustan</div>
                    </div>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="border-0 bg-transparent p-0 w-100 text-start">
                        <div class="cuenta-card">
                            <div class="cuenta-card-title">Sesión</div>
                            <div class="cuenta-card-sub">Cierre de sesión</div>
                        </div>
                    </button>
                </form>
            </div>

            <!-- Contenido derecha -->
            <div class="col-md-6 col-lg-7">

                @if(empty($pedidos))
                    <div class="formulario-contenedor text-center py-5">
                        <i class="bi bi-bag-x" style="font-size: 3.5rem; color: #ccc;"></i>
                        <h5 class="mt-4 fw-semibold">Aún no tienes pedidos</h5>
                        <p class="text-muted" style="font-size: 14px;">Cuando realices una compra, aparecerá aquí.</p>
                        <a href="{{ url('/inicio') }}" class="btn btn-guardar mt-2">Ir a la tienda</a>
                    </div>

                @else
                    <div class="d-flex flex-column gap-3" id="lista-pedidos">
                        @foreach($pedidos as $index => $pedido)
                        <div class="pedido-card {{ $index >= 4 ? 'pedido-oculto' : '' }}"
                             data-index="{{ $index }}">

                            <div class="pedido-card-header">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-receipt"></i>
                                    <span class="pedido-numero">{{ $pedido['numero_factura'] ?? 'N/A' }}</span>
                                </div>
                                <span class="estado-badge estado-{{ strtolower($pedido['estado'] ?? 'pendiente') }}">
                                    {{ $pedido['estado'] ?? 'Pendiente' }}
                                </span>
                            </div>

                            <div class="pedido-card-body">
                                <div class="pedido-dato">
                                    <i class="bi bi-calendar3 pedido-icono"></i>
                                    <div>
                                        <small class="dato-label">Fecha</small>
                                        <p class="dato-valor mb-0">{{ $pedido['fecha_pedido'] ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="pedido-dato">
                                    <i class="bi bi-cash-stack pedido-icono"></i>
                                    <div>
                                        <small class="dato-label">Total</small>
                                        <p class="dato-valor mb-0 fw-semibold">
                                            ${{ number_format($pedido['total'] ?? 0, 3, '.', ',') }} COP
                                        </p>
                                    </div>
                                </div>
                                <div class="pedido-dato">
                                    <i class="bi bi-credit-card pedido-icono"></i>
                                    <div>
                                        <small class="dato-label">Método de pago</small>
                                        <p class="dato-valor mb-0">{{ $pedido['metodo_pago'] ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="pedido-card-footer">
                                @if(!empty($pedido['ruta_factura']))
                                    <a href="{{ route('mis-pedidos.factura.ver', $pedido['id_pedido']) }}"
                                        target="_blank" class="btn btn-ver-factura">
                                        <i class="bi bi-eye me-1"></i> Ver factura
                                    </a>
                                    <a href="{{ route('mis-pedidos.factura.descargar', $pedido['id_pedido']) }}"
                                        class="btn btn-guardar">
                                        <i class="bi bi-download me-1"></i> Descargar
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size: 13px;">
                                        <i class="bi bi-clock me-1"></i> Factura no disponible
                                    </span>
                                @endif
                            </div>

                        </div>
                        @endforeach
                    </div>

                    @if(count($pedidos) > 4)
                    <div class="text-center mt-4" id="ver-mas-wrap">
                        <button class="btn-ver-mas" id="btnVerMas" onclick="toggleVerMas()">
                            <i class="bi bi-chevron-down me-1"></i>
                            Ver {{ count($pedidos) - 4 }} pedidos más
                        </button>
                    </div>
                    @endif
                @endif

            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="container-fluid bg-black text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="text-center mb-4">
                <h5>Síguenos en</h5>
                <div class="d-flex justify-content-center gap-3 mt-2">
                    <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-6 col-md-3 mb-3">
                    <h6 class="fw-bold">Acerca de Street Urban</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Compra segura</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Términos y condiciones</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Formas de pago</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <h6 class="fw-bold">Información adicional</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Registro</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Contáctanos</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Política de datos</a></li>
                    </ul>
                </div>
            </div>
            <div class="text-center mt-4 border-top pt-3">
                <p class="mb-1">Con fines educativos únicamente</p>
                <small>&copy; Derechos de autor pertenecen a Koaj, Pull & Bear y Bershka</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/PuntoInicio/Cliente/Pedidos.js') }}"></script>
    
</body>
</html>