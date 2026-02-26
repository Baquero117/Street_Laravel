<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\Administrador\DetalleProductoService;

class DetalleProductoServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        session(['token' => '123']);
    }

    public function test_obtener_detalles_success()
    {
        Http::fake([
            'http://localhost:8080/detalle_producto' => Http::response([
                ['id_detalle_producto' => 1, 'talla' => 'M']
            ], 200)
        ]);

        $service = new DetalleProductoService();
        $resultado = $service->obtenerDetalles();

        $this->assertIsArray($resultado);
        $this->assertCount(1, $resultado);
    }

    public function test_agregar_detalle_success()
    {
        Http::fake([
            'http://localhost:8080/detalle_producto' => Http::response([
                'id_detalle_producto' => 1
            ], 200)
        ]);

        $service = new DetalleProductoService();

        $resultado = $service->agregarDetalle(
            'M',
            'detalles/imagen.jpg',
            1,
            10
        );

        $this->assertTrue($resultado['success']);
        $this->assertArrayHasKey('data', $resultado);
    }

    public function test_actualizar_detalle_con_imagen()
    {
        Http::fake([
            'http://localhost:8080/detalle_producto/1' => Http::response([
                'updated' => true
            ], 200)
        ]);

        $service = new DetalleProductoService();

        $resultado = $service->actualizarDetalle(
            1,
            'L',
            'detalles/nueva.jpg',
            1,
            5
        );

        $this->assertTrue($resultado['success']);
    }

    public function test_actualizar_detalle_sin_imagen()
    {
        Http::fake([
            'http://localhost:8080/detalle_producto/1' => Http::response([
                'updated' => true
            ], 200)
        ]);

        $service = new DetalleProductoService();

        $resultado = $service->actualizarDetalle(
            1,
            'L',
            null,
            1,
            5
        );

        $this->assertTrue($resultado['success']);
    }

    public function test_eliminar_detalle_success()
    {
        Http::fake([
            'http://localhost:8080/detalle_producto/1' => Http::response(null, 200)
        ]);

        $service = new DetalleProductoService();
        $resultado = $service->eliminarDetalle(1);

        $this->assertTrue($resultado['success']);
    }

    public function test_buscar_detalle_producto()
    {
        Http::fake([
            'http://localhost:8080/detalle_producto/detalle_producto/buscar*' => Http::response([
                ['id_detalle_producto' => 1]
            ], 200)
        ]);

        $service = new DetalleProductoService();
        $resultado = $service->buscarDetalleProducto(1);

        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado[0]['id_detalle_producto']);
    }
}

/** php artisan test --filter=DetalleProductoServiceTest */
