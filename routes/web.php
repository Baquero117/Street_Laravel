<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Administrador\CategoriaController;
use App\Http\Controllers\Administrador\ClienteController;

Route::get('/login', [LoginController::class, 'mostrar'])->name('login');
Route::post('/login', [LoginController::class, 'procesar'])->name('login.procesar');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Inicio del administrador
Route::get('/admin', function () {
    return view('Administrador.InicioAdmin.InicioAdmin');
})->name('admin.inicio');


// Secciones del admin
Route::get('/admin/cliente', fn() => view('Administrador.Cliente'))->name('admin.Cliente');
Route::get('/admin/vendedores', fn() => view('Administrador.Vendedores.index'))->name('admin.vendedores');
Route::get('/admin/pedidos', fn() => view('Administrador.Pedidos.index'))->name('admin.pedidos');
Route::get('/admin/productos', fn() => view('Administrador.Productos.index'))->name('admin.productos');
Route::get('/admin/detalle-producto', fn() => view('Administrador.DetalleProducto.index'))->name('admin.detalle_producto');
Route::get('/admin/Categoria', fn() => view('Administrador.Categoria'))->name('admin.Categoria');
Route::get('/admin/promociones', fn() => view('Administrador.Promociones.index'))->name('admin.promociones');
Route::get('/admin/valoraciones', fn() => view('Administrador.Valoraciones.index'))->name('admin.valoraciones');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/categoria', [CategoriaController::class, 'index'])->name('categoria.index');
Route::post('/categoria/agregar', [CategoriaController::class, 'store'])->name('categoria.agregar');
Route::post('/categoria/actualizar', [CategoriaController::class, 'update'])->name('categoria.actualizar');
Route::post('/categoria/eliminar', [CategoriaController::class, 'destroy'])->name('categoria.eliminar');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/cliente', [ClienteController::class, 'index'])->name('cliente.index');
Route::post('/cliente/agregar', [ClienteController::class, 'store'])->name('cliente.agregar');
Route::post('/cliente/actualizar', [ClienteController::class, 'update'])->name('cliente.actualizar');
Route::post('/cliente/eliminar', [ClienteController::class, 'destroy'])->name('cliente.eliminar');
