<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/Registrarse/Registro.css') }}">

    <title>Registro</title>
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-lg p-5 rounded-4" style="max-width: 500px; width:100%;">

        <div class="text-center mb-4">
            <i class="bi bi-person-plus fs-1 text-primary"></i>
            <h2 class="mt-2 fw-bold text-dark">Crear Cuenta</h2>
            <p class="text-muted">Regístrate para continuar</p>
        </div>

        <form action="{{ route('registro.procesar') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="nombre">Nombre</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Tu nombre" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="apellido">Apellido</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                    <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Tu apellido" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="direccion">Dirección</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                    <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Dirección de residencia" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="telefono">Teléfono</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    <input type="number" id="telefono" name="telefono" class="form-control" placeholder="Número de teléfono" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="correo_electronico">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" id="correo_electronico" name="correo_electronico" class="form-control" placeholder="ejemplo@correo.com" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="contrasena">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="* * * * * * * *" required>
                    <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-3 mb-3">
                <i class="bi bi-check2-circle me-2"></i>Registrarme
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-primary">
                <i class="bi bi-box-arrow-in-right"></i> Ya tengo una cuenta
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-center mt-3 p-2">
                {{ session('error') }}
            </div>
        @endif

    </div>

    <script src="{{ asset('js/Registrarse/Registro.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
