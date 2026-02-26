<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\Administrador\ClienteService;

class ClienteServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        session(['token' => '123']);
    }

    public function test_obtener_clientes_success()
    {
        Http::fake([
            'http://localhost:8080/cliente' => Http::response([
                [
                    'id_cliente' => 1,
                    'nombre' => 'Juan'
                ]
            ], 200)
        ]);

        $service = new ClienteService();
        $resultado = $service->obtenerClientes();

        $this->assertTrue($resultado['success']);
        $this->assertCount(1, $resultado['data']);
    }

    public function test_agregar_cliente_success()
    {
        Http::fake([
            'http://localhost:8080/cliente' => Http::response([
                'id_cliente' => 1
            ], 200)
        ]);

        $service = new ClienteService();

        $resultado = $service->agregarCliente(
            'Maria',
            'Gomez',
            '123456',
            'Calle 1',
            '3000000000',
            'maria@test.com'
        );

        $this->assertTrue($resultado['success']);
        $this->assertArrayHasKey('data', $resultado);
    }

    public function test_actualizar_cliente_success()
    {
        Http::fake([
            'http://localhost:8080/cliente/1' => Http::response([
                'updated' => true
            ], 200)
        ]);

        $service = new ClienteService();

        $resultado = $service->actualizarCliente(
            1,
            'Maria',
            'Gomez',
            '123456',
            'Calle 1',
            '3000000000',
            'maria@test.com'
        );

        $this->assertTrue($resultado['success']);
    }

    public function test_eliminar_cliente_success()
    {
        Http::fake([
            'http://localhost:8080/cliente/1' => Http::response(null, 200)
        ]);

        $service = new ClienteService();
        $resultado = $service->eliminarCliente(1);

        $this->assertTrue($resultado['success']);
    }

    public function test_obtener_cliente_por_id_success()
    {
        Http::fake([
            'http://localhost:8080/cliente/1' => Http::response([
                'id_cliente' => 1,
                'nombre' => 'Juan'
            ], 200)
        ]);

        $service = new ClienteService();
        $resultado = $service->obtenerClientePorId(1);

        $this->assertTrue($resultado['success']);
        $this->assertEquals(1, $resultado['data']['id_cliente']);
    }
}

/** php artisan test --filter=ClienteServiceTest */
