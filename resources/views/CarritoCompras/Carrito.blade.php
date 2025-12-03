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
    <!-- Estilos personalizados -->
    <link href="../../css/CarritoCompras/Carrito.css" rel="stylesheet">
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
                    <li><a href="../PuntoInicio/Inicio.php" class="text-decoration-none">Inicio</a></li>
                    <li><a href="#" class="text-decoration-none">Catálogo</a></li>
                    <li><a href="#" class="text-decoration-none">Ofertas</a></li>
                    <li><a href="#" class="text-decoration-none">Contacto</a></li>
                </ul>
            </div>
        </div>

        <!-- Logo -->
        <a href="../../Vista/PuntoInicio/Inicio.php" class="mx-auto logo">
            <img src="../../img/CarritoCompras/Logo-blanco.png" alt="Logo">
        </a>

        <!-- Iconos (búsqueda, login, carrito) -->
        <div class="botones d-flex align-items-center">
            <i class="bi bi-search"  style="font-size: 20px; color: white; margin-right:8px;"></i>
            <input type="text"  class="form-control form-control-sm rounded-pill mx-2" placeholder="Buscar..." aria-label="Buscar">

            <a href="#">
                <i class="bi bi-person" style="font-size: 24px; margin-left: 15px; color: white;"></i>
            </a>

            <a href="#">
                <i class="bi bi-cart" data-modal="modalCarrito" style="font-size: 24px; margin-left: 15px; color: white;"></i>
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

                    <!-- Primera card -->
                    <div class="card mb-3">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-2 text-center">
                                <img src="../../img/CarritoCompras/Chaqueta-negra.png" class="img-fluid rounded" alt="Producto">
                            </div>
                            <div class="col-md-7">
                                <div class="card-body">
                                    <h5 class="card-title">Chaqueta negra</h5>
                                    <p class="card-text text-muted">Descripción breve del producto</p>
                                    <p class="card-text text-muted">Talla: M</p>
                                    <p class="card-text text-muted">Material: Algodón</p>
                                    <p class="text-success">Disponible</p>

                                    <!-- Botones -->
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="number" value="1" class="form-control w-auto" min="1">
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-end pe-4">
                                <h5 class="text-dark">$90.000</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Segunda card -->
                    <div class="card mb-3">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-2 text-center">
                                <img src="../../img/CarritoCompras/Chaqueta-gris.jpg" class="img-fluid rounded" alt="Producto">
                            </div>
                            <div class="col-md-7">
                                <div class="card-body">
                                    <h5 class="card-title">Chaqueta gris</h5>
                                    <p class="card-text text-muted">Descripción breve del producto</p>
                                    <p class="card-text text-muted">Talla: L</p>
                                    <p class="card-text text-muted">Material: Algodon</p>
                                    <p class="text-success">Disponible</p>

                                    <!-- Botones -->
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="number" value="1" class="form-control w-auto" min="1">
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-end pe-4">
                                <h5 class="text-dark">$59.000</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin columna productos -->

                <!-- Columna resumen -->
                <div class="col-lg-4 mt-4 mt-lg-0">
    <div class="card p-4 shadow-sm">
        <h5 class="mb-3 border-bottom pb-2">Resumen del pedido</h5>
        
        <p class="d-flex justify-content-between">
            <span>Subtotal (2 productos):</span>
            <span class="fw-bold text-dark">$149.000</span> 
            </p>
        
        <p class="d-flex justify-content-between text-success">
            <span>Costo de Envío:</span>
            <span class="fw-bold">GRATIS</span>
        </p>

        <div class="form-check mb-3">
             </div>

        <div class="border-top pt-3 mt-2">
            <p class="d-flex justify-content-between fs-5">
                <span class="fw-bold">Total del Pedido:</span>
                <span class="fw-bold text-dark">$149.000</span>
            </p>
        </div>

        <button class="btn btn-boton-pago w-100 mt-3 py-2" id="btnProcederPago">
            Proceder al pago
        </button>
    </div>
</div>
                <!-- Fin columna resumen -->

            </div>
        </div>
        
        <!-- Separador -->
        <div class="container my-5">
            <hr>
        </div>

        <!-- Carrusel de productos -->
        <div class="container my-5">
            <h3 class="mb-4 text-center">Artículos destacados que te pueden gustar</h3>
            
            <div id="productosCarrusel" class="carousel slide" data-bs-ride="carousel">
                
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#productosCarrusel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#productosCarrusel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#productosCarrusel" data-bs-slide-to="2"></button>
                </div>

                <div class="carousel-inner">
                    
                    <!-- Primer slide -->
                    <div class="carousel-item active">
                        <div class="row row-cols-2 row-cols-md-4 g-4 justify-content-center">
                            <div class="col">
                                <div class="card h-100 text-center p-2">
    <img src="../../img/CarritoCompras/Chaqueta-gris.jpg" class="card-img-top mx-auto mt-2" alt="Chaqueta gris de oferta" style="height: 150px; width: auto; object-fit: contain;">
    <div class="card-body d-flex flex-column justify-content-between">
        <div>
            <h6 class="card-title text-truncate">Nombre de producto sugerido 1</h6>
            <p class="text-danger small fw-bold">Oferta del día</p>
            <p class="card-text fw-bold">$45.99</p>
        </div>
        <button class="btn-agregar-carrusel">Agregar al carrito</button>
    </div>
</div>
                            </div>
                            
                            <div class="col">
                                <div class="card h-100 text-center p-2">
                                    <img src="../../img/CarritoCompras/Jean-negro.jpg" class="card-img-top mx-auto mt-2" alt="Producto Sugerido 2" style="height: 150px; width: auto; object-fit: contain;">
                                    <div class="card-body">
                                        <h6 class="card-title text-truncate">Jean negro ancho</h6>
                                        <p class="text-muted small">Envío GRATIS</p>
                                        <p class="card-text fw-bold">$120.00</p>
                                        <button class="btn-agregar-carrusel">Agregar al carrito</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Segundo slide -->
                    <div class="carousel-item">
                        <div class="row row-cols-2 row-cols-md-4 g-4 justify-content-center">
                            
                            <div class="col">
                                <div class="card h-100 text-center p-2">
                                    <img src="../../img/CarritoCompras/Chaqueta-cafe.jpg" class="card-img-top mx-auto mt-2" alt="Producto Sugerido 3" style="height: 150px; width: auto; object-fit: contain;">
                                    <div class="card-body">
                                        <h6 class="card-title text-truncate">Chaqueta café</h6>
                                        <p class="text-muted small">50% de descuento</p>
                                        <p class="card-text fw-bold">$899.00</p>
                                        <button class="btn-agregar-carrusel">Agregar al carrito</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="card h-100 text-center p-2">
                                    <img src="../../img/CarritoCompras/Jean-azul.jpg" class="card-img-top mx-auto mt-2" alt="Producto Sugerido 4" style="height: 150px; width: auto; object-fit: contain;">
                                    <div class="card-body">
                                        <h6 class="card-title text-truncate">Jean azul</h6>
                                        <p class="text-success small">Disponible ahora</p>
                                        <p class="card-text fw-bold">$350.50</p>
                                        <button class="btn-agregar-carrusel">Agregar al carrito</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tercer slide -->
                    <div class="carousel-item">
                        <div class="row row-cols-2 row-cols-md-4 g-4 justify-content-center">
                            
                            <div class="col">
                                <div class="card h-100 text-center p-2">
                                    <img src="../../img/CarritoCompras/Sudadera-negra.jpg" class="card-img-top mx-auto mt-2" alt="Producto Sugerido 3" style="height: 150px; width: auto; object-fit: contain;">
                                    <div class="card-body">
                                        <h6 class="card-title text-truncate">Sudadera Negra</h6>
                                        <p class="text-muted small">50% de descuento</p>
                                        <p class="card-text fw-bold">$50.000</p>
                                        <button class="btn-agregar-carrusel">Agregar al carrito</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="card h-100 text-center p-2">
                                    <img src="../../img/CarritoCompras/Chaqueta-azul.jpg" class="card-img-top mx-auto mt-2" alt="Producto Sugerido 4" style="height: 150px; width: auto; object-fit: contain;">
                                    <div class="card-body">
                                        <h6 class="card-title text-truncate">Chaqueta azul</h6>
                                        <p class="text-success small">Disponible ahora</p>
                                        <p class="card-text fw-bold">$150.000</p>
                                        <button class="btn-agregar-carrusel">Agregar al carrito</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>





                </div>

                <!-- Controles del carrusel -->
                <button class="carousel-control-prev" type="button" data-bs-target="#productosCarrusel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productosCarrusel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer-section container-fluid py-4 px-md-5 bg-black text-white">
        <div class="row text-center">
            
            <div class="col-md-4 mb-3">
                <h5 class="footer-title">Síguenos en</h5>
                <div class="d-flex justify-content-center social-icons-container gap-2">
                    <a href="#" class="social-icon-circle"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon-circle"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon-circle"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="social-icon-circle"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <h5 class="footer-title">Acerca de STREET</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Aviso de Privacidad</a></li>
                    <li><a href="#">Términos y condiciones</a></li>
                    <li><a href="#">Formas de pago</a></li>
                    <li><a href="#">Uso de Cookies</a></li>
                    <li><a href="#">Nuestro Catálogo</a></li>
                </ul>
            </div>

            <div class="col-md-4 mb-3">
                <h5 class="footer-title">Información adicional</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Política de Datos</a></li>
                    <li><a href="#">Contáctanos</a></li>
                    <li><a href="#">Registro</a></li>
                    <li><a href="#">Soporte</a></li>
                </ul>
            </div>

            <div class="col-12 mt-3">
                <p class="mb-0 small">
                    Sitio web creado por S.I.R.O. © 2025.<br>
                    Imágenes utilizadas con fines educativos, propiedad de Koaj® y Pull&Bear®.  
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
             integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
             crossorigin="anonymous"></script>
    
    <script src="/js/CarritoCompras/Carrito.js"></script> 

</body>
</html>