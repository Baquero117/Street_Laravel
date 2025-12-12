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
    <link rel="stylesheet" href="{{ asset('css/OtrasVistas/moda.css') }}">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top my-0">
        <div class="container-fluid bg-white shadow-sm fixed-top py-2">

            <a class="navbar-brand fw-bold">
                <a href="{{ route('inicio') }}" class="mx-auto logo">
            <img src="{{ asset('img/OtrasVistas/Logo.png') }}" alt="Logo">
        </a>
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
    <div class="container py-4 mt-5">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img src="{{ asset('img/OtrasVistas/Ofertas.jpg') }}"
                         class="d-block w-100 rounded-3"
                         alt="Imagen principal"
                         style="height: 550px; object-fit: cover;">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('img/OtrasVistas/gracias.jpg') }}"
                         class="d-block w-100 rounded-3"
                         alt="Imagen principal"
                         style="height: 550px; object-fit: cover;">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('img/OtrasVistas/descuento.jpg') }}"
                         class="d-block w-100 rounded-3"
                         alt="Imagen principal"
                         style="height: 550px; object-fit: cover;">
                </div>


            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>



    <!-- CARDS -->
 <div class="container-fluid bg-light text-dark py-5 mt-2">
        <div class="container">
            <h2 class="text-center mb-4">El mejor catalogo en ropa femenina </h2>

            <div class="row g-4">

                {{-- CARD CARRUSEL 1--}}

                <div class="col-md-4">
                    <div class="card h-100">

                        <div id="carouselCard1" class="carousel slide">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/jean-negro.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/jean-negro2.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/jean-negro3.jpg') }}" class="d-block w-100">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCard1" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCard1" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>

                        <div class="card-body text-center">
                            <h5 class="card-title">Camiseta gris</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Camiseta gris"
                                data-descripcion="Camisa edición rick and morty de algodon."
                                data-categoria="Casual"
                                data-color="Gris"
                                data-talla="M">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>



             

                {{-- CARD CARRUSEL 2 --}}
                <div class="col-md-4">
                    <div class="card h-100">

                        <div id="carouselCard2" class="carousel slide">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/manga-larga.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/manga-larga2.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/manga-larga3.jpg') }}" class="d-block w-100">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCard2" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCard2" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>

                        <div class="card-body text-center">
                            <h5 class="card-title">Camiseta negra</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Camiseta negra"
                                data-descripcion="Camiseta negra estilo oversize de algodon."
                                data-categoria="Casual"
                                data-color="Negro"
                                data-talla="M">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>


                {{-- CARD CARRUSEL 3--}}
                <div class="col-md-4">
                    <div class="card h-100">

                        <div id="carouselCard3" class="carousel slide">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/manga-rosa2.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/manga-rosa3.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/manga-rosa.jpg') }}" class="d-block w-100">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCard3" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCard3" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>

                        <div class="card-body text-center">
                            <h5 class="card-title">Jean azul</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Jean azul"
                                data-descripcion="Jean azul estilizado."
                                data-categoria="Casual"
                                data-color="azul"
                                data-talla="32">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>



                               {{-- CARD CARRUSEL 4  --}}
                <div class="col-md-4">
                    <div class="card h-100">

                        <div id="carouselCard4" class="carousel slide">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/vestido-negro.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/vestido-negro2.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/vestido-negro3.jpg') }}" class="d-block w-100">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCard4" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCard4" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>

                        <div class="card-body text-center">
                            <h5 class="card-title">Buzo negro</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Buzo negro"
                                data-descripcion="Buzo negro de algodon."
                                data-categoria="Casual"
                                data-color="Negro"
                                data-talla="M">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>

                               {{-- CARD CARRUSEL 5 --}}
                <div class="col-md-4">
                    <div class="card h-100">

                        <div id="carouselCard5" class="carousel slide">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/jean-camuflado.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/jean-camuflado2.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/jean-camuflado3.jpg') }}" class="d-block w-100">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCard5" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCard5" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>

                        <div class="card-body text-center">
                            <h5 class="card-title">Pantalon negro</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Pantalon cargo negro"
                                data-descripcion="Pantalon cargo negro en drill ."
                                data-categoria="Casual"
                                data-color="Negro"
                                data-talla="M">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>


                           {{-- CARD CARRUSEL 6 --}}
                <div class="col-md-4">
                    <div class="card h-100">

                        <div id="carouselCard6" class="carousel slide">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/baggy-negro.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/baggy-negro3.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/baggy-negro2.jpg') }}" class="d-block w-100">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCard6" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCard6" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>

                        <div class="card-body text-center">
                            <h5 class="card-title">Camiseta estilizada</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Camiseta estilizada"
                                data-descripcion="Camiseta de algodon con estilo en las mangas."
                                data-categoria="Casual"
                                data-color="Gris"
                                data-talla="M">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>

                             {{-- CARD CARRUSEL 7 --}}
                <div class="col-md-4">
                    <div class="card h-100">

                        <div id="carouselCard7" class="carousel slide">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/camisa-blanca.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/camisa-blanca2.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/camisa-blanca3.jpg') }}" class="d-block w-100">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCard7" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCard7" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>

                        <div class="card-body text-center">
                            <h5 class="card-title">Camiseta lila</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Camiseta lila"
                                data-descripcion="Camiseta color lila de algodon."
                                data-categoria="Casual"
                                data-color="Lila"
                                data-talla="M">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>

                              {{-- CARD CARRUSEL 8 --}}
                <div class="col-md-4">
                    <div class="card h-100">

                        <div id="carouselCard8" class="carousel slide">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/medias-tobillera.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/medias-tobilleras.jpg') }}" class="d-block w-100">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCard8" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCard8" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>

                        <div class="card-body text-center">
                            <h5 class="card-title">Camiseta café</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Camiseta café"
                                data-descripcion="Camiseta café estilo oversize de algodon."
                                data-categoria="Casual"
                                data-color="Café"
                                data-talla="M">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>

                              {{-- CARD CARRUSEL 9 --}}
                <div class="col-md-4">
                    <div class="card h-100">

                        <div id="carouselCard9" class="carousel slide">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/short-azul.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/short-azul2.jpg') }}" class="d-block w-100">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('img/OtrasVistas/Mujer/short-azul3.jpg') }}" class="d-block w-100">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCard9" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCard9" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>

                        <div class="card-body text-center">
                            <h5 class="card-title">Baggy azul</h5>
                            <button class="btn btn-dark ver-detalle"
                                data-nombre="Baggy azul"
                                data-descripcion="Pantalon Jean azul estilo baggy."
                                data-categoria="Casual"
                                data-color="Azul"
                                data-talla="M">
                                Ver más
                            </button>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <!-- MODAL -->
        <div class="modal fade" id="detalleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="modalNombre"></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p id="modalDescripcion"></p>
                        <p><strong>Categoría:</strong> <span id="modalCategoria"></span></p>
                        <p><strong>Color:</strong> <span id="modalColor"></span></p>
                        <p><strong>Talla:</strong> <span id="modalTalla"></span></p>
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
    <script src="{{ asset('js/OtrasVistas/moda.js') }}"></script>

</body>
</html>
