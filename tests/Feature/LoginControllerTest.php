<?php
namespace Tests\Feature\Login;

use Tests\TestCase;
use App\Models\Login\LoginService;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class LoginControllerTest extends TestCase
{
    // ── 1. mostrar() retorna la vista login ──
    #[Test]
    public function mostrar_retorna_vista_login(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('login.login');
    }
    // php artisan test --filter=LoginControllerTest::mostrar_retorna_vista_login

    // ── 2. procesar() redirige a verificacion si la cuenta no está verificada ──
    #[Test]
    public function procesar_redirige_a_verificacion_si_cuenta_no_verificada(): void
    {
        $this->mock(LoginService::class, function ($mock) {
            $mock->shouldReceive('autenticar')
                ->once()
                ->andReturn([
                    'no_verificada' => true,
                    'correo'        => 'noVerificado@test.com',
                ]);
        });

        $response = $this->post(route('login.procesar'), [
            'correo_electronico' => 'noVerificado@test.com',
            'contrasena'         => '123456',
        ]);

        $response->assertRedirect(route('verificacion.mostrar'));
        $this->assertEquals('noVerificado@test.com', Session::get('correo_verificacion'));
    }
    // php artisan test --filter=LoginControllerTest::procesar_redirige_a_verificacion_si_cuenta_no_verificada

    // ── 3. procesar() guarda sesión y redirige a inicio cuando el login es exitoso como cliente ──
    #[Test]
    public function procesar_redirige_a_inicio_cuando_login_exitoso_cliente(): void
    {
        $this->mock(LoginService::class, function ($mock) {
            $mock->shouldReceive('autenticar')
                ->once()
                ->andReturn([
                    'tipo'  => 'cliente',
                    'token' => 'jwt-fake-token',
                    'datos' => [
                        'id_cliente'         => 5,
                        'nombre'             => 'Cliente Test',
                        'correo_electronico' => 'cliente@test.com',
                    ],
                ]);
        });

        $response = $this->post(route('login.procesar'), [
            'correo_electronico' => 'cliente@test.com',
            'contrasena'         => '123456',
        ]);

        $response->assertRedirect(route('inicio'));
        $this->assertEquals('jwt-fake-token', Session::get('token'));
        $this->assertEquals('cliente', Session::get('usuario_tipo'));
        $this->assertEquals('Cliente Test', Session::get('usuario_nombre'));
    }
    // php artisan test --filter=LoginControllerTest::procesar_redirige_a_inicio_cuando_login_exitoso_cliente

    // ── 4. procesar() redirige a admin.Reportes cuando el login es exitoso como administrador ──
    #[Test]
    public function procesar_redirige_a_admin_cuando_login_exitoso_administrador(): void
    {
        $this->mock(LoginService::class, function ($mock) {
            $mock->shouldReceive('autenticar')
                ->once()
                ->andReturn([
                    'tipo'  => 'administrador',
                    'token' => 'jwt-admin-token',
                    'datos' => [
                        'id_vendedor'        => 1,
                        'nombre'             => 'Admin Test',
                        'correo_electronico' => 'admin@test.com',
                    ],
                ]);
        });

        $response = $this->post(route('login.procesar'), [
            'correo_electronico' => 'admin@test.com',
            'contrasena'         => 'admin123',
        ]);

        $response->assertRedirect(route('admin.Reportes'));
        $this->assertEquals('administrador', Session::get('usuario_tipo'));
    }
    // php artisan test --filter=LoginControllerTest::procesar_redirige_a_admin_cuando_login_exitoso_administrador

    // ── 5. procesar() redirige a login con error cuando las credenciales son inválidas ──
    #[Test]
    public function procesar_redirige_a_login_con_error_cuando_credenciales_invalidas(): void
    {
        $this->mock(LoginService::class, function ($mock) {
            $mock->shouldReceive('autenticar')
                ->once()
                ->andReturn(null);
        });

        $response = $this->post(route('login.procesar'), [
            'correo_electronico' => 'wrong@test.com',
            'contrasena'         => 'wrongpass',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Credenciales inválidas');
    }
    // php artisan test --filter=LoginControllerTest::procesar_redirige_a_login_con_error_cuando_credenciales_invalidas

    // ── 6. procesar() falla la validación si el correo no es válido ──
    #[Test]
    public function procesar_falla_validacion_si_correo_invalido(): void
    {
        $response = $this->post(route('login.procesar'), [
            'correo_electronico' => 'no-es-un-correo',
            'contrasena'         => '123456',
        ]);

        $response->assertSessionHasErrors(['correo_electronico']);
    }
    // php artisan test --filter=LoginControllerTest::procesar_falla_validacion_si_correo_invalido

    // ── 7. logout() limpia la sesión y redirige a inicio ──
    #[Test]
    public function logout_limpia_sesion_y_redirige_a_inicio(): void
    {
        Session::put('token', 'jwt-fake-token');
        Session::put('usuario_tipo', 'cliente');

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('inicio'));
        $this->assertNull(Session::get('token'));
        $this->assertNull(Session::get('usuario_tipo'));
    }
    // php artisan test --filter=LoginControllerTest::logout_limpia_sesion_y_redirige_a_inicio
}

// php artisan test --filter=LoginControllerTest