<?php
namespace Tests\Feature\Recuperacion;

use Tests\TestCase;
use App\Models\Recuperacion\RecuperacionService;
use PHPUnit\Framework\Attributes\Test;

class RecuperacionControllerTest extends TestCase
{
    // ── 1. mostrarSolicitud() retorna la vista de solicitud ──
    #[Test]
    public function mostrar_solicitud_retorna_vista(): void
    {
        $response = $this->get(route('recuperacion.solicitud'));

        $response->assertStatus(200);
        $response->assertViewIs('recuperacion.solicitud');
    }
    // php artisan test --filter=RecuperacionControllerTest::mostrar_solicitud_retorna_vista

    // ── 2. procesarSolicitud() falla validación si el correo es inválido ──
    #[Test]
    public function procesar_solicitud_falla_validacion_si_correo_invalido(): void
    {
        $response = $this->post(route('recuperacion.procesar-solicitud'), [
            'correo_electronico' => 'no-es-un-correo',
        ]);

        $response->assertSessionHasErrors(['correo_electronico']);
    }
    // php artisan test --filter=RecuperacionControllerTest::procesar_solicitud_falla_validacion_si_correo_invalido

    // ── 3. procesarSolicitud() redirige siempre con el mismo mensaje por seguridad ──
    #[Test]
    public function procesar_solicitud_redirige_con_mensaje_generico(): void
    {
        $this->mock(RecuperacionService::class, function ($mock) {
            $mock->shouldReceive('solicitarRecuperacion')
                ->once()
                ->andReturn(['success' => true]);
        });

        $response = $this->post(route('recuperacion.procesar-solicitud'), [
            'correo_electronico' => 'juan@test.com',
        ]);

        $response->assertRedirect(route('recuperacion.solicitud'));
        $response->assertSessionHas('mensaje', 'Si el correo existe, recibirás un enlace para restablecer tu contraseña.');
    }
    // php artisan test --filter=RecuperacionControllerTest::procesar_solicitud_redirige_con_mensaje_generico

    // ── 4. procesarSolicitud() redirige con el mismo mensaje aunque el servicio falle ──
    #[Test]
    public function procesar_solicitud_redirige_con_mensaje_generico_aunque_servicio_falle(): void
    {
        $this->mock(RecuperacionService::class, function ($mock) {
            $mock->shouldReceive('solicitarRecuperacion')
                ->once()
                ->andReturn(['success' => false, 'error' => 'Error al conectar']);
        });

        $response = $this->post(route('recuperacion.procesar-solicitud'), [
            'correo_electronico' => 'juan@test.com',
        ]);

        $response->assertRedirect(route('recuperacion.solicitud'));
        $response->assertSessionHas('mensaje', 'Si el correo existe, recibirás un enlace para restablecer tu contraseña.');
    }
    // php artisan test --filter=RecuperacionControllerTest::procesar_solicitud_redirige_con_mensaje_generico_aunque_servicio_falle

    // ── 5. mostrarRestablecimiento() redirige a login si no hay token en la query ──
    #[Test]
    public function mostrar_restablecimiento_redirige_a_login_si_no_hay_token(): void
    {
        $response = $this->get(route('recuperacion.restablecer'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Token inválido o expirado.');
    }
    // php artisan test --filter=RecuperacionControllerTest::mostrar_restablecimiento_redirige_a_login_si_no_hay_token

    // ── 6. mostrarRestablecimiento() retorna la vista con el token cuando es válido ──
    #[Test]
    public function mostrar_restablecimiento_retorna_vista_cuando_token_presente(): void
    {
        $response = $this->get(route('recuperacion.restablecer') . '?token=abc123');

        $response->assertStatus(200);
        $response->assertViewIs('recuperacion.restablecer');
        $response->assertViewHas('token', 'abc123');
    }
    // php artisan test --filter=RecuperacionControllerTest::mostrar_restablecimiento_retorna_vista_cuando_token_presente

    // ── 7. procesarRestablecimiento() falla validación si las contraseñas no coinciden ──
    #[Test]
    public function procesar_restablecimiento_falla_validacion_si_contrasenas_no_coinciden(): void
    {
        $response = $this->post(route('recuperacion.procesar-restablecimiento'), [
            'token'              => 'abc123',
            'contrasena'         => 'nueva123',
            'contrasena_confirm' => 'diferente456',
        ]);

        $response->assertSessionHasErrors(['contrasena_confirm']);
    }
    // php artisan test --filter=RecuperacionControllerTest::procesar_restablecimiento_falla_validacion_si_contrasenas_no_coinciden

    // ── 8. procesarRestablecimiento() redirige a login con mensaje cuando el restablecimiento es exitoso ──
    #[Test]
    public function procesar_restablecimiento_redirige_a_login_cuando_exitoso(): void
    {
        $this->mock(RecuperacionService::class, function ($mock) {
            $mock->shouldReceive('restablecerContrasena')
                ->once()
                ->andReturn(['success' => true]);
        });

        $response = $this->post(route('recuperacion.procesar-restablecimiento'), [
            'token'              => 'abc123',
            'contrasena'         => 'nueva1234',
            'contrasena_confirm' => 'nueva1234',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('mensaje', 'Contraseña actualizada correctamente. Ya puedes iniciar sesión.');
    }
    // php artisan test --filter=RecuperacionControllerTest::procesar_restablecimiento_redirige_a_login_cuando_exitoso

    // ── 9. procesarRestablecimiento() redirige de vuelta con error cuando el servicio falla ──
    #[Test]
    public function procesar_restablecimiento_redirige_con_error_cuando_servicio_falla(): void
    {
        $this->mock(RecuperacionService::class, function ($mock) {
            $mock->shouldReceive('restablecerContrasena')
                ->once()
                ->andReturn(['success' => false, 'error' => 'El token es inválido o ha expirado.']);
        });

        $response = $this->post(route('recuperacion.procesar-restablecimiento'), [
            'token'              => 'token-expirado',
            'contrasena'         => 'nueva1234',
            'contrasena_confirm' => 'nueva1234',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'El token es inválido o ha expirado.');
    }
    // php artisan test --filter=RecuperacionControllerTest::procesar_restablecimiento_redirige_con_error_cuando_servicio_falla
}

// php artisan test --filter=RecuperacionControllerTest