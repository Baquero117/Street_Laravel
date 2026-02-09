<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hombre - Urban Street</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rubik:wght@300;400;500;700&family=Permanent+Marker&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/OtrasVistas/hombre.css') }}">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNavbar">
        <div class="container-fluid px-4 py-2">
            <div class="row w-100 align-items-center g-0">
                
                <!-- Logo izquierda -->
                <div class="col-auto">
                    <a href="{{ url('/inicio') }}" class="navbar-brand logo-urbano mb-0" id="brandLogo">
                        URBAN STREET
                    </a>
                </div>

                <!-- Links centrados -->
                <div class="col d-flex justify-content-center">
                    <div class="d-flex gap-4">
                        <a href="{{ url('/hombre') }}" class="nav-link-custom active-page">HOMBRE</a>
                        <a href="{{ url('/mujer') }}" class="nav-link-custom">MUJER</a>
                        <a href="{{ url('/moda') }}" class="nav-link-custom">LO MEJOR DE LA MODA</a>
                    </div>
                </div>

                <!-- Iconos derecha -->
                <div class="col-auto d-flex align-items-center gap-3">
                    
                    <!-- Búsqueda -->
                    <div class="icon-wrapper">
                        <i class="bi bi-search fs-5"></i>
                    </div>

                    <!-- Usuario -->
                    <div class="dropdown icon-wrapper">
                        <a href="#" class="text-dark" data-bs-toggle="dropdown">
                            <i class="bi bi-person fs-5"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                            <li><a class="dropdown-item py-2" href="{{ url('cuenta') }}">Perfil</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2">Cerrar sesión</button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <!-- Carrito -->
                    <a href="{{ url('/carrito') }}" class="text-dark icon-wrapper position-relative">
                        <i class="bi bi-bag fs-5"></i>
                    </a>
                </div>

            </div>
        </div>
    </nav>


    <!-- CARRUSEL -->
    <div class="carousel-section">
        <div id="carruselProductos" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">

            <div class="carousel-inner">

                <div class="carousel-item active">
                    <div class="d-flex justify-content-center gap-3">
                        <img src="{{ asset('img/OtrasVistas/Hombre/camisa-gris2.jpg') }}" class="carousel-image rounded" alt="Producto 1">
                        <img src="{{ asset('img/OtrasVistas/Hombre/camisa-gris.jpg') }}" class="carousel-image rounded" alt="Producto 2">
                        <img src="{{ asset('img/OtrasVistas/Hombre/camisa-gris3.jpg') }}" class="carousel-image rounded" alt="Producto 3">
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="d-flex justify-content-center gap-3">
                        <img src="{{ asset('img/OtrasVistas/Hombre/camisa-negra2.jpg') }}" class="carousel-image rounded" alt="Producto 4">
                        <img src="{{ asset('img/OtrasVistas/Hombre/camisa-negra.jpg') }}" class="carousel-image rounded" alt="Producto 5">
                        <img src="{{ asset('img/OtrasVistas/Hombre/camisa-negra3.jpg') }}" class="carousel-image rounded" alt="Producto 6">
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="d-flex justify-content-center gap-3">
                        <img src="{{ asset('img/OtrasVistas/Hombre/oversize-negra.jpg') }}" class="carousel-image rounded" alt="Producto 7">
                        <img src="{{ asset('img/OtrasVistas/Hombre/oversize-negra3.jpg') }}" class="carousel-image rounded" alt="Producto 8">
                        <img src="{{ asset('img/OtrasVistas/Hombre/oversize-negra2.jpg') }}" class="carousel-image rounded" alt="Producto 9">
                    </div>
                </div>

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carruselProductos" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#carruselProductos" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>
    </div>


    <!-- CARDS -->
    <div class="container-fluid bg-light text-dark py-5 mt-2">
        <div class="container">
            <h2 class="text-center mb-4">El mejor catálogo en ropa masculina</h2>

            <div class="row g-4">
                @forelse($productos as $producto)
                    <div class="col-md-3">
                        <div class="card h-100 product-card border-0">
                            <div class="image-wrapper position-relative">
                                <img src="{{ asset('storage/' . $producto['imagen']) }}" 
                                     class="card-img-top product-image" 
                                     alt="{{ $producto['nombre'] }}"
                                     data-id="{{ $producto['id_producto'] }}"
                                     onerror="this.src='https://via.placeholder.com/400x400?text=Sin+Imagen'">
                                
                                <!-- Logo en esquina superior derecha -->
                                <div class="product-logo">夜</div>
                            </div>
                            
                            <div class="card-body px-0 pt-3 pb-2">
                                <h6 class="card-title mb-1 text-dark fw-normal" style="font-size: 0.9rem;">
                                    {{ $producto['nombre'] }}
                                </h6>
                                <p class="text-dark mb-0 fw-bold" style="font-size: 0.95rem;">
                                    {{ number_format($producto['precio'], 3, '.', ',') }} COP
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            No hay productos de hombre disponibles en este momento
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>


    <!-- MODAL -->
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
                                <button class="btn btn-dark w-100 rounded-0 py-3 mb-2 text-uppercase fw-bold" onclick="agregarAlCarrito()">
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
    <footer class="container-fluid bg-black text-white pt-5 pb-3">
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
    <script src="{{ asset('js/OtrasVistas/hombre.js') }}"></script>

</body>
</html>