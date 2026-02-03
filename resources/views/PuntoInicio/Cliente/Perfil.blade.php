<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Urban Street</title>
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

    <div class="container cuenta-container mt-5 pt-5">

        <div class="row">

            <div class="col-md-6 col-lg-5">

                <h2 class="cuenta-titulo">
                    Mi Información Personal
                </h2>

                <a href="#" class="text-decoration-none text-dark">
                    <div class="cuenta-card">
                        <div class="cuenta-card-title">Pedidos</div>
                        <div class="cuenta-card-sub">Historial de pedidos</div>
                    </div>
                </a>

                <a href="{{ url('cuenta') }}" class="text-decoration-none text-dark">
                    <div class="cuenta-card active">
                        <div class="cuenta-card-title">Cuenta</div>
                        <div class="cuenta-card-sub">Mi información personal</div>
                    </div>
                </a>

                <a href="{{ url('inicio') }}" class="text-decoration-none text-dark">
                    <div class="cuenta-card">
                        <div class="cuenta-card-title">Inicio</div>
                        <div class="cuenta-card-sub">Volver a la pagina principal</div>
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
                                   value="{{ old('telefono', $perfil['telefono'] ?? '') }}" required>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/PuntoInicio/Cliente/Cuenta.js') }}"></script>

</body>
</html>