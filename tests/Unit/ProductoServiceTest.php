<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\Administrador\ProductoService;

class ProductoServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        session(['token' => '123']);
    }

    /* ==========================
       🔹 OBTENER PRODUCTOS
    ========================== */

    public function test_obtener_productos_success()
    {
        Http::fake([
            'http://localhost:8080/producto' => Http::response([
                ['id' => 1, 'nombre' => 'Zapato']
            ], 200)
        ]);

        $service = new ProductoService();
        $resultado = $service->obtenerProductos();

        $this->assertCount(1, $resultado);
        $this->assertEquals('Zapato', $resultado[0]['nombre']);
    }

    /* ==========================
       🔹 AGREGAR PRODUCTO
    ========================== */

    public function test_agregar_producto_success()
    {
        Http::fake([
            'http://localhost:8080/producto' => Http::response([
                'id' => 1,
                'nombre' => 'Zapato'
            ], 200)
        ]);

        $service = new ProductoService();

        $resultado = $service->agregarProducto(
            'Zapato',
            'Deportivo',
            10,
            'imagen.jpg',
            1,
            'Activo',
            100000,
            'Negro',
            2
        );

        $this->assertTrue($resultado['success']);
        $this->assertEquals('Zapato', $resultado['data']['nombre']);
    }

    /* ==========================
       🔹 ACTUALIZAR PRODUCTO
    ========================== */

    public function test_actualizar_producto_success()
    {
        Http::fake([
            'http://localhost:8080/producto/1' => Http::response([
                'id' => 1,
                'nombre' => 'Zapato Actualizado'
            ], 200)
        ]);

        $service = new ProductoService();

        $resultado = $service->actualizarProducto(
            1,
            'Zapato Actualizado',
            'Nueva descripción',
            20,
            'nueva.jpg',
            1,
            'Activo',
            120000,
            'Rojo',
            2
        );

        $this->assertTrue($resultado['success']);
        $this->assertEquals('Zapato Actualizado', $resultado['data']['nombre']);
    }

    /* ==========================
       🔹 ELIMINAR PRODUCTO
    ========================== */

    public function test_eliminar_producto_success()
    {
        Http::fake([
            'http://localhost:8080/producto/1' => Http::response(null, 200)
        ]);

        $service = new ProductoService();
        $resultado = $service->eliminarProducto(1);

        $this->assertTrue($resultado['success']);
    }

    /* ==========================
       🔹 BUSCAR PRODUCTO
    ========================== */

    public function test_buscar_producto_success()
    {
        Http::fake([
            'http://localhost:8080/producto/buscar*' => Http::response([
                ['id' => 1, 'nombre' => 'Zapato']
            ], 200)
        ]);

        $service = new ProductoService();
        $resultado = $service->buscarProducto('Zapato');

        $this->assertCount(1, $resultado);
        $this->assertEquals('Zapato', $resultado[0]['nombre']);
    }
}