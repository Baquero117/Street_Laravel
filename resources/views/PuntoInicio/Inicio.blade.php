<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tienda Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rubik:wght@300;400;500;700&family=Permanent+Marker&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/PuntoInicio/Inicio.css') }}">

</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top my-0">
        <div class="container-fluid bg-white shadow-sm fixed-top py-2">

            <a href="{{ url('/inicio') }}" class="navbar-brand fw-bold logo-urbano px-5">
                Urban Street
            </a>


            <div class="collapse navbar-collapse justify-content-center">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('/hombre') }}" >Hombre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/mujer') }}">Mujer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/moda') }}">Lo mejor de la moda</a>
                    </li>
                </ul>
            </div>

            <div class="d-flex align-items-center gap-3">
                <form class="d-flex custom-search">
                    <div class="input-group">
                        <input class="form-control rounded-pill" type="search" placeholder="Buscar..." aria-label="Buscar">

                        <span class="input-group-text bg-white border-0 rounded-pill ms-n5">
                            <i class="bi bi-search"></i>
                        </span>
                    </div>
                </form>

                <div class="dropdown">

                    <a href="#" class="text-dark fs-5 dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item" href="{{ url('cuenta') }}">
                                Perfil
                            </a>
                        </li>

                        <li>
                        <a href="{{ route('registro') }}" class="dropdown-item">
                            Registro
                        </a>
                        </li>


                    </ul>
                </div>


                {{-- CARRITO --}}
                <a href="{{ url('/carrito') }}" class="text-dark fs-5">
                    <i class="bi bi-cart3"></i>
                </a>
            </div>

        </div>
    </nav>


    <!-- CARRUSEL -->
    <div id="carruselProductos" class="carousel slide mt-4" data-bs-ride="carousel" data-bs-interval="2000">

        <div class="carousel-inner">

            <div class="carousel-item active">
                <div class="d-flex justify-content-center gap-3">
                    <img src="{{ asset('img/PuntoInicio/2.chaquetaGris.jpg') }}" class="d-block w-25 rounded" alt="Producto 1">
                    <img src="{{ asset('img/PuntoInicio/1.chaquetaGris.jpg') }}" class="d-block w-25 rounded" alt="Producto 2">
                    <img src="{{ asset('img/PuntoInicio/3.chaquetaGris.jpg') }}" class="d-block w-25 rounded" alt="Producto 3">
                </div>
            </div>

            <div class="carousel-item">
                <div class="d-flex justify-content-center gap-3">
                    <img src="{{ asset('img/PuntoInicio/2.chaquetaCafe.jpg') }}" class="d-block w-25 rounded" alt="Producto 4">
                    <img src="{{ asset('img/PuntoInicio/1.chaquetaCafe.jpg') }}" class="d-block w-25 rounded" alt="Producto 5">
                    <img src="{{ asset('img/PuntoInicio/3.chaquetaCafe.jpg') }}" class="d-block w-25 rounded" alt="Producto 6">
                </div>
            </div>

            <div class="carousel-item">
                <div class="d-flex justify-content-center gap-3">
                    <img src="{{ asset('img/PuntoInicio/2.chaquetaNegra.jpg') }}" class="d-block w-25 rounded" alt="Producto 7">
                    <img src="{{ asset('img/PuntoInicio/1.chaquetaNegra.jpg') }}" class="d-block w-25 rounded" alt="Producto 8">
                    <img src="{{ asset('img/PuntoInicio/3.chaquetaNegra.jpg') }}" class="d-block w-25 rounded" alt="Producto 9">
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


    <!-- CARDS -->
    <div class="container-fluid bg-light text-dark py-5 mt-2">
        <div class="container">
            <h2 class="text-center mb-4">La moda la puedes inventar tú.</h2>

            <div class="row g-4">

    @forelse($productos as $producto)
        <div class="col-md-4">
            <div class="card h-100">
                <img src="{{ asset('storage/' . $producto['imagen']) }}" 
                     class="card-img-top" 
                     alt="{{ $producto['nombre'] }}"
                     onerror="this.src='https://via.placeholder.com/400x400?text=Sin+Imagen'">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $producto['nombre'] }}</h5>
                    <p class="text-muted mb-2">{{ $producto['color'] ?? 'Varios colores' }}</p>
                    <p class="fw-bold text-success fs-5">${{ number_format($producto['precio'], 0, ',', '.') }}</p>
                    
                    <button class="btn btn-dark ver-detalle-dinamico" data-id="{{ $producto['id_producto'] }}">
                        Ver más
                    </button>

                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                No hay productos disponibles en este momento
            </div>
        </div>
    @endforelse
</div>
        </div>

        <!-- MODAL -->
<div class="modal fade" id="detalleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalNombre"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img id="modalImagen" src="" class="img-fluid rounded" alt="">
                    </div>
                    <div class="col-md-6">
                        <p id="modalDescripcion"></p>
                        <p><strong>Color:</strong> <span id="modalColor"></span></p>
                        <p><strong>Precio:</strong> <span class="text-success fw-bold fs-4">$<span id="modalPrecio"></span></span></p>
                        <div class="mb-3">
                            <strong>Tallas disponibles:</strong>
                            <div id="modalTallas" class="mt-2"></div>
                        </div>
                        <button class="btn btn-success w-100" onclick="agregarAlCarrito()">
                            <i class="bi bi-cart-plus"></i> Agregar al Carrito
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/PuntoInicio/Inicio.js') }}"></script>

</body>
</html>
