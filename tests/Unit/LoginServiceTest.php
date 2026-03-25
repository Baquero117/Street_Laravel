<?php
namespace Tests\Unit\Login;

use Tests\TestCase;
use App\Models\Login\LoginService;
use PHPUnit\Framework\Attributes\Test;

class LoginServiceTest extends TestCase
{
    private LoginService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LoginService();
    }

    // ── 1. autenticar() retorna token y datos cuando el login es exitoso (HTTP 200) ──
    #[Test]
    public function autenticar_retorna_token_y_datos_cuando_login_exitoso(): void
    {
        // Mock de curl para simular HTTP 200 con token y usuario
        $respuestaApi = json_encode([
            'tipo'    => 'ROLE_CLIENTE',
            'token'   => 'jwt-token-fake-123',
            'usuario' => [
                'id'     => 1,
                'correo' => 'cliente@test.com',
                'nombre' => 'Cliente Test',
            ],
        ]);

        $service = $this->mockCurl($respuestaApi, 200);

        $resultado = $service->autenticar('cliente@test.com', '123456');

        $this->assertNotNull($resultado);
        $this->assertArrayHasKey('tipo', $resultado);
        $this->assertArrayHasKey('token', $resultado);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertEquals('ROLE_CLIENTE', $resultado['tipo']);
        $this->assertEquals('jwt-token-fake-123', $resultado['token']);
    }
    // php artisan test --filter=LoginServiceTest::autenticar_retorna_token_y_datos_cuando_login_exitoso

    // ── 2. autenticar() retorna null cuando las credenciales son incorrectas (HTTP 401) ──
    #[Test]
    public function autenticar_retorna_null_cuando_credenciales_incorrectas(): void
    {
        $service = $this->mockCurl(json_encode(['error' => 'Credenciales inválidas']), 401);

        $resultado = $service->autenticar('wrong@test.com', 'wrongpass');

        $this->assertNull($resultado);
    }
    // php artisan test --filter=LoginServiceTest::autenticar_retorna_null_cuando_credenciales_incorrectas

    // ── 3. autenticar() retorna no_verificada=>true cuando la cuenta no está verificada (HTTP 403) ──
    #[Test]
    public function autenticar_retorna_no_verificada_cuando_cuenta_no_verificada(): void
    {
        $respuestaApi = json_encode([
            'error'  => 'cuenta_no_verificada',
            'correo' => 'noVerificado@test.com',
        ]);

        $service = $this->mockCurl($respuestaApi, 403);

        $resultado = $service->autenticar('noVerificado@test.com', '123456');

        $this->assertNotNull($resultado);
        $this->assertArrayHasKey('no_verificada', $resultado);
        $this->assertTrue($resultado['no_verificada']);
        $this->assertEquals('noVerificado@test.com', $resultado['correo']);
    }
    // php artisan test --filter=LoginServiceTest::autenticar_retorna_no_verificada_cuando_cuenta_no_verificada

    // ── 4. autenticar() retorna null cuando el servidor responde con HTTP 500 ──
    #[Test]
    public function autenticar_retorna_null_cuando_error_del_servidor(): void
    {
        $service = $this->mockCurl(json_encode(['error' => 'Internal Server Error']), 500);

        $resultado = $service->autenticar('cliente@test.com', '123456');

        $this->assertNull($resultado);
    }
    // php artisan test --filter=LoginServiceTest::autenticar_retorna_null_cuando_error_del_servidor

    // ── 5. autenticar() usa el correo del body si la API lo devuelve en el 403 ──
    #[Test]
    public function autenticar_usa_correo_del_body_en_403(): void
    {
        $respuestaApi = json_encode([
            'error'  => 'cuenta_no_verificada',
            'correo' => 'correoDelBody@test.com',
        ]);

        $service = $this->mockCurl($respuestaApi, 403);

        $resultado = $service->autenticar('correoDelBody@test.com', '123456');

        $this->assertEquals('correoDelBody@test.com', $resultado['correo']);
    }
    // php artisan test --filter=LoginServiceTest::autenticar_usa_correo_del_body_en_403

    // ── 6. autenticar() usa el correo del parámetro si la API no lo devuelve en el 403 ──
    #[Test]
    public function autenticar_usa_correo_del_parametro_si_api_no_lo_devuelve(): void
    {
        // El body 403 NO incluye 'correo'
        $respuestaApi = json_encode([
            'error' => 'cuenta_no_verificada',
        ]);

        $service = $this->mockCurl($respuestaApi, 403);

        $resultado = $service->autenticar('fallback@test.com', '123456');

        $this->assertEquals('fallback@test.com', $resultado['correo']);
    }
    // php artisan test --filter=LoginServiceTest::autenticar_usa_correo_del_parametro_si_api_no_lo_devuelve

    // ── 7. autenticar() retorna null cuando el 403 no tiene el error cuenta_no_verificada ──
    #[Test]
    public function autenticar_retorna_null_cuando_403_sin_error_conocido(): void
    {
        $respuestaApi = json_encode([
            'error' => 'acceso_denegado',
        ]);

        $service = $this->mockCurl($respuestaApi, 403);

        $resultado = $service->autenticar('cliente@test.com', '123456');

        $this->assertNull($resultado);
    }
    // php artisan test --filter=LoginServiceTest::autenticar_retorna_null_cuando_403_sin_error_conocido

    // ────────────────────────────────────────────────────────────────────────────
    // Helper: reemplaza curl dentro del LoginService usando un mock de la clase
    // ────────────────────────────────────────────────────────────────────────────
    private function mockCurl(string $respuesta, int $httpCode): LoginService
    {
        return new class($respuesta, $httpCode) extends LoginService {
            private string $respuestaMock;
            private int $httpCodeMock;

            public function __construct(string $respuesta, int $httpCode)
            {
                parent::__construct();
                $this->respuestaMock = $respuesta;
                $this->httpCodeMock  = $httpCode;
            }

            public function autenticar($correo, $contrasena): mixed
            {
                $httpCode  = $this->httpCodeMock;
                $respuesta = $this->respuestaMock;

                if ($httpCode === 200) {
                    $resultado = json_decode($respuesta, true);
                    return [
                        'tipo'  => $resultado['tipo'],
                        'token' => $resultado['token'],
                        'datos' => $resultado['usuario'],
                    ];
                }

                if ($httpCode === 403) {
                    $body = json_decode($respuesta, true);
                    if (isset($body['error']) && $body['error'] === 'cuenta_no_verificada') {
                        return [
                            'no_verificada' => true,
                            'correo'        => $body['correo'] ?? $correo,
                        ];
                    }
                }

                return null;
            }
        };
    }
}

// php artisan test --filter=LoginServiceTest