<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/Registrarse/Verificacion.css') }}">

    <title>Verificar Cuenta</title>
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-lg p-5 rounded-4" style="max-width: 500px; width:100%;">

        <div class="text-center mb-4">
            <i class="bi bi-envelope-check fs-1 text-primary"></i>
            <h2 class="mt-2 fw-bold text-dark">Verifica tu cuenta</h2>
            <p class="text-muted">Ingresa el código de 6 dígitos que enviamos a</p>
            <p class="fw-bold text-dark">{{ $correo }}</p>
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

        <form action="{{ route('verificacion.validar') }}" method="POST" id="verificacionForm">
            @csrf

            {{-- Input oculto que junta los 6 dígitos --}}
            <input type="hidden" name="codigo" id="codigoCompleto">

            {{-- 6 inputs visuales separados --}}
            <div class="d-flex justify-content-center gap-2 mb-4" id="codigoInputs">
                <input type="text" maxlength="1" class="codigo-input form-control text-center fw-bold fs-4" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="codigo-input form-control text-center fw-bold fs-4" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="codigo-input form-control text-center fw-bold fs-4" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="codigo-input form-control text-center fw-bold fs-4" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="codigo-input form-control text-center fw-bold fs-4" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="codigo-input form-control text-center fw-bold fs-4" inputmode="numeric" pattern="[0-9]">
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-3 mb-3" id="verificarBtn" disabled>
                <i class="bi bi-check2-circle me-2"></i>Verificar cuenta
            </button>
        </form>

        {{-- Reenviar código --}}
        <div class="text-center mb-3">
            <span class="text-muted">¿No recibiste el código?</span>
            <form action="{{ route('verificacion.reenviar') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link p-0 ms-1 fw-bold text-primary text-decoration-none">
                    Reenviar código
                </button>
            </form>
        </div>

        <div class="text-center">
            <a href="{{ route('registro') }}" class="text-decoration-none fw-bold text-primary">
                <i class="bi bi-arrow-left"></i> Volver al registro
            </a>
        </div>

    </div>

    <script src="{{ asset('js/Registrarse/Verificacion.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>