<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Registro\RegistroController;
use App\Http\Controllers\Administrador\CategoriaController;
use App\Http\Controllers\Administrador\ClienteController;
use App\Http\Controllers\Administrador\DetalleProductoController;
use App\Http\Controllers\Administrador\ProductoController;
use App\Http\Controllers\Administrador\PedidoController;
use App\Http\Controllers\Administrador\VendedorController;
use App\Http\Controllers\PuntoInicio\PerfilController;

Route::get('/login', [LoginController::class, 'mostrar'])->name('login');
Route::post('/login', [LoginController::class, 'procesar'])->name('login.procesar');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Fachada y Inicio
Route::get('/fachada', function () {
    return view('Fachada.Fachada');
})->name('fachada');

Route::get('/inicio', function () {
    return view('PuntoInicio.Inicio');
})->name('inicio');


//Registro
Route::get('/registro', [RegistroController::class, 'mostrar'])->name('registro');
Route::post('/registro', [RegistroController::class, 'procesar'])->name('registro.procesar');


// Administrador Inicio
Route::get('/admin', function () {
    return view('Administrador.InicioAdmin.InicioAdmin');
})->name('admin.inicio');

//Perfil y Cuenta
Route::get('/cuenta', [PerfilController::class, 'mostrarCuenta'])->name('cuenta');
Route::get('/perfil', [PerfilController::class, 'mostrar'])->name('perfil');
Route::post('/perfil/actualizar', [PerfilController::class, 'actualizar'])->name('perfil.actualizar');

Route::get('/carrito', function () {
    return view('CarritoCompras.Carrito');
})->name('carrito');

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

Route::get('/admin/Vendedor', [VendedorController::class, 'index'])->name('admin.Vendedor');
Route::get('/vendedor', [VendedorController::class, 'index'])->name('vendedor.index');
Route::post('/vendedor/agregar', [VendedorController::class, 'store'])->name('vendedor.agregar');
Route::post('/vendedor/actualizar', [VendedorController::class, 'update'])->name('vendedor.actualizar');
Route::post('/vendedor/eliminar', [VendedorController::class, 'destroy'])->name('vendedor.eliminar');
