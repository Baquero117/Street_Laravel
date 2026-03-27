<?php
namespace Tests\Unit\Administrador;

use Tests\TestCase;
use App\Models\Administrador\ProductoService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class ProductoServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Session::put('token', 'token-fake');
    }

    private function datosProducto(): array
    {
        return [
            'Camiseta', 'Descripción', 10, 'productos/img.jpg',
            1, 'Activo', 50000, 'Negro', 20
        ];
    }

    // ── 1. obtenerProductos() retorna array cuando la API responde HTTP 200 ──
    #[Test]
    public function obtener_productos_retorna_array_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/producto' => Http::response([
                ['id_producto' => 1, 'nombre' => 'Camiseta', 'precio' => 50000],
                ['id_producto' => 2, 'nombre' => 'Gorra',    'precio' => 25000],
            ], 200),
        ]);

        $service   = new ProductoService();
        $resultado = $service->obtenerProductos();

        $this->assertIsArray($resultado);
        $this->assertCount(2, $resultado);
    }
    // php artisan test --filter=ProductoServiceTest::obtener_productos_retorna_array_cuando_api_exitosa

    // ── 2. obtenerProductos() retorna array vacío cuando la API falla ──
    #[Test]
    public function obtener_productos_retorna_vacio_cuando_api_falla(): void
    {
        Http::fake([
            '*/producto' => Http::response([], 500),
        ]);

        $service   = new ProductoService();
        $resultado = $service->obtenerProductos();

        $this->assertIsArray($resultado);
        $this->assertEmpty($resultado);
    }
    // php artisan test --filter=ProductoServiceTest::obtener_productos_retorna_vacio_cuando_api_falla

    // ── 3. agregarProducto() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function agregar_producto_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/producto' => Http::response(['id_producto' => 3], 200),
        ]);

        $service   = new ProductoService();
        $resultado = $service->agregarProducto(...$this->datosProducto());

        $this->assertTrue($resultado['success']);
        $this->assertArrayHasKey('data', $resultado);
    }
    // php artisan test --filter=ProductoServiceTest::agregar_producto_retorna_success_true_cuando_api_exitosa

    // ── 4. agregarProducto() retorna success=>false cuando la API falla ──
    #[Test]
    public function agregar_producto_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/producto' => Http::response([], 500),
        ]);

        $service   = new ProductoService();
        $resultado = $service->agregarProducto(...$this->datosProducto());

        $this->assertFalse($resultado['success']);
        $this->assertStringContainsString('500', $resultado['error']);
    }
    // php artisan test --filter=ProductoServiceTest::agregar_producto_retorna_false_cuando_api_falla

    // ── 5. actualizarProducto() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function actualizar_producto_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/producto/1' => Http::response(['id_producto' => 1], 200),
        ]);

        $service   = new ProductoService();
        $resultado = $service->actualizarProducto(1, ...$this->datosProducto());

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=ProductoServiceTest::actualizar_producto_retorna_success_true_cuando_api_exitosa

    // ── 6. actualizarProducto() retorna success=>false cuando la API falla ──
    #[Test]
    public function actualizar_producto_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/producto/1' => Http::response([], 500),
        ]);

        $service   = new ProductoService();
        $resultado = $service->actualizarProducto(1, ...$this->datosProducto());

        $this->assertFalse($resultado['success']);
    }
    // php artisan test --filter=ProductoServiceTest::actualizar_producto_retorna_false_cuando_api_falla

    // ── 7. eliminarProducto() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function eliminar_producto_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/producto/1' => Http::response([], 200),
        ]);

        $service   = new ProductoService();
        $resultado = $service->eliminarProducto(1);

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=ProductoServiceTest::eliminar_producto_retorna_success_true_cuando_api_exitosa

    // ── 8. eliminarProducto() retorna success=>false cuando la API falla ──
    #[Test]
    public function eliminar_producto_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/producto/1' => Http::response([], 500),
        ]);

        $service   = new ProductoService();
        $resultado = $service->eliminarProducto(1);

        $this->assertFalse($resultado['success']);
        $this->assertStringContainsString('500', $resultado['error']);
    }
    // php artisan test --filter=ProductoServiceTest::eliminar_producto_retorna_false_cuando_api_falla

    // ── 9. buscarProducto() retorna JSON de la API ──
    #[Test]
    public function buscar_producto_retorna_json_de_la_api(): void
    {
        Http::fake([
            '*/producto/buscar*' => Http::response([
                ['id_producto' => 1, 'nombre' => 'Camiseta'],
            ], 200),
        ]);

        $service   = new ProductoService();
        $resultado = $service->buscarProducto('Camiseta');

        $this->assertIsArray($resultado);
        $this->assertEquals('Camiseta', $resultado[0]['nombre']);
    }
    // php artisan test --filter=ProductoServiceTest::buscar_producto_retorna_json_de_la_api
}

// php artisan test --filter=ProductoServiceTest