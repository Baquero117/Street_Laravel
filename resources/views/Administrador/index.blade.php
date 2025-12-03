<?php

$opcion = $_GET['opcion'] ?? 'inicio';

$contenido    = "";
$clientes     = [];
$vendedores   = [];
$productos    = [];
$detalles     = [];  
$pedidos      = [];
$promociones  = [];
$valoraciones = [];
$categorias   = [];  
$mensaje      = "";

switch ($opcion) {
    case 'cliente':
        require_once __DIR__ . '/../../Controlador/Administrador/ClienteController.php';
        $controller = new ClienteController();
        $response   = $controller->manejarPeticion();

        $clientes = $response['clientes'] ?? [];
        $mensaje  = $response['mensaje'] ?? "";

        $contenido = __DIR__ . '/../../Vista/Administrador/Cliente.php';
        break;

    case 'vendedor':
        require_once __DIR__ . '/../../Controlador/Administrador/VendedorController.php';
        $controller = new VendedorController();
        $response   = $controller->manejarPeticion();

        $vendedores = $response['vendedores'] ?? [];
        $mensaje    = $response['mensaje'] ?? "";

        $contenido = __DIR__ . '/../../Vista/Administrador/Vendedor.php';
        break;

    case 'producto':
        require_once __DIR__ . '/../../Controlador/Administrador/ProductoController.php';
        $controller = new ProductoController();
        $response   = $controller->manejarPeticion();

        $productos = $response['productos'] ?? [];
        $mensaje   = $response['mensaje'] ?? "";

        $contenido = __DIR__ . '/../../Vista/Administrador/Producto.php';
        break;

    case 'detalle_producto':
        require_once __DIR__ . '/../../Controlador/Administrador/Detalle_ProductoController.php';
        $controller = new DetalleProductoController();
        $response   = $controller->manejarPeticion();
        
        $detalles = $response['detalles'] ?? [];
        $mensaje  = $response['mensaje'] ?? "";

        $contenido = __DIR__ . '/../../Vista/Administrador/Detalle_Producto.php';
        break;

    case 'pedido':
        require_once __DIR__ . '/../../Controlador/Administrador/PedidoController.php';
        $controller = new PedidoController();
        $response   = $controller->manejarPeticion();

        $pedidos = $response['pedidos'] ?? [];
        $mensaje = $response['mensaje'] ?? "";

        $contenido = __DIR__ . '/../../Vista/Administrador/Pedido.php';
        break;

    case 'promocion':
        require_once __DIR__ . '/../../Controlador/Administrador/PromocionController.php';
        $controller = new PromocionController();
        $response   = $controller->manejarPeticion();

        $promociones = $response['promociones'] ?? [];
        $mensaje     = $response['mensaje'] ?? "";

        $contenido = __DIR__ . '/../../Vista/Administrador/Promocion.php';
        break;

    case 'valoracion':
        require_once __DIR__ . '/../../Controlador/Administrador/ValoracionController.php';
        $controller = new ValoracionController();
        $response   = $controller->manejarPeticion();

        $valoraciones = $response['valoraciones'] ?? [];
        $mensaje      = $response['mensaje'] ?? "";

        $contenido = __DIR__ . '/../../Vista/Administrador/Valoracion.php';
        break;

    case 'categoria':   
        require_once __DIR__ . '/../../Controlador/Administrador/CategoriaController.php';
        $controller = new CategoriaController();
        $response   = $controller->manejarPeticion();

        $categorias = $response['categorias'] ?? [];
        $mensaje    = $response['mensaje'] ?? "";

        $contenido = __DIR__ . '/../../Vista/Administrador/Categoria.php';
        break;
        
    default:
        $contenido = __DIR__ . '/../../Vista/Administrador/Inicio.php';
        break;
}


return view('Administrador.InicioAdmin.InicioAdmin');


