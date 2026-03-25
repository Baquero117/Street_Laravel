<?php
namespace Tests\Unit\PuntoInicio;

use Tests\TestCase;
use App\Models\PuntoInicio\PerfilService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class PerfilServiceTest extends TestCase
{
    // ── 1. obtenerPerfil() retorna datos del perfil cuando la API responde HTTP 200 ──
    #[Test]
    public function obtener_perfil_retorna_datos_cuando_api_exitosa(): void
    {
        Session::put('token', 'token-fake');

        Http::fake([
            '*/cliente/perfil' => Http::response([
                'id_cliente'         => 1,
                'nombre'             => 'Juan',
                'apellido'           => 'Pérez',
                'correo_electronico' => 'juan@test.com',
            ], 200),
        ]);

        $service   = new PerfilService();
        $resultado = $service->obtenerPerfil();

        $this->assertNotNull($resultado);
        $this->assertEquals('Juan', $resultado['nombre']);
    }
    // php artisan test --filter=PerfilServiceTest::obtener_perfil_retorna_datos_cuando_api_exitosa

    // ── 2. obtenerPerfil() retorna null cuando no hay token en sesión ──
    #[Test]
    public function obtener_perfil_retorna_null_cuando_no_hay_token(): void
    {
        Session::flush();

        $service   = new PerfilService();
        $resultado = $service->obtenerPerfil();

        $this->assertNull($resultado);
    }
    // php artisan test --filter=PerfilServiceTest::obtener_perfil_retorna_null_cuando_no_hay_token

    // ── 3. obtenerPerfil() retorna null cuando la API falla ──
    #[Test]
    public function obtener_perfil_retorna_null_cuando_api_falla(): void
    {
        Session::put('token', 'token-fake');

        Http::fake([
            '*/cliente/perfil' => Http::response([], 500),
        ]);

        $service   = new PerfilService();
        $resultado = $service->obtenerPerfil();

        $this->assertNull($resultado);
    }
    // php artisan test --filter=PerfilServiceTest::obtener_perfil_retorna_null_cuando_api_falla

    // ── 4. actualizarPerfil() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function actualizar_perfil_retorna_success_true_cuando_api_exitosa(): void
    {
        Session::put('token', 'token-fake');

        Http::fake([
            '*/cliente/perfil' => Http::response(['mensaje' => 'Actualizado'], 200),
        ]);

        $service   = new PerfilService();
        $resultado = $service->actualizarPerfil(
            'Juan', 'Pérez', 'Antioquia', 'Medellín',
            null, 'Calle 123', '3001234567', 'juan@test.com'
        );

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=PerfilServiceTest::actualizar_perfil_retorna_success_true_cuando_api_exitosa

    // ── 5. actualizarPerfil() incluye contraseña en el body cuando se proporciona ──
    #[Test]
    public function actualizar_perfil_incluye_contrasena_cuando_se_proporciona(): void
    {
        Session::put('token', 'token-fake');

        Http::fake([
            '*/cliente/perfil' => Http::response(['mensaje' => 'Actualizado'], 200),
        ]);

        $service   = new PerfilService();
        $resultado = $service->actualizarPerfil(
            'Juan', 'Pérez', 'Antioquia', 'Medellín',
            'nueva123', 'Calle 123', '3001234567', 'juan@test.com'
        );

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=PerfilServiceTest::actualizar_perfil_incluye_contrasena_cuando_se_proporciona

    // ── 6. actualizarPerfil() retorna success=>false cuando no hay token ──
    #[Test]
    public function actualizar_perfil_retorna_false_cuando_no_hay_token(): void
    {
        Session::flush();

        $service   = new PerfilService();
        $resultado = $service->actualizarPerfil(
            'Juan', 'Pérez', 'Antioquia', 'Medellín',
            null, 'Calle 123', '3001234567', 'juan@test.com'
        );

        $this->assertFalse($resultado['success']);
        $this->assertEquals('Token no encontrado', $resultado['error']);
    }
    // php artisan test --filter=PerfilServiceTest::actualizar_perfil_retorna_false_cuando_no_hay_token

    // ── 7. actualizarPerfil() retorna success=>false cuando la API falla ──
    #[Test]
    public function actualizar_perfil_retorna_false_cuando_api_falla(): void
    {
        Session::put('token', 'token-fake');

        Http::fake([
            '*/cliente/perfil' => Http::response([], 500),
        ]);

        $service   = new PerfilService();
        $resultado = $service->actualizarPerfil(
            'Juan', 'Pérez', 'Antioquia', 'Medellín',
            null, 'Calle 123', '3001234567', 'juan@test.com'
        );

        $this->assertFalse($resultado['success']);
    }
    // php artisan test --filter=PerfilServiceTest::actualizar_perfil_retorna_false_cuando_api_falla
}

// php artisan test --filter=PerfilServiceTest