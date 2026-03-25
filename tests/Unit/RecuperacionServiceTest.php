<?php
namespace Tests\Unit\Recuperacion;

use Tests\TestCase;
use App\Models\Recuperacion\RecuperacionService;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;

class RecuperacionServiceTest extends TestCase
{
    // ── 1. solicitarRecuperacion() retorna success=>true independientemente de la respuesta de la API ──
    #[Test]
    public function solicitar_recuperacion_retorna_success_true_cuando_api_responde(): void
    {
        Http::fake([
            '*/recuperacion/solicitar' => Http::response([], 200),
        ]);

        $service   = new RecuperacionService();
        $resultado = $service->solicitarRecuperacion('juan@test.com');

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=RecuperacionServiceTest::solicitar_recuperacion_retorna_success_true_cuando_api_responde

    // ── 2. solicitarRecuperacion() retorna success=>true aunque la API falle (por seguridad) ──
    #[Test]
    public function solicitar_recuperacion_retorna_success_true_aunque_api_falle(): void
    {
        Http::fake([
            '*/recuperacion/solicitar' => Http::response([], 500),
        ]);

        $service   = new RecuperacionService();
        $resultado = $service->solicitarRecuperacion('juan@test.com');

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=RecuperacionServiceTest::solicitar_recuperacion_retorna_success_true_aunque_api_falle

    // ── 3. solicitarRecuperacion() retorna success=>false cuando la API lanza excepción ──
    #[Test]
    public function solicitar_recuperacion_retorna_false_cuando_api_lanza_excepcion(): void
    {
        Http::fake([
            '*/recuperacion/solicitar' => function () {
                throw new \Exception('Connection refused');
            },
        ]);

        $service   = new RecuperacionService();
        $resultado = $service->solicitarRecuperacion('juan@test.com');

        $this->assertFalse($resultado['success']);
        $this->assertEquals('Error al conectar con el servidor.', $resultado['error']);
    }
    // php artisan test --filter=RecuperacionServiceTest::solicitar_recuperacion_retorna_false_cuando_api_lanza_excepcion

    // ── 4. restablecerContrasena() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function restablecer_contrasena_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/recuperacion/restablecer' => Http::response([], 200),
        ]);

        $service   = new RecuperacionService();
        $resultado = $service->restablecerContrasena('token-valido', 'nueva123');

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=RecuperacionServiceTest::restablecer_contrasena_retorna_success_true_cuando_api_exitosa

    // ── 5. restablecerContrasena() retorna error de token inválido cuando la API responde HTTP 400 ──
    #[Test]
    public function restablecer_contrasena_retorna_error_token_invalido_cuando_api_400(): void
    {
        Http::fake([
            '*/recuperacion/restablecer' => Http::response([], 400),
        ]);

        $service   = new RecuperacionService();
        $resultado = $service->restablecerContrasena('token-expirado', 'nueva123');

        $this->assertFalse($resultado['success']);
        $this->assertEquals('El token es inválido o ha expirado.', $resultado['error']);
    }
    // php artisan test --filter=RecuperacionServiceTest::restablecer_contrasena_retorna_error_token_invalido_cuando_api_400

    // ── 6. restablecerContrasena() retorna error genérico cuando la API responde HTTP 500 ──
    #[Test]
    public function restablecer_contrasena_retorna_error_generico_cuando_api_500(): void
    {
        Http::fake([
            '*/recuperacion/restablecer' => Http::response([], 500),
        ]);

        $service   = new RecuperacionService();
        $resultado = $service->restablecerContrasena('token-valido', 'nueva123');

        $this->assertFalse($resultado['success']);
        $this->assertEquals('Error al restablecer la contraseña. Intenta de nuevo.', $resultado['error']);
    }
    // php artisan test --filter=RecuperacionServiceTest::restablecer_contrasena_retorna_error_generico_cuando_api_500

    // ── 7. restablecerContrasena() retorna success=>false cuando la API lanza excepción ──
    #[Test]
    public function restablecer_contrasena_retorna_false_cuando_api_lanza_excepcion(): void
    {
        Http::fake([
            '*/recuperacion/restablecer' => function () {
                throw new \Exception('Connection refused');
            },
        ]);

        $service   = new RecuperacionService();
        $resultado = $service->restablecerContrasena('token-valido', 'nueva123');

        $this->assertFalse($resultado['success']);
        $this->assertEquals('Error al conectar con el servidor.', $resultado['error']);
    }
    // php artisan test --filter=RecuperacionServiceTest::restablecer_contrasena_retorna_false_cuando_api_lanza_excepcion
}

// php artisan test --filter=RecuperacionServiceTest