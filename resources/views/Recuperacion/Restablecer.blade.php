<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/Recuperacion/Recuperacion.css') }}">

    <title>Restablecer Contraseña</title>
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-lg p-5 rounded-4" style="max-width: 500px; width:100%;">

        <div class="text-center mb-4">
            <i class="bi bi-key fs-1 text-primary"></i>
            <h2 class="mt-2 fw-bold text-dark">Nueva Contraseña</h2>
            <p class="text-muted">Ingresa y confirma tu nueva contraseña</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-center p-2">
                <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger p-2">
                @foreach($errors->all() as $error)
                    <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('recuperacion.procesar-restablecimiento') }}" method="POST">
            @csrf

            {{-- Token oculto que viene en la URL --}}
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label" for="contrasena">Nueva Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" id="contrasena" name="contrasena"
                        class="form-control" placeholder="* * * * * * * *" required minlength="8">
                    <span class="input-group-text" id="toggleContrasena" style="cursor:pointer;">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label" for="contrasena_confirm">Confirmar Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" id="contrasena_confirm" name="contrasena_confirm"
                        class="form-control" placeholder="* * * * * * * *" required minlength="8">
                    <span class="input-group-text" id="toggleConfirm" style="cursor:pointer;">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-3 mb-3">
                <i class="bi bi-check2-circle me-2"></i>Restablecer Contraseña
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-primary">
                <i class="bi bi-arrow-left"></i> Volver al inicio de sesión
            </a>
        </div>

    </div>

    <script src="{{ asset('js/Recuperacion/Recuperacion.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>