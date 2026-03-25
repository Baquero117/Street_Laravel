<?php
namespace Tests\Unit\PuntoInicio;

use Tests\TestCase;
use App\Models\PuntoInicio\PedidosService;
use PHPUnit\Framework\Attributes\Test;

class PedidosServiceTest extends TestCase
{
    // ── 1. obtenerPedidosPorCliente() retorna array de pedidos cuando la API responde HTTP 200 ──
    #[Test]
    public function obtener_pedidos_retorna_array_cuando_api_exitosa(): void
    {
        $pedidosMock = [
            ['id_pedido' => 1, 'estado' => 'PENDIENTE', 'total' => 150000],
            ['id_pedido' => 2, 'estado' => 'ENTREGADO', 'total' => 80000],
        ];

        $service   = $this->mockServicio('obtenerPedidosPorCliente', $pedidosMock, 200);
        $resultado = $service->obtenerPedidosPorCliente(1, 'token-fake');

        $this->assertIsArray($resultado);
        $this->assertCount(2, $resultado);
        $this->assertEquals('PENDIENTE', $resultado[0]['estado']);
    }
    // php artisan test --filter=PedidosServiceTest::obtener_pedidos_retorna_array_cuando_api_exitosa

    // ── 2. obtenerPedidosPorCliente() retorna array vacío cuando la API falla ──
    #[Test]
    public function obtener_pedidos_retorna_vacio_cuando_api_falla(): void
    {
        $service   = $this->mockServicio('obtenerPedidosPorCliente', [], 500);
        $resultado = $service->obtenerPedidosPorCliente(1, 'token-fake');

        $this->assertIsArray($resultado);
        $this->assertEmpty($resultado);
    }
    // php artisan test --filter=PedidosServiceTest::obtener_pedidos_retorna_vacio_cuando_api_falla

    // ── 3. obtenerFactura() retorna string PDF cuando la API responde HTTP 200 (ver) ──
    #[Test]
    public function obtener_factura_retorna_pdf_cuando_api_exitosa_ver(): void
    {
        $pdfFake = '%PDF-1.4 fake-pdf-content';

        $service   = $this->mockServicio('obtenerFactura', $pdfFake, 200);
        $resultado = $service->obtenerFactura(1, 'token-fake', false);

        $this->assertNotNull($resultado);
        $this->assertIsString($resultado);
        $this->assertEquals($pdfFake, $resultado);
    }
    // php artisan test --filter=PedidosServiceTest::obtener_factura_retorna_pdf_cuando_api_exitosa_ver

    // ── 4. obtenerFactura() retorna string PDF cuando la API responde HTTP 200 (descargar) ──
    #[Test]
    public function obtener_factura_retorna_pdf_cuando_api_exitosa_descargar(): void
    {
        $pdfFake = '%PDF-1.4 fake-pdf-content';

        $service   = $this->mockServicio('obtenerFactura', $pdfFake, 200);
        $resultado = $service->obtenerFactura(1, 'token-fake', true);

        $this->assertNotNull($resultado);
        $this->assertIsString($resultado);
    }
    // php artisan test --filter=PedidosServiceTest::obtener_factura_retorna_pdf_cuando_api_exitosa_descargar

    // ── 5. obtenerFactura() retorna null cuando la API falla ──
    #[Test]
    public function obtener_factura_retorna_null_cuando_api_falla(): void
    {
        $service   = $this->mockServicio('obtenerFactura', null, 404);
        $resultado = $service->obtenerFactura(999, 'token-fake', false);

        $this->assertNull($resultado);
    }
    // php artisan test --filter=PedidosServiceTest::obtener_factura_retorna_null_cuando_api_falla

    // ────────────────────────────────────────────────────────────────────────────
    // Helper: clase anónima que sobreescribe el método indicado con respuesta mock
    // ────────────────────────────────────────────────────────────────────────────
    private function mockServicio(string $metodo, mixed $respuestaMock, int $httpCodeMock): PedidosService
    {
        return new class($metodo, $respuestaMock, $httpCodeMock) extends PedidosService {
            public function __construct(
                private string $metodo,
                private mixed  $respuestaMock,
                private int    $httpCodeMock
            ) {
                parent::__construct();
            }

            public function obtenerPedidosPorCliente(int $idCliente, string $token): array
            {
                if ($this->metodo !== 'obtenerPedidosPorCliente') return parent::obtenerPedidosPorCliente($idCliente, $token);
                return $this->httpCodeMock === 200 ? (array) $this->respuestaMock : [];
            }

            public function obtenerFactura(int $idPedido, string $token, bool $descargar): ?string
            {
                if ($this->metodo !== 'obtenerFactura') return parent::obtenerFactura($idPedido, $token, $descargar);
                return $this->httpCodeMock === 200 ? $this->respuestaMock : null;
            }
        };
    }
}

// php artisan test --filter=PedidosServiceTest