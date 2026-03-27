<?php
namespace Tests\Feature\Administrador;

use Tests\TestCase;
use App\Models\Administrador\ClienteService;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class ClienteControllerTest extends TestCase
{
    // ── Helper de sesión admin ──
    private function sesionAdmin(): void
    {
        Session::put('token', 'token-fake');
        Session::put('usuario_tipo', 'administrador');
        Session::put('usuario_id', 1);
    }

    // ── Datos base reutilizables ──
    private function datosFormulario(array $override = []): array
    {
        return array_merge([
            'nombre'             => 'Juan',
            'apellido'           => 'Pérez',
            'contrasena'         => 'pass1234',
            'departamento'       => 'Antioquia',
            'municipio'          => 'Medellín',
            'direccion'          => 'Calle 123',
            'telefono'           => '3001234567',
            'correo_electronico' => 'juan@test.com',
        ], $override);
    }

    // ── 1. index() redirige si no hay token (interceptado por constructor) ──
    #[Test]
    public function index_redirige_si_no_hay_token(): void
    {
        $response = $this->get(route('admin.Cliente'));

        $response->assertStatus(302);
    }
    // php artisan test --filter=ClienteControllerTest::index_redirige_si_no_hay_token

    // ── 2. index() retorna la vista de clientes cuando hay sesión ──
    #[Test]
    public function index_retorna_vista_cuando_autenticado(): void
    {
        $this->sesionAdmin();

        $this->mock(ClienteService::class, function ($mock) {
            $mock->shouldReceive('obtenerClientes')
                ->once()
                ->andReturn([
                    'success' => true,
                    'data'    => [
                        [
                            'id_cliente'         => 1,
                            'nombre'             => 'Juan',
                            'apellido'           => 'Pérez',
                            'correo_electronico' => 'juan@test.com',
                            'telefono'           => '3001234567',
                            'direccion'          => 'Calle 123',
                            'departamento'       => 'Antioquia',
                            'municipio'          => 'Medellín',
                        ],
                    ],
                ]);
        });

        $response = $this->get(route('admin.Cliente'));

        $response->assertStatus(200);
        $response->assertViewIs('Administrador.Cliente');
        $response->assertViewHas('clientes');
    }
    // php artisan test --filter=ClienteControllerTest::index_retorna_vista_cuando_autenticado

    // ── 3. store() falla validación si faltan campos requeridos ──
    #[Test]
    public function store_falla_validacion_si_faltan_campos(): void
    {
        $this->sesionAdmin();

        $response = $this->post(route('cliente.agregar'), []);

        $response->assertSessionHasErrors([
            'nombre', 'apellido', 'contrasena', 'departamento',
            'municipio', 'direccion', 'telefono', 'correo_electronico',
        ]);
    }
    // php artisan test --filter=ClienteControllerTest::store_falla_validacion_si_faltan_campos

    // ── 4. store() redirige con mensaje de éxito cuando el servicio agrega correctamente ──
    #[Test]
    public function store_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();

        $this->mock(ClienteService::class, function ($mock) {
            $mock->shouldReceive('agregarCliente')
                ->once()
                ->andReturn(['success' => true, 'data' => []]);
        });

        $response = $this->post(route('cliente.agregar'), $this->datosFormulario());

        $response->assertRedirect(route('admin.Cliente'));
        $response->assertSessionHas('mensaje', 'Cliente agregado correctamente.');
    }
    // php artisan test --filter=ClienteControllerTest::store_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 5. store() redirige con mensaje de error cuando el servicio falla ──
    #[Test]
    public function store_redirige_con_mensaje_error_cuando_servicio_falla(): void
    {
        $this->sesionAdmin();

        $this->mock(ClienteService::class, function ($mock) {
            $mock->shouldReceive('agregarCliente')
                ->once()
                ->andReturn(['success' => false, 'error' => 'HTTP 500']);
        });

        $response = $this->post(route('cliente.agregar'), $this->datosFormulario());

        $response->assertRedirect(route('admin.Cliente'));
        $response->assertSessionHas('mensaje', 'Error: HTTP 500');
    }
    // php artisan test --filter=ClienteControllerTest::store_redirige_con_mensaje_error_cuando_servicio_falla

    // ── 6. update() falla validación si id_cliente no es numérico ──
    #[Test]
    public function update_falla_validacion_si_id_no_es_numerico(): void
    {
        $this->sesionAdmin();

        $response = $this->post(route('cliente.actualizar'), $this->datosFormulario([
            'id_cliente' => 'no-numerico',
        ]));

        $response->assertSessionHasErrors(['id_cliente']);
    }
    // php artisan test --filter=ClienteControllerTest::update_falla_validacion_si_id_no_es_numerico

    // ── 7. update() redirige con mensaje de éxito cuando el servicio actualiza correctamente ──
    #[Test]
    public function update_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();

        $this->mock(ClienteService::class, function ($mock) {
            $mock->shouldReceive('actualizarCliente')
                ->once()
                ->andReturn(['success' => true, 'data' => []]);
        });

        $response = $this->post(route('cliente.actualizar'), $this->datosFormulario([
            'id_cliente' => 1,
        ]));

        $response->assertRedirect(route('admin.Cliente'));
        $response->assertSessionHas('mensaje', 'Cliente actualizado correctamente.');
    }
    // php artisan test --filter=ClienteControllerTest::update_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 8. update() redirige con mensaje de error cuando el servicio falla ──
    #[Test]
    public function update_redirige_con_mensaje_error_cuando_servicio_falla(): void
    {
        $this->sesionAdmin();

        $this->mock(ClienteService::class, function ($mock) {
            $mock->shouldReceive('actualizarCliente')
                ->once()
                ->andReturn(['success' => false, 'error' => 'HTTP 500']);
        });

        $response = $this->post(route('cliente.actualizar'), $this->datosFormulario([
            'id_cliente' => 1,
        ]));

        $response->assertRedirect(route('admin.Cliente'));
        $response->assertSessionHas('mensaje', 'Error: HTTP 500');
    }
    // php artisan test --filter=ClienteControllerTest::update_redirige_con_mensaje_error_cuando_servicio_falla

    // ── 9. destroy() redirige con mensaje de éxito cuando el servicio elimina correctamente ──
    #[Test]
    public function destroy_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();

        $this->mock(ClienteService::class, function ($mock) {
            $mock->shouldReceive('eliminarCliente')
                ->once()
                ->andReturn(['success' => true]);
        });

        $response = $this->post(route('cliente.eliminar'), ['id_cliente' => 1]);

        $response->assertRedirect(route('admin.Cliente'));
        $response->assertSessionHas('mensaje', 'Cliente eliminado correctamente.');
    }
    // php artisan test --filter=ClienteControllerTest::destroy_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 10. destroy() redirige con mensaje de error cuando el servicio falla ──
    #[Test]
    public function destroy_redirige_con_mensaje_error_cuando_servicio_falla(): void
    {
        $this->sesionAdmin();

        $this->mock(ClienteService::class, function ($mock) {
            $mock->shouldReceive('eliminarCliente')
                ->once()
                ->andReturn(['success' => false, 'error' => 'HTTP 500']);
        });

        $response = $this->post(route('cliente.eliminar'), ['id_cliente' => 1]);

        $response->assertRedirect(route('admin.Cliente'));
        $response->assertSessionHas('mensaje', 'Error: HTTP 500');
    }
    // php artisan test --filter=ClienteControllerTest::destroy_redirige_con_mensaje_error_cuando_servicio_falla

    // ── 11. buscar() retorna JSON con resultados de búsqueda ──
    #[Test]
    public function buscar_retorna_json_con_resultados(): void
    {
        $this->sesionAdmin();

        $this->mock(ClienteService::class, function ($mock) {
            $mock->shouldReceive('buscarCliente')
                ->once()
                ->with('Juan')
                ->andReturn([
                    ['id_cliente' => 1, 'nombre' => 'Juan'],
                ]);
        });

        $response = $this->getJson(route('cliente.buscar', ['dato' => 'Juan']));

        $response->assertStatus(200);
        $response->assertJson([['id_cliente' => 1, 'nombre' => 'Juan']]);
    }
    // php artisan test --filter=ClienteControllerTest::buscar_retorna_json_con_resultados
}

// php artisan test --filter=ClienteControllerTest