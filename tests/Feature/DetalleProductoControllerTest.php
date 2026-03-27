<?php
namespace Tests\Feature\Administrador;

use Tests\TestCase;
use App\Models\Administrador\DetalleProductoService;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class DetalleProductoControllerTest extends TestCase
{
    // ── Helper de sesión admin ──
    private function sesionAdmin(): void
    {
        Session::put('token', 'token-fake');
        Session::put('usuario_tipo', 'administrador');
        Session::put('usuario_id', 1);
    }

    // ── 1. index() redirige si no hay token (interceptado por constructor) ──
    #[Test]
    public function index_redirige_si_no_hay_token(): void
    {
        $response = $this->get(route('detalle.index'));

        $response->assertStatus(302);
    }
    // php artisan test --filter=DetalleProductoControllerTest::index_redirige_si_no_hay_token

    // ── 2. index() retorna la vista de detalles cuando hay sesión ──
    #[Test]
    public function index_retorna_vista_cuando_autenticado(): void
    {
        $this->sesionAdmin();

        $this->mock(DetalleProductoService::class, function ($mock) {
            $mock->shouldReceive('obtenerDetalles')
                ->once()
                ->andReturn([
                    [
                        'id_detalle_producto' => 1,
                        'talla'               => 'M',
                        'cantidad'            => 10,
                        'id_producto'         => 1,
                        'nombre_producto'     => 'Camiseta',
                        'color'               => 'Negro',
                    ],
                ]);
        });

        $response = $this->get(route('detalle.index'));

        $response->assertStatus(200);
        $response->assertViewIs('Administrador.DetalleProducto');
        $response->assertViewHas('detalles');
    }
    // php artisan test --filter=DetalleProductoControllerTest::index_retorna_vista_cuando_autenticado

    // ── 3. store() falla validación si faltan campos requeridos ──
    #[Test]
    public function store_falla_validacion_si_faltan_campos(): void
    {
        $this->sesionAdmin();

        $response = $this->post(route('detalle.agregar'), []);

        $response->assertSessionHasErrors(['talla', 'id_producto', 'cantidad']);
    }
    // php artisan test --filter=DetalleProductoControllerTest::store_falla_validacion_si_faltan_campos

    // ── 4. store() falla validación si cantidad es menor a 1 ──
    #[Test]
    public function store_falla_validacion_si_cantidad_menor_a_1(): void
    {
        $this->sesionAdmin();

        $response = $this->post(route('detalle.agregar'), [
            'talla'       => 'M',
            'id_producto' => 1,
            'cantidad'    => 0,
        ]);

        $response->assertSessionHasErrors(['cantidad']);
    }
    // php artisan test --filter=DetalleProductoControllerTest::store_falla_validacion_si_cantidad_menor_a_1

    // ── 5. store() redirige con mensaje de éxito cuando el servicio agrega correctamente ──
    #[Test]
    public function store_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();

        $this->mock(DetalleProductoService::class, function ($mock) {
            $mock->shouldReceive('agregarDetalle')
                ->once()
                ->andReturn(['success' => true, 'data' => []]);
        });

        $response = $this->post(route('detalle.agregar'), [
            'talla'       => 'M',
            'id_producto' => 1,
            'cantidad'    => 10,
        ]);

        $response->assertRedirect(route('detalle.index'));
        $response->assertSessionHas('mensaje', 'Detalle agregado correctamente.');
    }
    // php artisan test --filter=DetalleProductoControllerTest::store_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 6. store() redirige con mensaje de error cuando el servicio falla ──
    #[Test]
    public function store_redirige_con_mensaje_error_cuando_servicio_falla(): void
    {
        $this->sesionAdmin();

        $this->mock(DetalleProductoService::class, function ($mock) {
            $mock->shouldReceive('agregarDetalle')
                ->once()
                ->andReturn(['success' => false, 'error' => 'HTTP 500']);
        });

        $response = $this->post(route('detalle.agregar'), [
            'talla'       => 'M',
            'id_producto' => 1,
            'cantidad'    => 10,
        ]);

        $response->assertRedirect(route('detalle.index'));
        $response->assertSessionHas('mensaje', 'Error: HTTP 500');
    }
    // php artisan test --filter=DetalleProductoControllerTest::store_redirige_con_mensaje_error_cuando_servicio_falla

    // ── 7. update() falla validación si id_detalle_producto no es numérico ──
    #[Test]
    public function update_falla_validacion_si_id_no_es_numerico(): void
    {
        $this->sesionAdmin();

        $response = $this->post(route('detalle.actualizar'), [
            'id_detalle_producto' => 'no-numerico',
            'talla'               => 'M',
            'id_producto'         => 1,
            'cantidad'            => 10,
        ]);

        $response->assertSessionHasErrors(['id_detalle_producto']);
    }
    // php artisan test --filter=DetalleProductoControllerTest::update_falla_validacion_si_id_no_es_numerico

    // ── 8. update() redirige con mensaje de éxito cuando el servicio actualiza correctamente ──
    #[Test]
    public function update_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();

        $this->mock(DetalleProductoService::class, function ($mock) {
            $mock->shouldReceive('actualizarDetalle')
                ->once()
                ->andReturn(['success' => true, 'data' => []]);
        });

        $response = $this->post(route('detalle.actualizar'), [
            'id_detalle_producto' => 1,
            'talla'               => 'L',
            'id_producto'         => 1,
            'cantidad'            => 20,
        ]);

        $response->assertRedirect(route('detalle.index'));
        $response->assertSessionHas('mensaje', 'Detalle actualizado correctamente.');
    }
    // php artisan test --filter=DetalleProductoControllerTest::update_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 9. update() redirige con mensaje de error cuando el servicio falla ──
    #[Test]
    public function update_redirige_con_mensaje_error_cuando_servicio_falla(): void
    {
        $this->sesionAdmin();

        $this->mock(DetalleProductoService::class, function ($mock) {
            $mock->shouldReceive('actualizarDetalle')
                ->once()
                ->andReturn(['success' => false, 'error' => 'HTTP 500']);
        });

        $response = $this->post(route('detalle.actualizar'), [
            'id_detalle_producto' => 1,
            'talla'               => 'L',
            'id_producto'         => 1,
            'cantidad'            => 20,
        ]);

        $response->assertRedirect(route('detalle.index'));
        $response->assertSessionHas('mensaje', 'Error: HTTP 500');
    }
    // php artisan test --filter=DetalleProductoControllerTest::update_redirige_con_mensaje_error_cuando_servicio_falla

    // ── 10. destroy() redirige con mensaje de éxito cuando el servicio elimina correctamente ──
    #[Test]
    public function destroy_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();

        $this->mock(DetalleProductoService::class, function ($mock) {
            $mock->shouldReceive('eliminarDetalle')
                ->once()
                ->andReturn(['success' => true]);
        });

        $response = $this->post(route('detalle.eliminar'), ['id_detalle_producto' => 1]);

        $response->assertRedirect(route('detalle.index'));
        $response->assertSessionHas('mensaje', 'Detalle eliminado correctamente.');
    }
    // php artisan test --filter=DetalleProductoControllerTest::destroy_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 11. destroy() redirige con mensaje de error cuando el servicio falla ──
    #[Test]
    public function destroy_redirige_con_mensaje_error_cuando_servicio_falla(): void
    {
        $this->sesionAdmin();

        $this->mock(DetalleProductoService::class, function ($mock) {
            $mock->shouldReceive('eliminarDetalle')
                ->once()
                ->andReturn(['success' => false, 'error' => 'HTTP 500']);
        });

        $response = $this->post(route('detalle.eliminar'), ['id_detalle_producto' => 1]);

        $response->assertRedirect(route('detalle.index'));
        $response->assertSessionHas('mensaje', 'Error: HTTP 500');
    }
    // php artisan test --filter=DetalleProductoControllerTest::destroy_redirige_con_mensaje_error_cuando_servicio_falla

    // ── 12. buscar() retorna JSON con resultados de búsqueda ──
    #[Test]
    public function buscar_retorna_json_con_resultados(): void
    {
        $this->sesionAdmin();

        $this->mock(DetalleProductoService::class, function ($mock) {
            $mock->shouldReceive('buscarDetalleProducto')
                ->once()
                ->with('5')
                ->andReturn([
                    ['id_detalle_producto' => 1, 'talla' => 'M', 'id_producto' => 5],
                ]);
        });

        $response = $this->getJson(route('detalle_producto.buscar', ['id_producto' => 5]));

        $response->assertStatus(200);
        $response->assertJson([
            ['id_detalle_producto' => 1, 'talla' => 'M'],
        ]);
    }
    // php artisan test --filter=DetalleProductoControllerTest::buscar_retorna_json_con_resultados
}

// php artisan test --filter=DetalleProductoControllerTest