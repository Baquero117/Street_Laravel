<?php
namespace Tests\Unit\Administrador;

use Tests\TestCase;
use App\Models\Administrador\ClienteService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class ClienteServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Session::put('token', 'token-fake');
    }

    // ── Datos base reutilizables ──
    private function datosCliente(): array
    {
        return [
            'Juan', 'Pérez', 'pass1234', 'Antioquia',
            'Medellín', 'Calle 123', '3001234567', 'juan@test.com'
        ];
    }

    // ── 1. obtenerClientes() retorna array de clientes cuando la API responde HTTP 200 ──
    #[Test]
    public function obtener_clientes_retorna_array_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/cliente' => Http::response([
                ['id_cliente' => 1, 'nombre' => 'Juan'],
                ['id_cliente' => 2, 'nombre' => 'Maria'],
            ], 200),
        ]);

        $service   = new ClienteService();
        $resultado = $service->obtenerClientes();

        $this->assertTrue($resultado['success']);
        $this->assertCount(2, $resultado['data']);
    }
    // php artisan test --filter=ClienteServiceTest::obtener_clientes_retorna_array_cuando_api_exitosa

    // ── 2. obtenerClientes() envuelve objeto único en array cuando la API retorna un solo cliente ──
    #[Test]
    public function obtener_clientes_envuelve_objeto_unico_en_array(): void
    {
        Http::fake([
            '*/cliente' => Http::response(['id_cliente' => 1, 'nombre' => 'Juan'], 200),
        ]);

        $service   = new ClienteService();
        $resultado = $service->obtenerClientes();

        $this->assertTrue($resultado['success']);
        $this->assertCount(1, $resultado['data']);
        $this->assertEquals('Juan', $resultado['data'][0]['nombre']);
    }
    // php artisan test --filter=ClienteServiceTest::obtener_clientes_envuelve_objeto_unico_en_array

    // ── 3. obtenerClientes() retorna success=>false cuando la API falla ──
    #[Test]
    public function obtener_clientes_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/cliente' => Http::response([], 500),
        ]);

        $service   = new ClienteService();
        $resultado = $service->obtenerClientes();

        $this->assertFalse($resultado['success']);
        $this->assertEmpty($resultado['data']);
    }
    // php artisan test --filter=ClienteServiceTest::obtener_clientes_retorna_false_cuando_api_falla

    // ── 4. agregarCliente() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function agregar_cliente_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/cliente' => Http::response(['id_cliente' => 3], 200),
        ]);

        $service   = new ClienteService();
        $resultado = $service->agregarCliente(...$this->datosCliente());

        $this->assertTrue($resultado['success']);
        $this->assertArrayHasKey('data', $resultado);
    }
    // php artisan test --filter=ClienteServiceTest::agregar_cliente_retorna_success_true_cuando_api_exitosa

    // ── 5. agregarCliente() retorna success=>false cuando la API falla ──
    #[Test]
    public function agregar_cliente_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/cliente' => Http::response([], 500),
        ]);

        $service   = new ClienteService();
        $resultado = $service->agregarCliente(...$this->datosCliente());

        $this->assertFalse($resultado['success']);
        $this->assertStringContainsString('500', $resultado['error']);
    }
    // php artisan test --filter=ClienteServiceTest::agregar_cliente_retorna_false_cuando_api_falla

    // ── 6. actualizarCliente() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function actualizar_cliente_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/cliente/1' => Http::response(['id_cliente' => 1], 200),
        ]);

        $service   = new ClienteService();
        $resultado = $service->actualizarCliente(1, ...$this->datosCliente());

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=ClienteServiceTest::actualizar_cliente_retorna_success_true_cuando_api_exitosa

    // ── 7. actualizarCliente() retorna success=>false cuando la API falla ──
    #[Test]
    public function actualizar_cliente_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/cliente/1' => Http::response([], 500),
        ]);

        $service   = new ClienteService();
        $resultado = $service->actualizarCliente(1, ...$this->datosCliente());

        $this->assertFalse($resultado['success']);
        $this->assertStringContainsString('500', $resultado['error']);
    }
    // php artisan test --filter=ClienteServiceTest::actualizar_cliente_retorna_false_cuando_api_falla

    // ── 8. eliminarCliente() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function eliminar_cliente_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/cliente/1' => Http::response([], 200),
        ]);

        $service   = new ClienteService();
        $resultado = $service->eliminarCliente(1);

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=ClienteServiceTest::eliminar_cliente_retorna_success_true_cuando_api_exitosa

    // ── 9. eliminarCliente() retorna success=>false cuando la API falla ──
    #[Test]
    public function eliminar_cliente_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/cliente/1' => Http::response([], 500),
        ]);

        $service   = new ClienteService();
        $resultado = $service->eliminarCliente(1);

        $this->assertFalse($resultado['success']);
        $this->assertStringContainsString('500', $resultado['error']);
    }
    // php artisan test --filter=ClienteServiceTest::eliminar_cliente_retorna_false_cuando_api_falla

    // ── 10. buscarCliente() retorna JSON de la API ──
    #[Test]
    public function buscar_cliente_retorna_json_de_la_api(): void
    {
        Http::fake([
            '*/cliente/cliente/buscar*' => Http::response([
                ['id_cliente' => 1, 'nombre' => 'Juan'],
            ], 200),
        ]);

        $service   = new ClienteService();
        $resultado = $service->buscarCliente('Juan');

        $this->assertIsArray($resultado);
        $this->assertEquals('Juan', $resultado[0]['nombre']);
    }
    // php artisan test --filter=ClienteServiceTest::buscar_cliente_retorna_json_de_la_api
}

// php artisan test --filter=ClienteServiceTest