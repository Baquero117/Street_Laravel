<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\Administrador\PedidoService;

class PedidoServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        session(['token' => '123']);
    }

    /* ===================== OBTENER PEDIDOS ===================== */

    public function test_obtener_pedidos_success()
    {
        Http::fake([
            'http://localhost:8080/pedido' => Http::response([
                [
                    'id_pedido' => 1,
                    'total' => 100000
                ]
            ], 200)
        ]);

        $service = new PedidoService();
        $resultado = $service->obtenerPedidos();

        $this->assertTrue($resultado['success']);
        $this->assertCount(1, $resultado['data']);
    }

    /* ===================== CREAR PEDIDO ===================== */

    public function test_crear_pedido_success()
    {
        Http::fake([
            'http://localhost:8080/pedido' => Http::response(null, 200)
        ]);

        $service = new PedidoService();

        $resultado = $service->crearPedido(
            1,
            '2026-02-22',
            150000,
            'Pendiente'
        );

        $this->assertTrue($resultado['success']);
    }

    /* ===================== ACTUALIZAR PARCIAL ===================== */

    public function test_actualizar_pedido_parcial_success()
    {
        Http::fake([
            'http://localhost:8080/pedido/1' => Http::response(null, 200)
        ]);

        $service = new PedidoService();

        $resultado = $service->actualizarPedidoParcial(
            1,
            '2026-02-23',
            200000
        );

        $this->assertTrue($resultado['success']);
    }

    /* ===================== CAMBIAR ESTADO ===================== */

    public function test_actualizar_pedido_estado_success()
    {
        Http::fake([
            'http://localhost:8080/pedido/1/estado' => Http::response(null, 200)
        ]);

        $service = new PedidoService();

        $resultado = $service->actualizarPedidoEstado(
            1,
            'Enviado'
        );

        $this->assertTrue($resultado['success']);
    }

    /* ===================== OBTENER FACTURA PDF ===================== */

    public function test_obtener_factura_success()
    {
        $fakePdfContent = '%PDF-1.4 FAKE PDF CONTENT';

        Http::fake([
            'http://localhost:8080/pedido/1/factura/ver' =>
                Http::response($fakePdfContent, 200, [
                    'Content-Type' => 'application/pdf'
                ])
        ]);

        $service = new PedidoService();
        $resultado = $service->obtenerFactura(1);

        $this->assertTrue($resultado['success']);
        $this->assertEquals($fakePdfContent, $resultado['data']);
    }
}

/** php artisan test --filter=PedidoServiceTest */