<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cuenta - Urban Street</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rubik:wght@300;400;500;700&family=Permanent+Marker&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/PuntoInicio/Cliente/Cuenta.css') }}">
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
                    <ul class="cuenta-dropdown-menu" id="userDropdownMenuMobile">
                        @if(Session::has('token'))
                            <li><a class="cuenta-dropdown-item" href="{{ url('cuenta') }}">Perfil</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="cuenta-dropdown-item w-100 text-start border-0 bg-transparent">Cerrar sesión</button>
                                </form>
                            </li>
                        @else
                            <li><a class="cuenta-dropdown-item" href="{{ route('login') }}">Iniciar sesión</a></li>
                            <li><a class="cuenta-dropdown-item" href="{{ route('registro') }}">Registrarse</a></li>
                        @endif
                    </ul>
                </div>

                <a href="{{ url('/carrito') }}" class="text-dark icon-wrapper position-relative">
                    <i class="bi bi-bag fs-5"></i>
                </a>
            </div>

            <!-- Toggler hamburguesa -->
            <button class="navbar-toggler border-0 shadow-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarCuentaMenu"
                aria-controls="navbarCuentaMenu" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú colapsable -->
            <div class="collapse navbar-collapse" id="navbarCuentaMenu">

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
                        <ul class="cuenta-dropdown-menu" id="userDropdownMenu">
                            @if(Session::has('token'))
                                <li><a class="cuenta-dropdown-item" href="{{ url('cuenta') }}">Perfil</a></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="cuenta-dropdown-item w-100 text-start border-0 bg-transparent">Cerrar sesión</button>
                                    </form>
                                </li>
                            @else
                                <li><a class="cuenta-dropdown-item" href="{{ route('login') }}">Iniciar sesión</a></li>
                                <li><a class="cuenta-dropdown-item" href="{{ route('registro') }}">Registrarse</a></li>
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
    <div class="container perfil-container mt-3 pt-3">
        <div class="row">

            <div class="col-md-6 col-lg-5">
                <h2 class="perfil-titulo">
                    ¡Hola, {{ Session::get('usuario_nombre') }}!
                </h2>

                <a href="{{ url('perfil') }}" class="text-decoration-none text-dark">
                    <div class="perfil-card">
                        <div class="perfil-card-title">Perfil</div>
                        <div class="perfil-card-sub">Mi información personal</div>
                    </div>
                </a>

                <a href="{{ route('favoritos') }}" class="text-decoration-none text-dark">
                    <div class="perfil-card">
                        <div class="perfil-card-title">Favoritos</div>
                        <div class="perfil-card-sub">Productos que me gustan</div>
                    </div>
                </a>

                <a href="{{ route('mis-pedidos') }}" class="text-decoration-none text-dark">
                    <div class="perfil-card">
                        <div class="perfil-card-title">Pedidos</div>
                        <div class="perfil-card-sub">Historial de pedidos</div>
                    </div>
                </a>

                <a href="#" class="text-decoration-none text-dark"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="perfil-card">
                        <div class="perfil-card-title">Sesión</div>
                        <div class="perfil-card-sub">Cierre de sesión</div>
                    </div>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>

            <div class="col-md-6 col-lg-7">
                <div class="perfil-img-container d-flex align-items-center justify-content-center">
                    <img src="{{ asset('img/bugs_bunny.jpeg') }}" class="perfil-img" alt="Imagen de perfil">
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
    <script src="{{ asset('js/PuntoInicio/Cliente/Cuenta.js') }}"></script>
    
</body>
</html>