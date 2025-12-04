<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Administrador\CategoriaController;
use App\Http\Controllers\Administrador\ClienteController;
use App\Http\Controllers\Administrador\DetalleProductoController;
use App\Http\Controllers\Administrador\ProductoController;
use App\Http\Controllers\Administrador\PedidoController;

Route::get('/login', [LoginController::class, 'mostrar'])->name('login');
Route::post('/login', [LoginController::class, 'procesar'])->name('login.procesar');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Inicio del administrador
Route::get('/admin', function () {
    return view('Administrador.InicioAdmin.InicioAdmin');
})->name('admin.inicio');


// Secciones del admin

Route::get('/admin/vendedores', fn() => view('Administrador.Vendedores.index'))->name('admin.vendedores');

Route::get('/admin/promociones', fn() => view('Administrador.Promociones.index'))->name('admin.promociones');
Route::get('/admin/valoraciones', fn() => view('Administrador.Valoraciones.index'))->name('admin.valoraciones');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/admin/Categoria', fn() => view('Administrador.Categoria'))->name('admin.Categoria');
Route::get('/admin/Categoria', [CategoriaController::class, 'index'])->name('admin.Categoria');


Route::get('/categoria', [CategoriaController::class, 'index'])->name('categoria.index');
Route::post('/categoria/agregar', [CategoriaController::class, 'store'])->name('categoria.agregar');
Route::post('/categoria/actualizar', [CategoriaController::class, 'update'])->name('categoria.actualizar');
Route::post('/categoria/eliminar', [CategoriaController::class, 'destroy'])->name('categoria.eliminar');



Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/admin/Cliente', fn() => view('Administrador.Cliente'))->name('admin.Cliente');
Route::get('/admin/Cliente', [ClienteController::class, 'index'])->name('admin.Cliente');


Route::get('/cliente', [ClienteController::class, 'index'])->name('cliente.index');
Route::post('/cliente/agregar', [ClienteController::class, 'store'])->name('cliente.agregar');
Route::post('/cliente/actualizar', [ClienteController::class, 'update'])->name('cliente.actualizar');
Route::post('/cliente/eliminar', [ClienteController::class, 'destroy'])->name('cliente.eliminar');


Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/admin/DetalleProducto', fn() => view('Administrador.DetalleProducto'))->name('admin.DetalleProducto');
Route::get('/admin/DetalleProducto', [DetalleProductoController::class, 'index'])->name('admin.DetalleProducto');


Route::get('/detalleproducto', [DetalleProductoController::class, 'index'])->name('detalle.index');
Route::post('/detalle/agregar', [DetalleProductoController::class, 'store'])->name('detalle.agregar');
Route::post('/detalle/actualizar', [DetalleProductoController::class, 'update'])->name('detalle.actualizar');
Route::post('/detalle/eliminar', [DetalleProductoController::class, 'destroy'])->name('detalle.eliminar');



Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/admin/Producto', fn() => view('Administrador.Producto'))->name('admin.Producto');
Route::get('/admin/Producto', [ProductoController::class, 'index'])->name('admin.Producto');

Route::get('/producto', [ProductoController::class, 'index']) ->name('producto.index');
Route::post('/producto/agregar', [ProductoController::class, 'store'])->name('producto.agregar');
Route::post('/producto/actualizar', [ProductoController::class, 'update'])->name('producto.actualizar');
Route::post('/producto/eliminar', [ProductoController::class, 'destroy'])->name('producto.eliminar');



Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/admin/Pedido', fn() => view('Administrador.Pedido'))->name('admin.Pedido');
Route::get('/admin/Pedido', [PedidoController::class, 'index'])->name('admin.Pedido');


Route::get('/pedido', [PedidoController::class, 'index'])->name('pedido.index');
Route::post('/pedido/agregar', [PedidoController::class, 'store'])->name('pedido.agregar');
Route::post('/pedido/actualizar', [PedidoController::class, 'update'])->name('pedido.actualizar');
Route::post('/pedido/eliminar', [PedidoController::class, 'destroy'])->name('pedido.eliminar');