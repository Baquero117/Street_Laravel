<?php
namespace Tests\Feature\Registro;

use Tests\TestCase;
use App\Models\Registro\VerificacionService;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class VerificacionControllerTest extends TestCase
{
    // ── 1. mostrar() redirige a registro si no hay correo en sesión ──
    #[Test]
    public function mostrar_redirige_a_registro_si_no_hay_correo_en_sesion(): void
    {
        $response = $this->get(route('verificacion.mostrar'));

        $response->assertRedirect(route('registro'));
    }
    // php artisan test --filter=VerificacionControllerTest::mostrar_redirige_a_registro_si_no_hay_correo_en_sesion

    // ── 2. mostrar() retorna la vista con el correo cuando hay sesión ──
    #[Test]
    public function mostrar_retorna_vista_con_correo_cuando_hay_sesion(): void
    {
        Session::put('correo_verificacion', 'juan@test.com');

        $response = $this->get(route('verificacion.mostrar'));

        $response->assertStatus(200);
        $response->assertViewIs('Registro.Verificacion');
        $response->assertViewHas('correo', 'juan@test.com');
    }
    // php artisan test --filter=VerificacionControllerTest::mostrar_retorna_vista_con_correo_cuando_hay_sesion

    // ── 3. validar() falla validación si el código no tiene exactamente 6 caracteres ──
    #[Test]
    public function validar_falla_validacion_si_codigo_no_tiene_6_caracteres(): void
    {
        Session::put('correo_verificacion', 'juan@test.com');

        $response = $this->post(route('verificacion.validar'), ['codigo' => '123']);

        $response->assertSessionHasErrors(['codigo']);
    }
    // php artisan test --filter=VerificacionControllerTest::validar_falla_validacion_si_codigo_no_tiene_6_caracteres

    // ── 4. validar() redirige a registro si no hay correo en sesión ──
    #[Test]
    public function validar_redirige_a_registro_si_no_hay_correo_en_sesion(): void
    {
        $response = $this->post(route('verificacion.validar'), ['codigo' => '123456']);

        $response->assertRedirect(route('registro'));
    }
    // php artisan test --filter=VerificacionControllerTest::validar_redirige_a_registro_si_no_hay_correo_en_sesion

    // ── 5. validar() redirige a login con mensaje cuando el código es correcto ──
    #[Test]
    public function validar_redirige_a_login_cuando_codigo_correcto(): void
    {
        Session::put('correo_verificacion', 'juan@test.com');

        $this->mock(VerificacionService::class, function ($mock) {
            $mock->shouldReceive('validarCodigo')
                ->once()
                ->andReturn(['success' => true]);
        });

        $response = $this->post(route('verificacion.validar'), ['codigo' => '123456']);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('mensaje', '¡Cuenta verificada correctamente! Ya puedes iniciar sesión.');
        $this->assertNull(Session::get('correo_verificacion'));
    }
    // php artisan test --filter=VerificacionControllerTest::validar_redirige_a_login_cuando_codigo_correcto

    // ── 6. validar() limpia el correo de sesión cuando la verificación es exitosa ──
    #[Test]
    public function validar_limpia_correo_de_sesion_cuando_verificacion_exitosa(): void
    {
        Session::put('correo_verificacion', 'juan@test.com');

        $this->mock(VerificacionService::class, function ($mock) {
            $mock->shouldReceive('validarCodigo')
                ->once()
                ->andReturn(['success' => true]);
        });

        $this->post(route('verificacion.validar'), ['codigo' => '123456']);

        $this->assertNull(Session::get('correo_verificacion'));
    }
    // php artisan test --filter=VerificacionControllerTest::validar_limpia_correo_de_sesion_cuando_verificacion_exitosa

    // ── 7. validar() redirige a verificacion con error cuando el código es incorrecto ──
    #[Test]
    public function validar_redirige_con_error_cuando_codigo_incorrecto(): void
    {
        Session::put('correo_verificacion', 'juan@test.com');

        $this->mock(VerificacionService::class, function ($mock) {
            $mock->shouldReceive('validarCodigo')
                ->once()
                ->andReturn(['success' => false, 'error' => 'Código incorrecto o expirado.']);
        });

        $response = $this->post(route('verificacion.validar'), ['codigo' => '000000']);

        $response->assertRedirect(route('verificacion.mostrar'));
        $response->assertSessionHas('error', 'Código incorrecto o expirado.');
    }
    // php artisan test --filter=VerificacionControllerTest::validar_redirige_con_error_cuando_codigo_incorrecto

    // ── 8. reenviar() redirige a registro si no hay correo en sesión ──
    #[Test]
    public function reenviar_redirige_a_registro_si_no_hay_correo_en_sesion(): void
    {
        $response = $this->post(route('verificacion.reenviar'));

        $response->assertRedirect(route('registro'));
    }
    // php artisan test --filter=VerificacionControllerTest::reenviar_redirige_a_registro_si_no_hay_correo_en_sesion

    // ── 9. reenviar() redirige a verificacion con mensaje cuando el reenvío es exitoso ──
    #[Test]
    public function reenviar_redirige_con_mensaje_cuando_exitoso(): void
    {
        Session::put('correo_verificacion', 'juan@test.com');

        $this->mock(VerificacionService::class, function ($mock) {
            $mock->shouldReceive('reenviarCodigo')
                ->once()
                ->andReturn(['success' => true]);
        });

        $response = $this->post(route('verificacion.reenviar'));

        $response->assertRedirect(route('verificacion.mostrar'));
        $response->assertSessionHas('mensaje', 'Código reenviado correctamente. Revisa tu correo.');
    }
    // php artisan test --filter=VerificacionControllerTest::reenviar_redirige_con_mensaje_cuando_exitoso

    // ── 10. reenviar() redirige a verificacion con error cuando el reenvío falla ──
    #[Test]
    public function reenviar_redirige_con_error_cuando_falla(): void
    {
        Session::put('correo_verificacion', 'juan@test.com');

        $this->mock(VerificacionService::class, function ($mock) {
            $mock->shouldReceive('reenviarCodigo')
                ->once()
                ->andReturn(['success' => false, 'error' => 'No se pudo reenviar el código.']);
        });

        $response = $this->post(route('verificacion.reenviar'));

        $response->assertRedirect(route('verificacion.mostrar'));
        $response->assertSessionHas('error', 'No se pudo reenviar el código.');
    }
    // php artisan test --filter=VerificacionControllerTest::reenviar_redirige_con_error_cuando_falla
}

// php artisan test --filter=VerificacionControllerTest