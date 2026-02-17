<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Registro\RegistroController;
use App\Http\Controllers\Administrador\CategoriaController;
use App\Http\Controllers\Administrador\ClienteController;
use App\Http\Controllers\Administrador\DetalleProductoController;
use App\Http\Controllers\Administrador\ProductoController;
use App\Http\Controllers\Administrador\PedidoController;
use App\Http\Controllers\Administrador\PromocionController;
use App\Http\Controllers\Administrador\VendedorController;
use App\Http\Controllers\Carrito\CarritoController;
use App\Http\Controllers\MasVistas\HombreController;
use App\Http\Controllers\MasVistas\MujerController;
use App\Http\Controllers\MasVistas\ModaController;
use App\Http\Controllers\PuntoInicio\PerfilController;
use App\Http\Controllers\PublicoController;
use App\Http\Controllers\Administrador\ReporteController;

Route::get('/admin/Reportes', [ReporteController::class, 'index'])
    ->name('admin.Reportes');

Route::get('/login', [LoginController::class, 'mostrar'])->name('login');
Route::post('/login', [LoginController::class, 'procesar'])->name('login.procesar');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Registro de usuario
Route::get('/registro', [RegistroController::class, 'mostrar'])->name('registro');
Route::post('/registro', [RegistroController::class, 'procesar'])->name('registro.procesar');

Route::get('/inicio', [PublicoController::class, 'index'])->name('inicio');
Route::get('/productos/{id}/detalle', [PublicoController::class, 'detalle'])->name('productos.detalle');


// Perfil de usuario
Route::get('/cuenta', [PerfilController::class, 'mostrarCuenta'])->name('cuenta');
Route::get('/perfil', [PerfilController::class, 'mostrar'])->name('perfil');
Route::post('/perfil/actualizar', [PerfilController::class, 'actualizar'])->name('perfil.actualizar');

// Vista ropa hombre
Route::get('/hombre', [HombreController::class, 'index'])->name('hombre');

// Vista ropa Mujer
Route::get('/mujer', [MujerController::class, 'index'])->name('mujer');

// Vista ropa de moda
Route::get('/moda', [ModaController::class, 'index'])->name('moda');

// Inicio del administrador
Route::get('/admin', function () {
    return view('Administrador.InicioAdmin.InicioAdmin');
})->name('admin.inicio');

Route::get('/admin/Categoria', [CategoriaController::class, 'index'])->name('admin.Categoria');
Route::get('/categoria', [CategoriaController::class, 'index'])->name('categoria.index');
Route::post('/categoria/agregar', [CategoriaController::class, 'store'])->name('categoria.agregar');
Route::post('/categoria/actualizar', [CategoriaController::class, 'update'])->name('categoria.actualizar');
Route::post('/categoria/eliminar', [CategoriaController::class, 'destroy'])->name('categoria.eliminar');

Route::get('/admin/Cliente', [ClienteController::class, 'index'])->name('admin.Cliente');
Route::get('/cliente', [ClienteController::class, 'index'])->name('cliente.index');
Route::post('/cliente/agregar', [ClienteController::class, 'store'])->name('cliente.agregar');
Route::post('/cliente/actualizar', [ClienteController::class, 'update'])->name('cliente.actualizar');
Route::post('/cliente/eliminar', [ClienteController::class, 'destroy'])->name('cliente.eliminar');

Route::get('/admin/DetalleProducto', [DetalleProductoController::class, 'index'])->name('admin.DetalleProducto');
Route::get('/detalleproducto', [DetalleProductoController::class, 'index'])->name('detalle.index');
Route::post('/detalle/agregar', [DetalleProductoController::class, 'store'])->name('detalle.agregar');
Route::post('/detalle/actualizar', [DetalleProductoController::class, 'update'])->name('detalle.actualizar');
Route::post('/detalle/eliminar', [DetalleProductoController::class, 'destroy'])->name('detalle.eliminar');

Route::get('/admin/Producto', [ProductoController::class, 'index'])->name('admin.Producto');
Route::get('/producto', [ProductoController::class, 'index'])->name('producto.index');
Route::post('/producto/agregar', [ProductoController::class, 'store'])->name('producto.agregar');
Route::post('/producto/actualizar', [ProductoController::class, 'update'])->name('producto.actualizar');
Route::post('/producto/eliminar', [ProductoController::class, 'destroy'])->name('producto.eliminar');



Route::get('/admin/Pedido', [PedidoController::class, 'index'])->name('admin.Pedido');
Route::get('/pedido', [PedidoController::class, 'index'])->name('pedido.index');

Route::post('/pedido/agregar', [PedidoController::class, 'store'])->name('pedido.agregar');
Route::post('/pedido/actualizar', [PedidoController::class, 'update'])->name('pedido.actualizar');
Route::post('/pedido/eliminar', [PedidoController::class, 'destroy'])->name('pedido.eliminar');
// Cambiar estado desde el select
Route::post('/pedido/cambiar-estado', 
    [PedidoController::class, 'cambiarEstado']
)->name('pedido.cambiarEstado');

// Cancelar pedido (estado = Cancelado)
Route::post('/pedido/cancelar', 
    [PedidoController::class, 'cancelar']
)->name('pedido.cancelar');


Route::get('/admin/Vendedor', [VendedorController::class, 'index'])->name('admin.Vendedor');
Route::get('/vendedor', [VendedorController::class, 'index'])->name('vendedor.index');
Route::post('/vendedor/agregar', [VendedorController::class, 'store'])->name('vendedor.agregar');
Route::post('/vendedor/actualizar', [VendedorController::class, 'update'])->name('vendedor.actualizar');
Route::post('/vendedor/eliminar', [VendedorController::class, 'destroy'])->name('vendedor.eliminar');


Route::get('/admin/Promocion', [PromocionController::class, 'index'])->name('admin.Promocion');
Route::get('/promocion', [PromocionController::class, 'index'])->name('promocion.index');
Route::post('/promocion/agregar', [PromocionController::class, 'store'])->name('promocion.agregar');
Route::post('/promocion/actualizar', [PromocionController::class, 'update'])->name('promocion.actualizar');
Route::post('/promocion/eliminar', [PromocionController::class, 'destroy'])->name('promocion.eliminar');