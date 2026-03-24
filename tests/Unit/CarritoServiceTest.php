<?php

namespace Tests\Unit;

use App\Models\Carrito\CarritoService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CarritoServiceTest extends TestCase
{
    private CarritoService $carritoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->carritoService = new CarritoService();
    }

    // obtenerToken - Retorna token válido
    public function test_obtenerToken_retorna_token_valido()
    {
        Session::put('token', 'token_123');
        $reflection = new \ReflectionClass($this->carritoService);
        $method = $reflection->getMethod('obtenerToken');
        $method->setAccessible(true);
        $resultado = $method->invoke($this->carritoService);
        $this->assertEquals('token_123', $resultado);
    }
    // php artisan test --filter=test_obtenerToken_retorna_token_valido

    public function test_obtenerToken_retorna_null_sin_sesion()
    {
        Session::flush();
        $reflection = new \ReflectionClass($this->carritoService);
        $method = $reflection->getMethod('obtenerToken');
        $method->setAccessible(true);
        $resultado = $method->invoke($this->carritoService);
        $this->assertNull($resultado);
    }
    // php artisan test --filter=test_obtenerToken_retorna_null_sin_sesion

    // obtenerHeaders - Retorna headers correctos
    public function test_obtenerHeaders_retorna_headers_correctos()
    {
        Session::put('token', 'bearer_123');
        $reflection = new \ReflectionClass($this->carritoService);
        $method = $reflection->getMethod('obtenerHeaders');
        $method->setAccessible(true);
        $resultado = $method->invoke($this->carritoService);
        $this->assertIsArray($resultado);
        $this->assertEquals('Bearer bearer_123', $resultado['Authorization']);
    }
    // php artisan test --filter=test_obtenerHeaders_retorna_headers_correctos

    public function test_obtenerHeaders_retorna_null_sin_token()
    {
        Session::flush();
        $reflection = new \ReflectionClass($this->carritoService);
        $method = $reflection->getMethod('obtenerHeaders');
        $method->setAccessible(true);
        $resultado = $method->invoke($this->carritoService);
        $this->assertNull($resultado);
    }
    // php artisan test --filter=test_obtenerHeaders_retorna_null_sin_token

    // obtenerCarrito - Exitoso
    public function test_obtenerCarrito_exitoso()
    {
        Session::put('token', 'token_valido');
        Http::fake(['http://34.225.197.89:8080/carrito/mis-productos' => Http::response(['items' => [['id' => 1, 'nombre' => 'Producto 1']], 'total' => 150.00, 'cantidad_items' => 1], 200)]);
        $resultado = $this->carritoService->obtenerCarrito();
        $this->assertEquals(150.00, $resultado['total']);
    }
    // php artisan test --filter=test_obtenerCarrito_exitoso

    public function test_obtenerCarrito_sin_token()
    {
        Session::flush();
        $resultado = $this->carritoService->obtenerCarrito();
        $this->assertEquals(['items' => [], 'total' => 0, 'cantidad_items' => 0], $resultado);
    }
    // php artisan test --filter=test_obtenerCarrito_sin_token

    public function test_obtenerCarrito_error_en_api()
    {
        Session::put('token', 'token_valido');
        Http::fake(['http://34.225.197.89:8080/carrito/mis-productos' => Http::response(['error' => 'No autorizado'], 401)]);
        $resultado = $this->carritoService->obtenerCarrito();
        $this->assertEquals(['items' => [], 'total' => 0, 'cantidad_items' => 0], $resultado);
    }
    // php artisan test --filter=test_obtenerCarrito_error_en_api

    // agregarProducto - Exitoso
    public function test_agregarProducto_exitoso()
    {
        Session::put('token', 'token_valido');
        Http::fake(['http://34.225.197.89:8080/carrito/agregar' => Http::response(['success' => true, 'mensaje' => 'Producto agregado'], 200)]);
        $resultado = $this->carritoService->agregarProducto(['id_detalle_producto' => 5, 'talla' => 'M', 'cantidad' => 2, 'precio' => 99.99]);
        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=test_agregarProducto_exitoso

    public function test_agregarProducto_sin_autenticacion()
    {
        Session::flush();
        $resultado = $this->carritoService->agregarProducto(['id_detalle_producto' => 5, 'talla' => 'M', 'cantidad' => 2, 'precio' => 99.99]);
        $this->assertFalse($resultado['success']);
    }
    // php artisan test --filter=test_agregarProducto_sin_autenticacion

    public function test_agregarProducto_stock_insuficiente()
    {
        Session::put('token', 'token_valido');
        Http::fake(['http://34.225.197.89:8080/carrito/agregar' => Http::response(['success' => false, 'resultado' => -1], 400)]);
        $resultado = $this->carritoService->agregarProducto(['id_detalle_producto' => 5, 'talla' => 'M', 'cantidad' => 100, 'precio' => 99.99]);
        $this->assertFalse($resultado['success']);
    }
    // php artisan test --filter=test_agregarProducto_stock_insuficiente

    // actualizarCantidad - Exitoso
    public function test_actualizarCantidad_exitoso()
    {
        Session::put('token', 'token_valido');
        Http::fake(['http://34.225.197.89:8080/carrito/actualizar' => Http::response(['success' => true], 200)]);
        $resultado = $this->carritoService->actualizarCantidad(1, 5);
        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=test_actualizarCantidad_exitoso

    public function test_actualizarCantidad_stock_insuficiente()
    {
        Session::put('token', 'token_valido');
        Http::fake(['http://34.225.197.89:8080/carrito/actualizar' => Http::response(['resultado' => -1], 400)]);
        $resultado = $this->carritoService->actualizarCantidad(1, 100);
        $this->assertFalse($resultado['success']);
    }
    // php artisan test --filter=test_actualizarCantidad_stock_insuficiente

    public function test_actualizarCantidad_sin_autenticacion()
    {
        Session::flush();
        $resultado = $this->carritoService->actualizarCantidad(1, 5);
        $this->assertFalse($resultado['success']);
    }
    // php artisan test --filter=test_actualizarCantidad_sin_autenticacion

    // eliminarItem - Exitoso
    public function test_eliminarItem_exitoso()
    {
        Session::put('token', 'token_valido');
        Http::fake(['http://34.225.197.89:8080/carrito/eliminar/1' => Http::response(['success' => true], 200)]);
        $resultado = $this->carritoService->eliminarItem(1);
        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=test_eliminarItem_exitoso

    public function test_eliminarItem_sin_autenticacion()
    {
        Session::flush();
        $resultado = $this->carritoService->eliminarItem(1);
        $this->assertFalse($resultado['success']);
    }
    // php artisan test --filter=test_eliminarItem_sin_autenticacion

    public function test_eliminarItem_error()
    {
        Session::put('token', 'token_valido');
        Http::fake(['http://34.225.197.89:8080/carrito/eliminar/999' => Http::response(['error' => 'No encontrado'], 404)]);
        $resultado = $this->carritoService->eliminarItem(999);
        $this->assertFalse($resultado['success']);
    }
    // php artisan test --filter=test_eliminarItem_error

    // obtenerContador - Exitoso
    public function test_obtenerContador_exitoso()
    {
        Session::put('token', 'token_valido');
        Http::fake(['http://34.225.197.89:8080/carrito/contador' => Http::response(['cantidad' => 5], 200)]);
        $resultado = $this->carritoService->obtenerContador();
        $this->assertEquals(5, $resultado['cantidad']);
    }
    // php artisan test --filter=test_obtenerContador_exitoso

    public function test_obtenerContador_sin_autenticacion()
    {
        Session::flush();
        $resultado = $this->carritoService->obtenerContador();
        $this->assertEquals(0, $resultado['cantidad']);
    }
    // php artisan test --filter=test_obtenerContador_sin_autenticacion

    // vaciarCarrito - Exitoso
    public function test_vaciarCarrito_exitoso()
    {
        Session::put('token', 'token_valido');
        Http::fake(['http://34.225.197.89:8080/carrito/vaciar' => Http::response(['success' => true], 200)]);
        $resultado = $this->carritoService->vaciarCarrito();
        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=test_vaciarCarrito_exitoso

    public function test_vaciarCarrito_sin_autenticacion()
    {
        Session::flush();
        $resultado = $this->carritoService->vaciarCarrito();
        $this->assertFalse($resultado['success']);
    }
    // php artisan test --filter=test_vaciarCarrito_sin_autenticacion
}

/*
php artisan test tests/Unit/CarritoServiceTest.php
*/