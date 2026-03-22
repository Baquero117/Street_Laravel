<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi Perfil - Urban Street</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rubik:wght@300;400;500;700&family=Permanent+Marker&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/PuntoInicio/Cliente/Perfil.css') }}">
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
                    <ul class="perfil-dropdown-menu" id="userDropdownMenuMobile">
                        @if(Session::has('token'))
                            <li><a class="perfil-dropdown-item" href="{{ url('cuenta') }}">Perfil</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="perfil-dropdown-item w-100 text-start border-0 bg-transparent">Cerrar sesión</button>
                                </form>
                            </li>
                        @else
                            <li><a class="perfil-dropdown-item" href="{{ route('login') }}">Iniciar sesión</a></li>
                            <li><a class="perfil-dropdown-item" href="{{ route('registro') }}">Registrarse</a></li>
                        @endif
                    </ul>
                </div>

                <a href="{{ url('/carrito') }}" class="text-dark icon-wrapper position-relative">
                    <i class="bi bi-bag fs-5"></i>
                </a>
            </div>

            <!-- Toggler hamburguesa -->
            <button class="navbar-toggler border-0 shadow-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarPerfilMenu"
                aria-controls="navbarPerfilMenu" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú colapsable -->
            <div class="collapse navbar-collapse" id="navbarPerfilMenu">

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
                        <ul class="perfil-dropdown-menu" id="userDropdownMenu">
                            @if(Session::has('token'))
                                <li><a class="perfil-dropdown-item" href="{{ url('cuenta') }}">Perfil</a></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="perfil-dropdown-item w-100 text-start border-0 bg-transparent">Cerrar sesión</button>
                                    </form>
                                </li>
                            @else
                                <li><a class="perfil-dropdown-item" href="{{ route('login') }}">Iniciar sesión</a></li>
                                <li><a class="perfil-dropdown-item" href="{{ route('registro') }}">Registrarse</a></li>
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
    <div class="container cuenta-container mt-5 pt-5">
        <div class="row">

            <div class="col-md-6 col-lg-5">
                <h2 class="cuenta-titulo">Mi Información Personal</h2>

                <a href="{{ route('perfil') }}" class="text-decoration-none text-dark">
                    <div class="cuenta-card active">
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
                    <div class="cuenta-card">
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

            <div class="col-md-6 col-lg-7">
                <div class="formulario-contenedor">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ url('perfil/actualizar') }}" method="POST" id="formCuenta">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                       value="{{ old('nombre', $perfil['nombre'] ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="apellido" name="apellido"
                                       value="{{ old('apellido', $perfil['apellido'] ?? '') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="correo_electronico" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico"
                                   value="{{ old('correo_electronico', $perfil['correo_electronico'] ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="telefono" name="telefono"
                            value="{{ old('telefono', $perfil['telefono'] ?? '') }}"
                            pattern="[0-9]{10}"
                            minlength="10"
                            maxlength="10"
                            required>
                        <div class="invalid-feedback">
                            El número de teléfono debe tener exactamente 10 dígitos.
                        </div>
                                   
                        </div>

                        <!-- Departamento y Municipio — van después del bloque nombre/apellido -->
                        <div class="mb-3">
                            <label for="selectDepartamento" class="form-label">Departamento <span class="text-danger">*</span></label>
                            <select class="form-select" id="selectDepartamento" name="departamento" required>
                                <option value="">-- Selecciona un departamento --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="selectMunicipio" class="form-label">Municipio / Ciudad <span class="text-danger">*</span></label>
                            <select class="form-select" id="selectMunicipio" name="municipio" required>
                                <option value="">-- Selecciona un municipio --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="3" required>{{ old('direccion', $perfil['direccion'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena"
                                   placeholder="Dejar en blanco si no desea cambiarla">
                            <small class="form-text text-muted">Solo complete este campo si desea cambiar su contraseña</small>
                        </div>

                        <div class="mb-4">
                            <label for="contrasena_confirmacion" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="contrasena_confirmacion" name="contrasena_confirmacion"
                                   placeholder="Confirme su nueva contraseña">
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terminos" name="terminos" required>
                            <label class="form-check-label" for="terminos">
                                Acepto los <span class="terminos-link">Términos y Condiciones</span> <span class="text-danger">*</span>
                            </label>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ url('cuenta') }}" class="btn btn-cancelar">Cancelar</a>
                            <button type="submit" class="btn btn-guardar">Guardar Cambios</button>
                        </div>

                    </form>
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
    <script src="{{ asset('js/PuntoInicio/Cliente/Perfil.js') }}"></script>
    <script src="{{ asset('js/Registrarse/ubicacion.js') }}"></script>
    <script>
        initSelectUbicacion(
            "{{ $perfil['departamento'] ?? '' }}",
            "{{ $perfil['municipio'] ?? '' }}"
        );
    </script>
    
</body>
</html>