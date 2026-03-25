<?php
namespace Tests\Unit\Registro;

use Tests\TestCase;
use App\Models\Registro\RegistroService;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;

class RegistroServiceTest extends TestCase
{
    // ── Datos base reutilizables ──
    private function datosUsuario(): array
    {
        return [
            'Juan', 'Pérez', 'Antioquia', 'Medellín',
            'Calle 123', '3001234567', 'juan@test.com', 'pass1234'
        ];
    }

    // ── 1. registrarUsuario() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function registrar_usuario_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/cliente' => Http::response(['id_cliente' => 1], 200),
        ]);

        $service   = new RegistroService();
        $resultado = $service->registrarUsuario(...$this->datosUsuario());

        $this->assertTrue($resultado['success']);
        $this->assertArrayHasKey('data', $resultado);
    }
    // php artisan test --filter=RegistroServiceTest::registrar_usuario_retorna_success_true_cuando_api_exitosa

    // ── 2. registrarUsuario() retorna error de correo duplicado cuando la API responde HTTP 403 ──
    #[Test]
    public function registrar_usuario_retorna_error_correo_duplicado_cuando_api_403(): void
    {
        Http::fake([
            '*/cliente' => Http::response([], 403),
        ]);

        $service   = new RegistroService();
        $resultado = $service->registrarUsuario(...$this->datosUsuario());

        $this->assertFalse($resultado['success']);
        $this->assertEquals(
            'Este correo electrónico ya tiene una cuenta registrada.',
            $resultado['error']
        );
    }
    // php artisan test --filter=RegistroServiceTest::registrar_usuario_retorna_error_correo_duplicado_cuando_api_403

    // ── 3. registrarUsuario() retorna error de correo no encontrado cuando la API responde HTTP 404 ──
    #[Test]
    public function registrar_usuario_retorna_error_correo_no_encontrado_cuando_api_404(): void
    {
        Http::fake([
            '*/cliente' => Http::response([], 404),
        ]);

        $service   = new RegistroService();
        $resultado = $service->registrarUsuario(...$this->datosUsuario());

        $this->assertFalse($resultado['success']);
        $this->assertEquals(
            'La dirección de correo electrónico no fue encontrada. Verifica que sea correcta.',
            $resultado['error']
        );
    }
    // php artisan test --filter=RegistroServiceTest::registrar_usuario_retorna_error_correo_no_encontrado_cuando_api_404

    // ── 4. registrarUsuario() retorna error genérico cuando la API responde HTTP 500 ──
    #[Test]
    public function registrar_usuario_retorna_error_generico_cuando_api_500(): void
    {
        Http::fake([
            '*/cliente' => Http::response([], 500),
        ]);

        $service   = new RegistroService();
        $resultado = $service->registrarUsuario(...$this->datosUsuario());

        $this->assertFalse($resultado['success']);
        $this->assertEquals(
            'Ocurrió un error al registrar. Intenta de nuevo más tarde.',
            $resultado['error']
        );
    }
    // php artisan test --filter=RegistroServiceTest::registrar_usuario_retorna_error_generico_cuando_api_500
}

// php artisan test --filter=RegistroServiceTest