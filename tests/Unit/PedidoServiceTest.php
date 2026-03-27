<?php
namespace Tests\Unit\Administrador;

use Tests\TestCase;
use App\Models\Administrador\PedidoService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class PedidoServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Session::put('token', 'token-fake');
    }

    // ── 1. obtenerPedidos() retorna array de pedidos cuando la API responde HTTP 200 ──
    #[Test]
    public function obtener_pedidos_retorna_array_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/pedido' => Http::response([
                ['id_pedido' => 1, 'id_cliente' => 1, 'total' => 150000, 'estado' => 'Pendiente'],
                ['id_pedido' => 2, 'id_cliente' => 2, 'total' => 80000,  'estado' => 'Entregado'],
            ], 200),
        ]);

        $service   = new PedidoService();
        $resultado = $service->obtenerPedidos();

        $this->assertTrue($resultado['success']);
        $this->assertCount(2, $resultado['data']);
    }
    // php artisan test --filter=PedidoServiceTest::obtener_pedidos_retorna_array_cuando_api_exitosa

    // ── 2. obtenerPedidos() envuelve objeto único en array cuando la API retorna un solo pedido ──
    #[Test]
    public function obtener_pedidos_envuelve_objeto_unico_en_array(): void
    {
        Http::fake([
            '*/pedido' => Http::response(
                ['id_pedido' => 1, 'id_cliente' => 1, 'total' => 150000, 'estado' => 'Pendiente'],
                200
            ),
        ]);

        $service   = new PedidoService();
        $resultado = $service->obtenerPedidos();

        $this->assertTrue($resultado['success']);
        $this->assertCount(1, $resultado['data']);
    }
    // php artisan test --filter=PedidoServiceTest::obtener_pedidos_envuelve_objeto_unico_en_array

    // ── 3. obtenerPedidos() retorna success=>false cuando la API falla ──
    #[Test]
    public function obtener_pedidos_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/pedido' => Http::response([], 500),
        ]);

        $service   = new PedidoService();
        $resultado = $service->obtenerPedidos();

        $this->assertFalse($resultado['success']);
        $this->assertEmpty($resultado['data']);
    }
    // php artisan test --filter=PedidoServiceTest::obtener_pedidos_retorna_false_cuando_api_falla

    // ── 4. crearPedido() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function crear_pedido_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/pedido' => Http::response([], 200),
        ]);

        $service   = new PedidoService();
        $resultado = $service->crearPedido(1, '2025-01-01', 150000, 'Pendiente');

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=PedidoServiceTest::crear_pedido_retorna_success_true_cuando_api_exitosa

    // ── 5. crearPedido() retorna success=>false cuando la API falla ──
    #[Test]
    public function crear_pedido_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/pedido' => Http::response([], 500),
        ]);

        $service   = new PedidoService();
        $resultado = $service->crearPedido(1, '2025-01-01', 150000, 'Pendiente');

        $this->assertFalse($resultado['success']);
    }
    // php artisan test --filter=PedidoServiceTest::crear_pedido_retorna_false_cuando_api_falla

    // ── 6. actualizarPedidoParcial() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function actualizar_pedido_parcial_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/pedido/1' => Http::response([], 200),
        ]);

        $service   = new PedidoService();
        $resultado = $service->actualizarPedidoParcial(1, '2025-06-01', 200000);

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=PedidoServiceTest::actualizar_pedido_parcial_retorna_success_true_cuando_api_exitosa

    // ── 7. actualizarPedidoParcial() retorna success=>false cuando la API falla ──
    #[Test]
    public function actualizar_pedido_parcial_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/pedido/1' => Http::response([], 500),
        ]);

        $service   = new PedidoService();
        $resultado = $service->actualizarPedidoParcial(1, '2025-06-01', 200000);

        $this->assertFalse($resultado['success']);
    }
    // php artisan test --filter=PedidoServiceTest::actualizar_pedido_parcial_retorna_false_cuando_api_falla

    // ── 8. actualizarPedidoEstado() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function actualizar_pedido_estado_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/pedido/1/estado' => Http::response([], 200),
        ]);

        $service   = new PedidoService();
        $resultado = $service->actualizarPedidoEstado(1, 'Entregado');

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=PedidoServiceTest::actualizar_pedido_estado_retorna_success_true_cuando_api_exitosa

    // ── 9. actualizarPedidoEstado() retorna success=>false cuando la API falla ──
    #[Test]
    public function actualizar_pedido_estado_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/pedido/1/estado' => Http::response([], 500),
        ]);

        $service   = new PedidoService();
        $resultado = $service->actualizarPedidoEstado(1, 'Entregado');

        $this->assertFalse($resultado['success']);
    }
    // php artisan test --filter=PedidoServiceTest::actualizar_pedido_estado_retorna_false_cuando_api_falla

    // ── 10. obtenerFactura() retorna success=>true con PDF cuando la API responde HTTP 200 ──
    #[Test]
    public function obtener_factura_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/pedido/1/factura/ver' => Http::response('%PDF-1.4 fake-content', 200),
        ]);

        $service   = new PedidoService();
        $resultado = $service->obtenerFactura(1);

        $this->assertTrue($resultado['success']);
        $this->assertNotEmpty($resultado['data']);
    }
    // php artisan test --filter=PedidoServiceTest::obtener_factura_retorna_success_true_cuando_api_exitosa

    // ── 11. obtenerFactura() retorna success=>false cuando la API falla ──
    #[Test]
    public function obtener_factura_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/pedido/1/factura/ver' => Http::response('', 404),
        ]);

        $service   = new PedidoService();
        $resultado = $service->obtenerFactura(1);

        $this->assertFalse($resultado['success']);
    }
    // php artisan test --filter=PedidoServiceTest::obtener_factura_retorna_false_cuando_api_falla
}

// php artisan test --filter=PedidoServiceTest