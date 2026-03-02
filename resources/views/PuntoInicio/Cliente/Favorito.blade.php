<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Favoritos - Urban Street</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rubik:wght@300;400;500;700&family=Permanent+Marker&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/PuntoInicio/Cliente/Favorito.css') }}">
</head>

<body>

    <!-- NAVBAR igual al de inicio -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNavbar">
        <div class="container-fluid px-4 py-2">
            <div class="row w-100 align-items-center g-0">

                <!-- Logo izquierda -->
                <div class="col-auto">
                    <a href="{{ url('/inicio') }}" class="navbar-brand logo-urbano mb-0">
                        URBAN STREET
                    </a>
                </div>

                <!-- Espacio central -->
                <div class="col"></div>

                <!-- Iconos derecha -->
                <div class="col-auto d-flex align-items-center gap-3">

                    <div class="icon-wrapper">
                        <i class="bi bi-search fs-5"></i>
                    </div>

                    <div class="dropdown icon-wrapper">
                        <a href="#" class="text-dark">
                            <i class="bi bi-person fs-5"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                            @if(Session::has('token'))
                                <li><a class="dropdown-item py-2" href="{{ url('cuenta') }}">Perfil</a></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2">Cerrar sesión</button>
                                    </form>
                                </li>
                            @else
                                <li><a class="dropdown-item py-2" href="{{ route('login') }}">Iniciar sesión</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('registro') }}">Registrarse</a></li>
                            @endif
                        </ul>
                    </div>

                    <a href="{{ url('/carrito') }}" class="text-dark icon-wrapper position-relative">
                        <i class="bi bi-bag fs-5"></i>
                    </a>

                    <a href="{{ route('favoritos') }}" class="text-dark icon-wrapper position-relative">
                        <i class="bi bi-heart fs-5"></i>
                    </a>

                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="container favoritos-container mt-5 pt-5">
        <div class="row">

            <!-- Sidebar izquierda -->
            <div class="col-md-6 col-lg-5">
                <h2 class="favoritos-titulo">Mis Favoritos</h2>

                <a href="{{ route('perfil') }}" class="text-decoration-none text-dark">
                    <div class="cuenta-card">
                        <div class="cuenta-card-title">Perfil</div>
                        <div class="cuenta-card-sub">Mi información personal</div>
                    </div>
                </a>

                <a href="{{ route('mis-pedidos') }}" class="text-decoration-none text-dark">
                    <div class="cuenta-card">
                        <div class="cuenta-card-title">Pedidos</div>
                        <div class="cuenta-card-sub">Historial de pedidos</div>
                    </div>
                </a>

                <a href="{{ route('favoritos') }}" class="text-decoration-none text-dark">
                    <div class="cuenta-card active">
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

                @if(empty($favoritos))
                    <div class="formulario-contenedor text-center py-5">
                        <i class="bi bi-heart" style="font-size: 3.5rem; color: #ccc;"></i>
                        <h5 class="mt-4 fw-semibold">Aún no tienes favoritos</h5>
                        <p class="text-muted" style="font-size: 14px;">
                            Cuando marques un producto con ♥, aparecerá aquí.
                        </p>
                        <a href="{{ url('/inicio') }}" class="btn btn-guardar mt-2">Ir a la tienda</a>
                    </div>

                @else
                    <p class="favoritos-contador mb-3">
                        <i class="bi bi-heart-fill text-danger me-1"></i>
                        {{ count($favoritos) }} {{ count($favoritos) === 1 ? 'producto guardado' : 'productos guardados' }}
                    </p>

                    <div class="d-flex flex-column gap-3" id="lista-favoritos">
                        @foreach($favoritos as $item)
                        <div class="favorito-card" id="card-favorito-{{ $item['id_favorito'] }}">

                            <div class="favorito-imagen-wrap">
                                <img src="{{ asset('storage/' . $item['imagen']) }}"
                                    alt="{{ $item['nombre'] }}"
                                    class="favorito-imagen">
                            </div>

                            <div class="favorito-info">
                                <div class="favorito-header">
                                    <span class="favorito-nombre">{{ $item['nombre'] }}</span>
                                    <span class="favorito-estado estado-{{ strtolower($item['estado'] ?? 'activo') }}">
                                        {{ $item['estado'] ?? 'Activo' }}
                                    </span>
                                </div>

                                <p class="favorito-descripcion">{{ $item['descripcion'] }}</p>

                                <div class="favorito-footer">
                                    <div class="favorito-meta">
                                        <span class="favorito-color">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 9px;"></i>
                                            {{ $item['color'] }}
                                        </span>
                                        <span class="favorito-precio">
                                            ${{ number_format($item['precio'], 3, '.', ',') }} COP
                                        </span>
                                    </div>

                                    <div class="favorito-acciones">
                                        <button class="btn btn-ver-producto"
                                                onclick="verDetalleFavorito({{ $item['id_producto'] }})">
                                            <i class="bi bi-eye me-1"></i> Ver producto
                                        </button>
                                        <button class="btn btn-quitar-favorito"
                                                data-id="{{ $item['id_favorito'] }}">
                                            <i class="bi bi-heart-fill me-1"></i> Quitar
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- MODAL DETALLE PRODUCTO -->
    <div class="modal fade" id="detalleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 rounded-0 overflow-hidden">
                <button type="button" class="btn-close custom-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-md-7 d-flex align-items-center bg-light">
                            <img id="modalImagen" src="" class="img-fluid w-100 img-product-detail" alt="">
                        </div>
                        <div class="col-md-5 d-flex flex-column p-4 p-lg-5" style="max-height: 90vh; overflow-y: auto;">
                            <h2 id="modalNombre" class="fw-bold mb-1 text-uppercase"></h2>
                            <p id="modalColor" class="text-muted small mb-3"></p>
                            <h3 class="fw-light mb-4 text-dark">$<span id="modalPrecio"></span></h3>
                            <div class="mb-4">
                                <p id="modalDescripcion" class="text-secondary small" style="line-height: 1.6;"></p>
                            </div>
                            <div class="mb-4">
                                <label class="fw-bold small mb-2 text-uppercase" style="letter-spacing: 1px;">Seleccionar Talla</label>
                                <div id="modalTallas" class="d-flex flex-wrap gap-2"></div>
                            </div>
                            <div class="mt-auto border-top pt-4">
                                <button class="btn btn-dark w-100 rounded-0 py-3 mb-2 text-uppercase fw-bold"
                                        onclick="agregarAlCarrito()">
                                    Añadir al carrito
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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
    <script src="{{ asset('js/PuntoInicio/Cliente/Favorito.js') }}"></script>
</body>
</html>