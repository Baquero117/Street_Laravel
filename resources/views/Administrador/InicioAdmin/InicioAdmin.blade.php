<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Street Admin</title>

    {{-- CSS propio --}}
   <link rel="stylesheet" href="{{ asset('css/Administrador/InicioAdmin.css') }}">



    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    {{-- ===== SIDEBAR ===== --}}
    <div class="sidebar" id="sidebar">

        <div class="header">
            <img src="{{ asset('img/logoStreet.jpg') }}" alt="Logo" class="logo">
            <h3><span class="title-text">Street Admin</span></h3>
        </div>

        <ul class="menu">
            <li><a href="{{ route('admin.inicio') }}"><i class="fas fa-home"></i><span> Inicio</span></a></li>
            <li><a href="{{ route('admin.Cliente') }}"><i class="fas fa-user"></i><span> Cliente</span></a></li>
            <li><a href="{{ route('admin.Vendedor') }}"><i class="fas fa-user-tie"></i><span> Vendedor</span></a></li>
            <li><a href="{{ route('admin.Pedido') }}"><i class="fas fa-shopping-cart"></i><span> Pedido</span></a></li>
            <li><a href="{{ route('admin.Producto') }}"><i class="fas fa-box"></i><span> Producto</span></a></li>
            <li><a href="{{ route('admin.DetalleProducto') }}"><i class="fas fa-tags"></i><span> Detalle Producto</span></a></li>
            <li><a href="{{ route('admin.Categoria') }}"><i class="fas fa-layer-group"></i><span> Categoría</span></a></li>
            <li><a href="{{ route('admin.Promocion') }}"><i class="fas fa-percent"></i><span> Promocion</span></a></li>
            

        <div class="footer">
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout">
                    <i class="fas fa-sign-out-alt"></i><span> Cerrar sesión</span>
                </button>
            </form>

            <label class="toggle" for="darkmode">
                <i class="fas fa-moon"></i><span> Modo Oscuro</span>
                <input type="checkbox" id="darkmode">
            </label>

            <button id="collapse" aria-label="Colapsar sidebar">
                <i class="fas fa-angle-double-left"></i>
                <span> Colapsar</span>
            </button>

        </div>
    </div>

    {{-- ===== CONTENIDO ===== --}}
    <div class="content p-4">
        @yield('contenido')
    </div>

    
 <script src="{{ asset('js/Administrador/InicioAdmin.js') }}"></script>

  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
