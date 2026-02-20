<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/Recuperacion/Recuperacion.css') }}">

    <title>Recuperar Contraseña</title>
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-lg p-5 rounded-4" style="max-width: 500px; width:100%;">

        <div class="text-center mb-4">
            <i class="bi bi-shield-lock fs-1 text-primary"></i>
            <h2 class="mt-2 fw-bold text-dark">Recuperar Contraseña</h2>
            <p class="text-muted">Ingresa tu correo y te enviaremos un enlace</p>
        </div>

        @if(session('mensaje'))
            <div class="alert alert-success text-center p-2">
                <i class="bi bi-check-circle me-1"></i> {{ session('mensaje') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger text-center p-2">
                <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('recuperacion.procesar-solicitud') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="form-label" for="correo_electronico">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" id="correo_electronico" name="correo_electronico"
                        class="form-control" placeholder="ejemplo@correo.com" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-3 mb-3">
                <i class="bi bi-send me-2"></i>Enviar enlace de recuperación
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-primary">
                <i class="bi bi-arrow-left"></i> Volver al inicio de sesión
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>