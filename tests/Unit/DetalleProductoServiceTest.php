<?php
namespace Tests\Unit\Administrador;

use Tests\TestCase;
use App\Models\Administrador\DetalleProductoService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class DetalleProductoServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Session::put('token', 'token-fake');
    }

    // ── 1. obtenerDetalles() retorna array de detalles cuando la API responde HTTP 200 ──
    #[Test]
    public function obtener_detalles_retorna_array_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/detalle_producto' => Http::response([
                ['id_detalle_producto' => 1, 'talla' => 'M', 'cantidad' => 10],
                ['id_detalle_producto' => 2, 'talla' => 'L', 'cantidad' => 5],
            ], 200),
        ]);

        $service   = new DetalleProductoService();
        $resultado = $service->obtenerDetalles();

        $this->assertIsArray($resultado);
        $this->assertCount(2, $resultado);
        $this->assertEquals('M', $resultado[0]['talla']);
    }
    // php artisan test --filter=DetalleProductoServiceTest::obtener_detalles_retorna_array_cuando_api_exitosa

    // ── 2. obtenerDetalles() retorna array vacío cuando la API falla ──
    #[Test]
    public function obtener_detalles_retorna_vacio_cuando_api_falla(): void
    {
        Http::fake([
            '*/detalle_producto' => Http::response([], 500),
        ]);

        $service   = new DetalleProductoService();
        $resultado = $service->obtenerDetalles();

        $this->assertIsArray($resultado);
        $this->assertEmpty($resultado);
    }
    // php artisan test --filter=DetalleProductoServiceTest::obtener_detalles_retorna_vacio_cuando_api_falla

    // ── 3. agregarDetalle() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function agregar_detalle_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/detalle_producto' => Http::response(['id_detalle_producto' => 3], 200),
        ]);

        $service   = new DetalleProductoService();
        $resultado = $service->agregarDetalle('M', 1, 10);

        $this->assertTrue($resultado['success']);
        $this->assertArrayHasKey('data', $resultado);
    }
    // php artisan test --filter=DetalleProductoServiceTest::agregar_detalle_retorna_success_true_cuando_api_exitosa

    // ── 4. agregarDetalle() retorna success=>false cuando la API falla ──
    #[Test]
    public function agregar_detalle_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/detalle_producto' => Http::response([], 500),
        ]);

        $service   = new DetalleProductoService();
        $resultado = $service->agregarDetalle('M', 1, 10);

        $this->assertFalse($resultado['success']);
        $this->assertStringContainsString('500', $resultado['error']);
    }
    // php artisan test --filter=DetalleProductoServiceTest::agregar_detalle_retorna_false_cuando_api_falla

    // ── 5. actualizarDetalle() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function actualizar_detalle_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/detalle_producto/1' => Http::response(['id_detalle_producto' => 1], 200),
        ]);

        $service   = new DetalleProductoService();
        $resultado = $service->actualizarDetalle(1, 'L', 1, 20);

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=DetalleProductoServiceTest::actualizar_detalle_retorna_success_true_cuando_api_exitosa

    // ── 6. actualizarDetalle() retorna success=>false cuando la API falla ──
    #[Test]
    public function actualizar_detalle_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/detalle_producto/1' => Http::response([], 500),
        ]);

        $service   = new DetalleProductoService();
        $resultado = $service->actualizarDetalle(1, 'L', 1, 20);

        $this->assertFalse($resultado['success']);
        $this->assertStringContainsString('500', $resultado['error']);
    }
    // php artisan test --filter=DetalleProductoServiceTest::actualizar_detalle_retorna_false_cuando_api_falla

    // ── 7. eliminarDetalle() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function eliminar_detalle_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/detalle_producto/1' => Http::response([], 200),
        ]);

        $service   = new DetalleProductoService();
        $resultado = $service->eliminarDetalle(1);

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=DetalleProductoServiceTest::eliminar_detalle_retorna_success_true_cuando_api_exitosa

    // ── 8. eliminarDetalle() retorna success=>false cuando la API falla ──
    #[Test]
    public function eliminar_detalle_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/detalle_producto/1' => Http::response([], 500),
        ]);

        $service   = new DetalleProductoService();
        $resultado = $service->eliminarDetalle(1);

        $this->assertFalse($resultado['success']);
        $this->assertStringContainsString('500', $resultado['error']);
    }
    // php artisan test --filter=DetalleProductoServiceTest::eliminar_detalle_retorna_false_cuando_api_falla

    // ── 9. buscarDetalleProducto() retorna JSON de la API ──
    #[Test]
    public function buscar_detalle_producto_retorna_json_de_la_api(): void
    {
        Http::fake([
            '*/detalle_producto/detalle_producto/buscar*' => Http::response([
                ['id_detalle_producto' => 1, 'talla' => 'M', 'id_producto' => 5],
            ], 200),
        ]);

        $service   = new DetalleProductoService();
        $resultado = $service->buscarDetalleProducto(5);

        $this->assertIsArray($resultado);
        $this->assertEquals('M', $resultado[0]['talla']);
    }
    // php artisan test --filter=DetalleProductoServiceTest::buscar_detalle_producto_retorna_json_de_la_api
}

// php artisan test --filter=DetalleProductoServiceTest