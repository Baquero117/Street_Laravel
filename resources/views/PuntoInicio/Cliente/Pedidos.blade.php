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
                            <a class="dropdown-item" href="{{ url('cuenta') }}">Ver Perfil</a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item">Cerrar sesión</button>
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

    <div class="container pedidos-container mt-5 pt-5">
        <div class="row">

            {{-- Sidebar izquierda --}}
            <div class="col-md-6 col-lg-5">

                <h2 class="pedidos-titulo">Mis Pedidos</h2>

                <a href="{{ route('mis-pedidos') }}" class="text-decoration-none text-dark">
                    <div class="cuenta-card active">
                        <div class="cuenta-card-title">Pedidos</div>
                        <div class="cuenta-card-sub">Historial de pedidos</div>
                    </div>
                </a>

                <a href="{{ route('perfil') }}" class="text-decoration-none text-dark">
                    <div class="cuenta-card">
                        <div class="cuenta-card-title">Perfil</div>
                        <div class="cuenta-card-sub">Mi información personal</div>
                    </div>
                </a>

                <a href="{{ url('inicio') }}" class="text-decoration-none text-dark">
                    <div class="cuenta-card">
                        <div class="cuenta-card-title">Inicio</div>
                        <div class="cuenta-card-sub">Volver a la página principal</div>
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

            {{-- Contenido derecha --}}
            <div class="col-md-6 col-lg-7">

                @if(empty($pedidos))
                    <div class="formulario-contenedor text-center py-5">
                        <i class="bi bi-bag-x" style="font-size: 3.5rem; color: #ccc;"></i>
                        <h5 class="mt-4 fw-semibold">Aún no tienes pedidos</h5>
                        <p class="text-muted" style="font-size: 14px;">Cuando realices una compra, aparecerá aquí.</p>
                        <a href="{{ url('/inicio') }}" class="btn btn-guardar mt-2">Ir a la tienda</a>
                    </div>

                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($pedidos as $pedido)
                        <div class="pedido-card">

                            {{-- Header --}}
                            <div class="pedido-card-header">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-receipt"></i>
                                    <span class="pedido-numero">{{ $pedido['numero_factura'] ?? 'N/A' }}</span>
                                </div>
                                <span class="estado-badge estado-{{ strtolower($pedido['estado'] ?? 'pendiente') }}">
                                    {{ $pedido['estado'] ?? 'Pendiente' }}
                                </span>
                            </div>

                            {{-- Datos --}}
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

                            {{-- Acciones --}}
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
                @endif

            </div>
        </div>
    </div>

    {{-- FOOTER --}}
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
                <div class="col-md-3 mb-3">
                    <h6 class="fw-bold">Acerca de Street Urban</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Compra segura</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Términos y condiciones</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Formas de pago</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="fw-bold">Información adicional</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Registro</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Contáctanos</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Política de protección de datos</a></li>
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