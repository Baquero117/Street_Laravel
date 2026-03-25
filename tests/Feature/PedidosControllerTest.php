<?php
namespace Tests\Feature\PuntoInicio;

use Tests\TestCase;
use App\Models\PuntoInicio\PedidosService;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class PedidosControllerTest extends TestCase
{
    // ── Helper de sesión ──
    private function sesionCliente(): void
    {
        Session::put('token', 'token-fake');
        Session::put('usuario_tipo', 'cliente');
        Session::put('usuario_id', 1);
    }

    // ── 1. index() redirige a login si no hay sesión ──
    #[Test]
    public function index_redirige_a_login_si_no_hay_sesion(): void
    {
        $response = $this->get(route('mis-pedidos'));

        $response->assertRedirect(route('login'));
    }
    // php artisan test --filter=PedidosControllerTest::index_redirige_a_login_si_no_hay_sesion

    // ── 2. index() retorna la vista de pedidos cuando el cliente está autenticado ──
    #[Test]
    public function index_retorna_vista_pedidos_cuando_autenticado(): void
    {
        $this->sesionCliente();

        $this->mock(PedidosService::class, function ($mock) {
            $mock->shouldReceive('obtenerPedidosPorCliente')
                ->once()
                ->andReturn([
                    [
                        'id_pedido'  => 1,
                        'estado'     => 'PENDIENTE',
                        'total'      => 150000,
                        'fecha'      => '2025-01-01',
                        'productos'  => [],
                    ],
                ]);
        });

        $response = $this->get(route('mis-pedidos'));

        $response->assertStatus(200);
        $response->assertViewIs('PuntoInicio.Cliente.Pedidos');
        $response->assertViewHas('pedidos');
    }
    // php artisan test --filter=PedidosControllerTest::index_retorna_vista_pedidos_cuando_autenticado

    // ── 3. verFactura() redirige a login si no hay sesión (interceptado por checkAuth) ──
    #[Test]
    public function ver_factura_redirige_a_login_si_no_hay_sesion(): void
    {
        $response = $this->get(route('mis-pedidos.factura.ver', ['id' => 1]));

        $response->assertStatus(302);
    }
    // php artisan test --filter=PedidosControllerTest::ver_factura_redirige_a_login_si_no_hay_sesion

    // ── 4. verFactura() retorna PDF inline cuando la factura existe ──
    #[Test]
    public function ver_factura_retorna_pdf_inline_cuando_existe(): void
    {
        $this->sesionCliente();

        $this->mock(PedidosService::class, function ($mock) {
            $mock->shouldReceive('obtenerFactura')
                ->once()
                ->with(1, 'token-fake', false)
                ->andReturn('%PDF-1.4 fake-content');
        });

        $response = $this->get(route('mis-pedidos.factura.ver', ['id' => 1]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'inline');
    }
    // php artisan test --filter=PedidosControllerTest::ver_factura_retorna_pdf_inline_cuando_existe

    // ── 5. verFactura() retorna 404 cuando la factura no existe ──
    #[Test]
    public function ver_factura_retorna_404_cuando_no_existe(): void
    {
        $this->sesionCliente();

        $this->mock(PedidosService::class, function ($mock) {
            $mock->shouldReceive('obtenerFactura')
                ->once()
                ->andReturn(null);
        });

        $response = $this->get(route('mis-pedidos.factura.ver', ['id' => 999]));

        $response->assertStatus(404);
    }
    // php artisan test --filter=PedidosControllerTest::ver_factura_retorna_404_cuando_no_existe

    // ── 6. descargarFactura() redirige a login si no hay sesión (interceptado por checkAuth) ──
    #[Test]
    public function descargar_factura_redirige_a_login_si_no_hay_sesion(): void
    {
        $response = $this->get(route('mis-pedidos.factura.descargar', ['id' => 1]));

        $response->assertStatus(302);
    }
    // php artisan test --filter=PedidosControllerTest::descargar_factura_redirige_a_login_si_no_hay_sesion

    // ── 7. descargarFactura() retorna PDF con header attachment cuando la factura existe ──
    #[Test]
    public function descargar_factura_retorna_pdf_attachment_cuando_existe(): void
    {
        $this->sesionCliente();

        $this->mock(PedidosService::class, function ($mock) {
            $mock->shouldReceive('obtenerFactura')
                ->once()
                ->with(1, 'token-fake', true)
                ->andReturn('%PDF-1.4 fake-content');
        });

        $response = $this->get(route('mis-pedidos.factura.descargar', ['id' => 1]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename="factura-1.pdf"');
    }
    // php artisan test --filter=PedidosControllerTest::descargar_factura_retorna_pdf_attachment_cuando_existe

    // ── 8. descargarFactura() retorna 404 cuando la factura no existe ──
    #[Test]
    public function descargar_factura_retorna_404_cuando_no_existe(): void
    {
        $this->sesionCliente();

        $this->mock(PedidosService::class, function ($mock) {
            $mock->shouldReceive('obtenerFactura')
                ->once()
                ->andReturn(null);
        });

        $response = $this->get(route('mis-pedidos.factura.descargar', ['id' => 999]));

        $response->assertStatus(404);
    }
    // php artisan test --filter=PedidosControllerTest::descargar_factura_retorna_404_cuando_no_existe
}

// php artisan test --filter=PedidosControllerTest