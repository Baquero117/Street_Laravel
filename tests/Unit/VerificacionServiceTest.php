<?php
namespace Tests\Unit\Registro;

use Tests\TestCase;
use App\Models\Registro\VerificacionService;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;

class VerificacionServiceTest extends TestCase
{
    // ── 1. validarCodigo() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function validar_codigo_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/verificacion/validar' => Http::response([], 200),
        ]);

        $service   = new VerificacionService();
        $resultado = $service->validarCodigo('juan@test.com', '123456');

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=VerificacionServiceTest::validar_codigo_retorna_success_true_cuando_api_exitosa

    // ── 2. validarCodigo() retorna error del body cuando la API responde HTTP 400 con mensaje ──
    #[Test]
    public function validar_codigo_retorna_error_del_body_cuando_api_400_con_mensaje(): void
    {
        Http::fake([
            '*/verificacion/validar' => Http::response(['error' => 'Código expirado.'], 400),
        ]);

        $service   = new VerificacionService();
        $resultado = $service->validarCodigo('juan@test.com', '000000');

        $this->assertFalse($resultado['success']);
        $this->assertEquals('Código expirado.', $resultado['error']);
    }
    // php artisan test --filter=VerificacionServiceTest::validar_codigo_retorna_error_del_body_cuando_api_400_con_mensaje

    // ── 3. validarCodigo() retorna error genérico cuando la API responde HTTP 400 sin mensaje ──
    #[Test]
    public function validar_codigo_retorna_error_generico_cuando_api_400_sin_mensaje(): void
    {
        Http::fake([
            '*/verificacion/validar' => Http::response([], 400),
        ]);

        $service   = new VerificacionService();
        $resultado = $service->validarCodigo('juan@test.com', '000000');

        $this->assertFalse($resultado['success']);
        $this->assertEquals('Código incorrecto o expirado.', $resultado['error']);
    }
    // php artisan test --filter=VerificacionServiceTest::validar_codigo_retorna_error_generico_cuando_api_400_sin_mensaje

    // ── 4. validarCodigo() retorna error genérico cuando la API responde HTTP 500 ──
    #[Test]
    public function validar_codigo_retorna_error_generico_cuando_api_500(): void
    {
        Http::fake([
            '*/verificacion/validar' => Http::response([], 500),
        ]);

        $service   = new VerificacionService();
        $resultado = $service->validarCodigo('juan@test.com', '123456');

        $this->assertFalse($resultado['success']);
        $this->assertEquals('Error al verificar el código. Intenta de nuevo.', $resultado['error']);
    }
    // php artisan test --filter=VerificacionServiceTest::validar_codigo_retorna_error_generico_cuando_api_500

    // ── 5. validarCodigo() retorna error de conexión cuando la API lanza excepción ──
    #[Test]
    public function validar_codigo_retorna_error_conexion_cuando_api_lanza_excepcion(): void
    {
        Http::fake([
            '*/verificacion/validar' => function () {
                throw new \Exception('Connection refused');
            },
        ]);

        $service   = new VerificacionService();
        $resultado = $service->validarCodigo('juan@test.com', '123456');

        $this->assertFalse($resultado['success']);
        $this->assertEquals('Error al conectar con el servidor.', $resultado['error']);
    }
    // php artisan test --filter=VerificacionServiceTest::validar_codigo_retorna_error_conexion_cuando_api_lanza_excepcion

    // ── 6. reenviarCodigo() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function reenviar_codigo_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/verificacion/reenviar' => Http::response([], 200),
        ]);

        $service   = new VerificacionService();
        $resultado = $service->reenviarCodigo('juan@test.com');

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=VerificacionServiceTest::reenviar_codigo_retorna_success_true_cuando_api_exitosa

    // ── 7. reenviarCodigo() retorna success=>false cuando la API falla ──
    #[Test]
    public function reenviar_codigo_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/verificacion/reenviar' => Http::response([], 500),
        ]);

        $service   = new VerificacionService();
        $resultado = $service->reenviarCodigo('juan@test.com');

        $this->assertFalse($resultado['success']);
        $this->assertEquals('No se pudo reenviar el código.', $resultado['error']);
    }
    // php artisan test --filter=VerificacionServiceTest::reenviar_codigo_retorna_false_cuando_api_falla

    // ── 8. reenviarCodigo() retorna error de conexión cuando la API lanza excepción ──
    #[Test]
    public function reenviar_codigo_retorna_error_conexion_cuando_api_lanza_excepcion(): void
    {
        Http::fake([
            '*/verificacion/reenviar' => function () {
                throw new \Exception('Connection refused');
            },
        ]);

        $service   = new VerificacionService();
        $resultado = $service->reenviarCodigo('juan@test.com');

        $this->assertFalse($resultado['success']);
        $this->assertEquals('Error al conectar con el servidor.', $resultado['error']);
    }
    // php artisan test --filter=VerificacionServiceTest::reenviar_codigo_retorna_error_conexion_cuando_api_lanza_excepcion
}

// php artisan test --filter=VerificacionServiceTest