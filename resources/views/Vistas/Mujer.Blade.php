<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mujer - Urban Street</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rubik:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/OtrasVistas/mujer.css') }}">
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top" id="mainNavbar">
        <div class="container-fluid px-4 py-2">
            <div class="row w-100 align-items-center g-0">
                
                <div class="col-auto">
                    <a href="{{ url('/inicio') }}" class="navbar-brand logo-urbano mb-0" id="brandLogo">
                        URBAN STREET
                    </a>
                </div>

                <div class="col d-flex justify-content-center">
                    <div class="d-flex gap-4">
                        <a href="{{ url('/hombre') }}" class="nav-link-custom">HOMBRE</a>
                        <a href="{{ url('/mujer') }}" class="nav-link-custom active-page">MUJER</a>
                        <a href="{{ url('/moda') }}" class="nav-link-custom">LO MEJOR DE LA MODA</a>
                    </div>
                </div>

                <div class="col-auto d-flex align-items-center gap-3">
                    <div class="icon-wrapper"><i class="bi bi-search fs-5"></i></div>
                    
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

                    <a href="{{ url('/carrito') }}" class="text-dark icon-wrapper">
                        <i class="bi bi-bag fs-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="carousel-section">
        <div id="carruselProductos" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="d-flex justify-content-center gap-3">
                        <img src="{{ asset('img/OtrasVistas/Mujer/blusa-satin2.jpg') }}" class="carousel-image rounded" alt="Producto 1">
                        <img src="{{ asset('img/OtrasVistas/Mujer/blusa-satin.jpg') }}" class="carousel-image rounded" alt="Producto 2">
                        <img src="{{ asset('img/OtrasVistas/Mujer/blusa-satin3.jpg') }}" class="carousel-image rounded" alt="Producto 3">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="d-flex justify-content-center gap-3">
                        <img src="{{ asset('img/OtrasVistas/mujer/baggy-azul2.jpg') }}" class="carousel-image rounded" alt="Producto 4">
                        <img src="{{ asset('img/OtrasVistas/mujer/baggy-azul.jpg') }}" class="carousel-image rounded" alt="Producto 5">
                        <img src="{{ asset('img/OtrasVistas/mujer/baggy-azul3.jpg') }}" class="carousel-image rounded" alt="Producto 6">
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carruselProductos" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
            <button class="carousel-control-next" type="button" data-bs-target="#carruselProductos" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
        </div>
    </div>

    <div class="container-fluid bg-light text-dark py-5 mt-2">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold text-uppercase" style="letter-spacing: 2px;">
            La moda la puedes inventar tú.
        </h2>

        <div class="row g-4">
            @forelse($productos as $producto)
                <div class="col-md-3">
                    <div class="card h-100 product-card border-0 bg-transparent">
                        <div class="image-wrapper position-relative overflow-hidden">
                            <img src="{{ asset('storage/' . $producto['imagen']) }}" 
                                 class="card-img-top product-image ver-detalle-mujer" 
                                 alt="{{ $producto['nombre'] }}"
                                 data-id="{{ $producto['id_producto'] }}"
                                 onerror="this.src='https://via.placeholder.com/400x400?text=Sin+Imagen'">
                            
                            <div class="product-logo">夜</div>
                        </div>
                        
                        <div class="card-body px-0 pt-3 pb-2 text-start">
                            <h6 class="card-title mb-1 text-dark fw-normal" style="font-size: 0.85rem;">
                                {{ $producto['nombre'] }}
                            </h6>
                            
                            <p class="text-dark mb-0 fw-bold" style="font-size: 0.95rem;">
                                {{ number_format($producto['precio'], 3, '.', ',') }} COP
                            </p>
                            
                            @if(isset($producto['color']))
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <span class="color-indicator" style="background-color: {{ $producto['color_hex'] ?? '#ccc' }};"></span> 
                                        +{{ $producto['colores_count'] ?? 1 }} colores
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center border-0 shadow-sm">
                        No hay productos disponibles en este momento.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

    <div class="modal fade" id="detalleModalMujer" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 rounded-0">
                <button type="button" class="btn-close custom-close-modal" data-bs-dismiss="modal"></button>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-md-7 bg-light d-flex align-items-center">
                            <img id="modalImagenMujer" src="" class="img-fluid w-100" style="height: 85vh; object-fit: cover;">
                        </div>
                        <div class="col-md-5 p-5 d-flex flex-column">
                            <h2 id="modalNombreMujer" class="fw-bold mb-1 text-uppercase"></h2>
                            <p id="modalColorMujer" class="text-muted small mb-4"></p>
                            <h3 class="fw-light mb-4 text-dark">$<span id="modalPrecioMujer"></span></h3>
                            
                            <p id="modalDescripcionMujer" class="text-secondary small mb-5" style="line-height: 1.8;"></p>

                            <div class="mb-5">
                                <label class="fw-bold small mb-3 text-uppercase" style="letter-spacing: 1px;">Tallas</label>
                                <div id="modalTallasMujer" class="d-flex flex-wrap gap-2"></div>
                            </div>

                            <div class="mt-auto">
                                <button class="btn btn-dark w-100 rounded-0 py-3 text-uppercase fw-bold" onclick="agregarAlCarritoMujer()">
                                    Añadir a la bolsa
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-black text-white py-5">
        <div class="container text-center">
            <div class="d-flex justify-content-center gap-4 mb-4">
                <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                <a href="#" class="text-white fs-5"><i class="bi bi-tiktok"></i></a>
                <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
            </div>
            <p class="small text-secondary mb-0">© 2024 URBAN STREET. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/OtrasVistas/mujer.js') }}"></script>
</body>
</html>