<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Carrito de Compras - Siro</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link href="{{ asset('css/CarritoCompras/Carrito.css') }}" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- HEADER -->
    <header class="container-barra d-flex px-3 py-1 text-dark align-items-center">

        <!-- Menú lateral -->
        <div id="logoScroll" class="logo font-weight-bold text-uppercase">
            <i class="bi bi-list fs-2" style="cursor:pointer;" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"></i>
        </div>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Menú</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="list-unstyled">
                    <li><a href="{{ route('inicio') }}" class="text-decoration-none">Inicio</a></li>
                    <li><a href="#" class="text-decoration-none">Catálogo</a></li>
                    <li><a href="#" class="text-decoration-none">Ofertas</a></li>
                    <li><a href="#" class="text-decoration-none">Contacto</a></li>
                </ul>
            </div>
        </div>

        <!-- Logo -->
        <a href="{{ route('inicio') }}" class="mx-auto logo">
            <img src="{{ asset('img/CarritoCompras/Logo-blanco.png') }}" alt="Logo">
        </a>

        <!-- Iconos -->
        <div class="botones d-flex align-items-center">
            <i class="bi bi-search" style="font-size: 20px; color: white; margin-right:8px;"></i>

            <input type="text" class="form-control form-control-sm rounded-pill mx-2" 
                   placeholder="Buscar..." aria-label="Buscar">

            <a href="#">
                <i class="bi bi-person" style="font-size: 24px; margin-left: 15px; color: white;"></i>
            </a>

            <a href="#">
                <i class="bi bi-cart" style="font-size: 24px; margin-left: 15px; color: white;"></i>
            </a>
        </div>
    </header>

    <!-- MAIN -->
    <main>
        <div class="container my-4">
            <div class="row">

                <!-- Columna productos -->
                <div class="col-lg-8">
                    <h3 class="mb-4">Carrito</h3>

                    <!-- Aquí irán productos dinámicos -->
                    @foreach($carrito as $item)
                    <div class="card mb-3">
                        <div class="row g-0 align-items-center">

                            <div class="col-md-2 text-center">
                                <img src="{{ asset('img/CarritoCompras/Chaqueta-azul.jpg') }}" alt="Chaqueta azul"
 
                                     class="img-fluid rounded" alt="Producto">
                            </div>

                            <div class="col-md-7">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->nombre }}</h5>
                                    <p class="card-text text-muted">{{ $item->descripcion }}</p>
                                    <p class="card-text text-muted">Talla: {{ $item->talla }}</p>
                                    <p class="card-text text-muted">Material: {{ $item->material }}</p>

                                    <p class="text-success">
                                        {{ $item->stock > 0 ? 'Disponible' : 'Agotado' }}
                                    </p>

                                    <div class="d-flex align-items-center gap-2">

                                        <!-- Actualizar cantidad -->
                                        <form action="{{ route('carrito.actualizar', $item->id) }}" method="POST">
                                            @csrf
                                            <input type="number" name="cantidad" min="1"
                                                   value="{{ $item->cantidad }}"
                                                   class="form-control w-auto">
                                        </form>

                                        <!-- Eliminar -->
                                        <form action="{{ route('carrito.eliminar', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 text-end pe-4">
                                <h5 class="text-dark">${{ number_format($item->precio, 0, ',', '.') }}</h5>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Columna resumen -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card p-4 shadow-sm">
                        <h5 class="mb-3 border-bottom pb-2">Resumen del pedido</h5>

                        <p class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span class="fw-bold text-dark">${{ number_format($subtotal, 0, ',', '.') }}</span>
                        </p>

                        <p class="d-flex justify-content-between text-success">
                            <span>Envío:</span>
                            <span class="fw-bold">GRATIS</span>
                        </p>

                        <div class="border-top pt-3 mt-2">
                            <p class="d-flex justify-content-between fs-5">
                                <span class="fw-bold">Total:</span>
                                <span class="fw-bold text-dark">${{ number_format($total, 0, ',', '.') }}</span>
                            </p>
                        </div>

                        <button class="btn btn-boton-pago w-100 mt-3 py-2" id="btnProcederPago">
                            Proceder al pago
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Separador -->
        <div class="container my-5">
            <hr>
        </div>

        <!-- Carrusel -->
        <div class="container my-5">
            <h3 class="mb-4 text-center">Artículos destacados que te pueden gustar</h3>

            <div id="productosCarrusel" class="carousel slide" data-bs-ride="carousel">

                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#productosCarrusel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#productosCarrusel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#productosCarrusel" data-bs-slide-to="2"></button>
                </div>

                <div class="carousel-inner">

                    @foreach($sugeridos as $i => $producto)
                    <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                        <div class="row row-cols-2 row-cols-md-4 g-4 justify-content-center">

                            <div class="col">
                                <div class="card h-100 text-center p-2">
                                    <img src="{{ asset('img/CarritoCompras/Chaqueta-azul.jpg') }}" alt="Chaqueta Azul"
                                       class="card-img-top mx-auto mt-2"
                                       style="height: 150px; object-fit: contain">


                                    <div class="card-body">
                                        <h6 class="card-title text-truncate">{{ $producto->nombre }}</h6>
                                        <p class="text-muted small">{{ $producto->etiqueta }}</p>
                                        <p class="card-text fw-bold">${{ $producto->precio }}</p>

                                        <button class="btn-agregar-carrusel">
                                            Agregar al carrito
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endforeach

                </div>

                <!-- Controles -->
                <button class="carousel-control-prev" type="button" data-bs-target="#productosCarrusel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productosCarrusel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="footer-section container-fluid py-4 px-md-5 bg-black text-white">
        <div class="row text-center">
            
            <div class="col-md-4 mb-3">
                <h5 class="footer-title">Síguenos en</h5>
                <div class="d-flex justify-content-center gap-2">
                    <a class="social-icon-circle"><i class="bi bi-facebook"></i></a>
                    <a class="social-icon-circle"><i class="bi bi-instagram"></i></a>
                    <a class="social-icon-circle"><i class="bi bi-youtube"></i></a>
                    <a class="social-icon-circle"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <h5 class="footer-title">Acerca de STREET</h5>
                <ul class="list-unstyled footer-links">
                    <li><a> Aviso de Privacidad</a></li>
                    <li><a> Términos y condiciones</a></li>
                    <li><a> Formas de pago</a></li>
                    <li><a> Uso de Cookies</a></li>
                    <li><a> Nuestro Catálogo</a></li>
                </ul>
            </div>

            <div class="col-md-4 mb-3">
                <h5 class="footer-title">Información adicional</h5>
                <ul class="list-unstyled footer-links">
                    <li><a>Política de Datos</a></li>
                    <li><a>Contáctanos</a></li>
                    <li><a>Registro</a></li>
                    <li><a>Soporte</a></li>
                </ul>
            </div>

            <div class="col-12 mt-3">
                <p class="mb-0 small">
                    Sitio web creado por S.I.R.O. © 2025 <br>
                    Imágenes educativas (Koaj® y Pull&Bear®)
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/CarritoCompras/Carrito.js') }}"></script>

</body>
</html>
