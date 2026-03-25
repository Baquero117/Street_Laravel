<?php
namespace Tests\Feature\PuntoInicio;

use Tests\TestCase;
use App\Models\PuntoInicio\PerfilService;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class PerfilControllerTest extends TestCase
{
    // ── Helper de sesión ──
    private function sesionCliente(): void
    {
        Session::put('token', 'token-fake');
        Session::put('usuario_tipo', 'cliente');
        Session::put('usuario_id', 1);
        Session::put('usuario_nombre', 'Juan');
    }

    private function perfilMock(): array
    {
        return [
            'id_cliente'         => 1,
            'nombre'             => 'Juan',
            'apellido'           => 'Pérez',
            'correo_electronico' => 'juan@test.com',
            'telefono'           => '3001234567',
            'direccion'          => 'Calle 123',
            'departamento'       => 'Antioquia',
            'municipio'          => 'Medellín',
        ];
    }

    // ── 1. mostrar() redirige a login si no hay token ──
    #[Test]
    public function mostrar_redirige_a_login_si_no_hay_token(): void
    {
        $response = $this->get(route('perfil'));

        $response->assertRedirect(route('login'));
    }
    // php artisan test --filter=PerfilControllerTest::mostrar_redirige_a_login_si_no_hay_token

    // ── 2. mostrar() retorna la vista de perfil cuando el cliente está autenticado ──
    #[Test]
    public function mostrar_retorna_vista_perfil_cuando_autenticado(): void
    {
        $this->sesionCliente();

        $this->mock(PerfilService::class, function ($mock) {
            $mock->shouldReceive('obtenerPerfil')
                ->once()
                ->andReturn($this->perfilMock());
        });

        $response = $this->get(route('perfil'));

        $response->assertStatus(200);
        $response->assertViewIs('PuntoInicio.Cliente.Perfil');
        $response->assertViewHas('perfil');
    }
    // php artisan test --filter=PerfilControllerTest::mostrar_retorna_vista_perfil_cuando_autenticado

    // ── 3. mostrar() redirige a inicio si el servicio no retorna perfil ──
    #[Test]
    public function mostrar_redirige_a_inicio_si_perfil_es_null(): void
    {
        $this->sesionCliente();

        $this->mock(PerfilService::class, function ($mock) {
            $mock->shouldReceive('obtenerPerfil')
                ->once()
                ->andReturn(null);
        });

        $response = $this->get(route('perfil'));

        $response->assertRedirect(route('inicio'));
    }
    // php artisan test --filter=PerfilControllerTest::mostrar_redirige_a_inicio_si_perfil_es_null

    // ── 4. mostrarCuenta() redirige a login si no hay token ──
    #[Test]
    public function mostrar_cuenta_redirige_a_login_si_no_hay_token(): void
    {
        $response = $this->get(route('cuenta'));

        $response->assertRedirect(route('login'));
    }
    // php artisan test --filter=PerfilControllerTest::mostrar_cuenta_redirige_a_login_si_no_hay_token

    // ── 5. mostrarCuenta() retorna la vista Cuenta cuando el cliente está autenticado ──
    #[Test]
    public function mostrar_cuenta_retorna_vista_cuenta_cuando_autenticado(): void
    {
        $this->sesionCliente();

        $this->mock(PerfilService::class, function ($mock) {
            $mock->shouldReceive('obtenerPerfil')
                ->once()
                ->andReturn($this->perfilMock());
        });

        $response = $this->get(route('cuenta'));

        $response->assertStatus(200);
        $response->assertViewIs('PuntoInicio.Cliente.Cuenta');
        $response->assertViewHas('perfil');
    }
    // php artisan test --filter=PerfilControllerTest::mostrar_cuenta_retorna_vista_cuenta_cuando_autenticado

    // ── 6. mostrarCuenta() redirige a inicio si el servicio no retorna perfil ──
    #[Test]
    public function mostrar_cuenta_redirige_a_inicio_si_perfil_es_null(): void
    {
        $this->sesionCliente();

        $this->mock(PerfilService::class, function ($mock) {
            $mock->shouldReceive('obtenerPerfil')
                ->once()
                ->andReturn(null);
        });

        $response = $this->get(route('cuenta'));

        $response->assertRedirect(route('inicio'));
    }
    // php artisan test --filter=PerfilControllerTest::mostrar_cuenta_redirige_a_inicio_si_perfil_es_null

    // ── 7. actualizar() redirige a login si no hay token (interceptado por checkAuth) ──
    #[Test]
    public function actualizar_redirige_a_login_si_no_hay_token(): void
    {
        $response = $this->post(route('perfil.actualizar'), []);

        $response->assertStatus(302);
    }
    // php artisan test --filter=PerfilControllerTest::actualizar_redirige_a_login_si_no_hay_token

    // ── 8. actualizar() falla validación si faltan campos requeridos ──
    #[Test]
    public function actualizar_falla_validacion_si_faltan_campos(): void
    {
        $this->sesionCliente();

        $response = $this->post(route('perfil.actualizar'), []);

        $response->assertSessionHasErrors([
            'nombre', 'apellido', 'departamento', 'municipio',
            'direccion', 'telefono', 'correo_electronico',
        ]);
    }
    // php artisan test --filter=PerfilControllerTest::actualizar_falla_validacion_si_faltan_campos

    // ── 9. actualizar() redirige con success cuando el servicio actualiza correctamente ──
    #[Test]
    public function actualizar_redirige_con_success_cuando_servicio_exitoso(): void
    {
        $this->sesionCliente();

        $this->mock(PerfilService::class, function ($mock) {
            $mock->shouldReceive('actualizarPerfil')
                ->once()
                ->andReturn(['success' => true]);
        });

        $response = $this->post(route('perfil.actualizar'), [
            'nombre'             => 'Juan',
            'apellido'           => 'Pérez',
            'departamento'       => 'Antioquia',
            'municipio'          => 'Medellín',
            'direccion'          => 'Calle 123',
            'telefono'           => '3001234567',
            'correo_electronico' => 'juan@test.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Datos actualizados correctamente.');
    }
    // php artisan test --filter=PerfilControllerTest::actualizar_redirige_con_success_cuando_servicio_exitoso

    // ── 10. actualizar() redirige con error cuando el servicio falla ──
    #[Test]
    public function actualizar_redirige_con_error_cuando_servicio_falla(): void
    {
        $this->sesionCliente();

        $this->mock(PerfilService::class, function ($mock) {
            $mock->shouldReceive('actualizarPerfil')
                ->once()
                ->andReturn(['success' => false, 'error' => 'HTTP 500']);
        });

        $response = $this->post(route('perfil.actualizar'), [
            'nombre'             => 'Juan',
            'apellido'           => 'Pérez',
            'departamento'       => 'Antioquia',
            'municipio'          => 'Medellín',
            'direccion'          => 'Calle 123',
            'telefono'           => '3001234567',
            'correo_electronico' => 'juan@test.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'No se pudieron actualizar los datos');
    }
    // php artisan test --filter=PerfilControllerTest::actualizar_redirige_con_error_cuando_servicio_falla
}

// php artisan test --filter=PerfilControllerTest