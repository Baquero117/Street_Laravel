<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/Fachada/Fachada.css') }}">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top my-0">
        <div class="container-fluid bg-white shadow-sm fixed-top py-2">

            <a class="navbar-brand fw-bold">
                <i class="bi bi-bag-check-fill me-2"></i> ¿Todo bien?
            </a>

            <div class="collapse navbar-collapse justify-content-center">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Hombre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Mujer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Lo mejor de la moda</a>
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

                {{-- PERFIL --}}
                <div class="dropdown">
                    <a href="#" class="text-dark fs-5 dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ url('login') }}">Sign In</a></li>
                        <li><a class="dropdown-item" href="#">Sign Up</a></li>
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

                {{-- CARD 1 --}}
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="{{ asset('img/PuntoInicio/1.chaquetaCafe.jpg') }}" class="card-img-top" alt="Chaqueta Café">
                        <div class="card-body text-center">
                            <h5 class="card-title">Chaqueta Café</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Chaqueta Café"
                                data-descripcion="Chaqueta de algodón suave y cálida."
                                data-categoria="Casual"
                                data-color="Café"
                                data-talla="M">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CARD 2 --}}
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="{{ asset('img/PuntoInicio/2.chaquetaCafe.jpg') }}" class="card-img-top" alt="Chaqueta Café 2">
                        <div class="card-body text-center">
                            <h5 class="card-title">Chaqueta Café Hombre</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Chaqueta Café Hombre"
                                data-descripcion="Ideal para clima frío, cómoda y elegante."
                                data-categoria="Casual"
                                data-color="Café Oscuro"
                                data-talla="L">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CARD 3 --}}
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="{{ asset('img/PuntoInicio/3.chaquetaCafe.jpg') }}" class="card-img-top" alt="Chaqueta Café 3">
                        <div class="card-body text-center">
                            <h5 class="card-title">Chaqueta Coffee</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Chaqueta Coffee"
                                data-descripcion="Material premium, perfecta para looks urbanos."
                                data-categoria="Urbano"
                                data-color="Coffee"
                                data-talla="S">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CARD 4 --}}
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="{{ asset('img/PuntoInicio/1.chaquetaGris.jpg') }}" class="card-img-top" alt="Chaqueta Gris">
                        <div class="card-body text-center">
                            <h5 class="card-title">Chaqueta Gris</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Chaqueta Gris"
                                data-descripcion="Chaqueta versátil para cualquier ocasión."
                                data-categoria="Casual"
                                data-color="Gris"
                                data-talla="M">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CARD 5 --}}
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="{{ asset('img/PuntoInicio/2.chaquetaGris.jpg') }}" class="card-img-top" alt="Chaqueta Gris 2">
                        <div class="card-body text-center">
                            <h5 class="card-title">Chaqueta Frost Gray</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Chaqueta Frost Gray"
                                data-descripcion="Suave y ligera, ideal para looks urbanos."
                                data-categoria="Urbano"
                                data-color="Gris Hielo"
                                data-talla="L">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CARD 6 --}}
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="{{ asset('img/PuntoInicio/3.chaquetaGris.jpg') }}" class="card-img-top" alt="Chaqueta Gris 3">
                        <div class="card-body text-center">
                            <h5 class="card-title">Chaqueta Steel</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Chaqueta Steel"
                                data-descripcion="Perfecta para clima frío y estilo urbano."
                                data-categoria="Invierno"
                                data-color="Gris Acero"
                                data-talla="XL">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CARD 7 --}}
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="{{ asset('img/PuntoInicio/1.chaquetaNegra.jpg') }}" class="card-img-top" alt="Chaqueta Negra">
                        <div class="card-body text-center">
                            <h5 class="card-title">Chaqueta Negra</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Chaqueta Negra"
                                data-descripcion="Clásica chaqueta negra estilo urbano."
                                data-categoria="Urbano"
                                data-color="Negro"
                                data-talla="M">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CARD 8 --}}
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="{{ asset('img/PuntoInicio/2.chaquetaNegra.jpg') }}" class="card-img-top" alt="Chaqueta Negra 2">
                        <div class="card-body text-center">
                            <h5 class="card-title">Chaqueta Black Urban</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Chaqueta Black Urban"
                                data-descripcion="Material resistente y diseño urbano moderno."
                                data-categoria="Urbano"
                                data-color="Negro"
                                data-talla="L">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CARD 9 --}}
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="{{ asset('img/PuntoInicio/3.chaquetaNegra.jpg') }}" class="card-img-top" alt="Chaqueta Negra 3">
                        <div class="card-body text-center">
                            <h5 class="card-title">Chaqueta Midnight</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Chaqueta Midnight"
                                data-descripcion="Look sofisticado con acabado premium."
                                data-categoria="Elegante"
                                data-color="Negro Midnight"
                                data-talla="S">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- MODAL -->
        <div class="modal fade" id="detalleModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Atención</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center">
                        <p id="modalMensaje" class="fs-5"></p>
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
    <script src="{{ asset('js/Fachada/Fachada.js') }}"></script>

</body>
</html>
