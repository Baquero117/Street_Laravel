<?php
namespace Tests\Feature\Administrador;

use Tests\TestCase;
use App\Models\Administrador\PedidoService;
use App\Models\Administrador\ClienteService;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class PedidoControllerTest extends TestCase
{
    private function sesionAdmin(): void
    {
        Session::put('token', 'token-fake');
        Session::put('usuario_tipo', 'administrador');
        Session::put('usuario_id', 1);
    }

    private function mockClienteBase($mock): void
    {
        $mock->shouldReceive('obtenerClientes')
            ->andReturn(['success' => true, 'data' => []]);
        $mock->shouldReceive('obtenerClientePorId')
            ->andReturn(['success' => false, 'data' => null]);
    }

    // ── 1. index() redirige si no hay token (interceptado por constructor) ──
    #[Test]
    public function index_redirige_si_no_hay_token(): void
    {
        $response = $this->get(route('pedido.index'));

        $response->assertStatus(302);
    }
    // php artisan test --filter=PedidoControllerTest::index_redirige_si_no_hay_token

    // ── 2. index() retorna la vista de pedidos cuando hay sesión ──
    #[Test]
    public function index_retorna_vista_cuando_autenticado(): void
    {
        $this->sesionAdmin();

        $this->mock(PedidoService::class, function ($mock) {
            $mock->shouldReceive('obtenerPedidos')
                ->once()
                ->andReturn(['success' => true, 'data' => []]);
        });

        $this->mock(ClienteService::class, function ($mock) {
            $this->mockClienteBase($mock);
        });

        $response = $this->get(route('pedido.index'));

        $response->assertStatus(200);
        $response->assertViewIs('Administrador.Pedido');
        $response->assertViewHas('pedidos');
    }
    // php artisan test --filter=PedidoControllerTest::index_retorna_vista_cuando_autenticado

    // ── 3. store() falla validación si faltan campos requeridos ──
    #[Test]
    public function store_falla_validacion_si_faltan_campos(): void
    {
        $this->sesionAdmin();

        $response = $this->post(route('pedido.agregar'), []);

        $response->assertSessionHasErrors(['id_cliente', 'fecha_pedido', 'total']);
    }
    // php artisan test --filter=PedidoControllerTest::store_falla_validacion_si_faltan_campos

    // ── 4. store() redirige con mensaje de éxito cuando el servicio crea correctamente ──
    #[Test]
    public function store_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();

        $this->mock(PedidoService::class, function ($mock) {
            $mock->shouldReceive('crearPedido')
                ->once()
                ->andReturn(['success' => true]);
        });

        $this->mock(ClienteService::class, function ($mock) {
            $this->mockClienteBase($mock);
        });

        $response = $this->post(route('pedido.agregar'), [
            'id_cliente'   => 1,
            'fecha_pedido' => '2025-01-01',
            'total'        => 150000,
        ]);

        $response->assertRedirect(route('pedido.index'));
        $response->assertSessionHas('mensaje', 'Pedido agregado correctamente.');
    }
    // php artisan test --filter=PedidoControllerTest::store_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 5. store() redirige con mensaje de error cuando el servicio falla ──
    #[Test]
    public function store_redirige_con_mensaje_error_cuando_servicio_falla(): void
    {
        $this->sesionAdmin();

        $this->mock(PedidoService::class, function ($mock) {
            $mock->shouldReceive('crearPedido')
                ->once()
                ->andReturn(['success' => false, 'error' => 'HTTP 500']);
        });

        $this->mock(ClienteService::class, function ($mock) {
            $this->mockClienteBase($mock);
        });

        $response = $this->post(route('pedido.agregar'), [
            'id_cliente'   => 1,
            'fecha_pedido' => '2025-01-01',
            'total'        => 150000,
        ]);

        $response->assertRedirect(route('pedido.index'));
        $response->assertSessionHas('mensaje', 'Error al agregar pedido.');
    }
    // php artisan test --filter=PedidoControllerTest::store_redirige_con_mensaje_error_cuando_servicio_falla

    // ── 6. cambiarEstado() falla validación si faltan campos ──
    #[Test]
    public function cambiar_estado_falla_validacion_si_faltan_campos(): void
    {
        $this->sesionAdmin();

        $response = $this->post(route('pedido.cambiarEstado'), []);

        $response->assertSessionHasErrors(['id_pedido', 'estado']);
    }
    // php artisan test --filter=PedidoControllerTest::cambiar_estado_falla_validacion_si_faltan_campos

    // ── 7. cambiarEstado() redirige con mensaje de éxito cuando el servicio actualiza ──
    #[Test]
    public function cambiar_estado_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();

        $this->mock(PedidoService::class, function ($mock) {
            $mock->shouldReceive('actualizarPedidoEstado')
                ->once()
                ->andReturn(['success' => true]);
        });

        $this->mock(ClienteService::class, function ($mock) {
            $this->mockClienteBase($mock);
        });

        $response = $this->post(route('pedido.cambiarEstado'), [
            'id_pedido' => 1,
            'estado'    => 'Entregado',
        ]);

        $response->assertRedirect(route('pedido.index'));
        $response->assertSessionHas('mensaje', 'Estado actualizado.');
    }
    // php artisan test --filter=PedidoControllerTest::cambiar_estado_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 8. cancelar() falla validación si falta id_pedido ──
    #[Test]
    public function cancelar_falla_validacion_si_falta_id_pedido(): void
    {
        $this->sesionAdmin();

        $response = $this->post(route('pedido.cancelar'), []);

        $response->assertSessionHasErrors(['id_pedido']);
    }
    // php artisan test --filter=PedidoControllerTest::cancelar_falla_validacion_si_falta_id_pedido

    // ── 9. cancelar() redirige con mensaje de éxito cuando el servicio cancela ──
    #[Test]
    public function cancelar_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();

        $this->mock(PedidoService::class, function ($mock) {
            $mock->shouldReceive('actualizarPedidoEstado')
                ->once()
                ->with(1, 'Cancelado')
                ->andReturn(['success' => true]);
        });

        $this->mock(ClienteService::class, function ($mock) {
            $this->mockClienteBase($mock);
        });

        $response = $this->post(route('pedido.cancelar'), ['id_pedido' => 1]);

        $response->assertRedirect(route('pedido.index'));
        $response->assertSessionHas('mensaje', 'Pedido cancelado.');
    }
    // php artisan test --filter=PedidoControllerTest::cancelar_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 10. verFactura() retorna PDF inline cuando la factura existe ──
    #[Test]
    public function ver_factura_retorna_pdf_inline_cuando_existe(): void
    {
        $this->sesionAdmin();

        $this->mock(PedidoService::class, function ($mock) {
            $mock->shouldReceive('obtenerFactura')
                ->once()
                ->andReturn(['success' => true, 'data' => '%PDF-1.4 fake-content']);
        });

        $this->mock(ClienteService::class, function ($mock) {
            $this->mockClienteBase($mock);
        });

        $response = $this->get(route('pedido.factura', ['id' => 1]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
    // php artisan test --filter=PedidoControllerTest::ver_factura_retorna_pdf_inline_cuando_existe

    // ── 11. verFactura() redirige con error cuando la factura no existe ──
    #[Test]
    public function ver_factura_redirige_con_error_cuando_no_existe(): void
    {
        $this->sesionAdmin();

        $this->mock(PedidoService::class, function ($mock) {
            $mock->shouldReceive('obtenerFactura')
                ->once()
                ->andReturn(['success' => false, 'error' => 'HTTP 404']);
        });

        $this->mock(ClienteService::class, function ($mock) {
            $this->mockClienteBase($mock);
        });

        $response = $this->get(route('pedido.factura', ['id' => 999]));

        $response->assertRedirect(route('pedido.index'));
        $response->assertSessionHas('mensaje');
    }
    // php artisan test --filter=PedidoControllerTest::ver_factura_redirige_con_error_cuando_no_existe
}

// php artisan test --filter=PedidoControllerTest