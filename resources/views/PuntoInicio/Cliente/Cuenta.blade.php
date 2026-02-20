<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Urban Street</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rubik:wght@300;400;500;700&family=Permanent+Marker&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/PuntoInicio/Cliente/Cuenta.css') }}">
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
                            <a class="dropdown-item" href="{{ url('cuenta') }}">
                                Ver Perfil
                            </a>
                        </li>

                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    Cerrar sesión
                                </button>
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

    <div class="container perfil-container mt-5 pt-5">

        <div class="row">

            <div class="col-md-6 col-lg-5">

                <h2 class="perfil-titulo">
                    ¡Hola, {{ Session::get('usuario_nombre') }}!
                </h2>

                <a href="{{ route('mis-pedidos') }}" class="text-decoration-none text-dark">
                    <div class="perfil-card">
                        <div class="perfil-card-title">Pedidos</div>
                        <div class="perfil-card-sub">Historial de pedidos</div>
                    </div>
                </a>

                <a href="{{ url('perfil') }}" class="text-decoration-none text-dark">
                    <div class="perfil-card">
                        <div class="perfil-card-title">Perfil</div>
                        <div class="perfil-card-sub">Mi información personal</div>
                    </div>
                </a>

                <a href="{{ url('inicio') }}" class="text-decoration-none text-dark">
                    <div class="perfil-card">
                        <div class="perfil-card-title">Inicio</div>
                        <div class="perfil-card-sub">Volver a la pagina principal</div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

