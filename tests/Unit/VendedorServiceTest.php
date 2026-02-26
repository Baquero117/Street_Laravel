<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\Administrador\VendedorService;

class VendedorServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        session(['token' => '123']);
    }

    /* ==========================
       🔹 OBTENER VENDEDORES
    ========================== */

    public function test_obtener_vendedores_success()
    {
        Http::fake([
            'http://localhost:8080/vendedor' => Http::response([
                ['id' => 1, 'nombre' => 'Carlos']
            ], 200)
        ]);

        $service = new VendedorService();
        $resultado = $service->obtenerVendedores();

        $this->assertCount(1, $resultado);
        $this->assertEquals('Carlos', $resultado[0]['nombre']);
    }

    /* ==========================
       🔹 AGREGAR VENDEDOR
    ========================== */

    public function test_agregar_vendedor_success()
    {
        Http::fake([
            'http://localhost:8080/vendedor' => Http::response([
                'id' => 1,
                'nombre' => 'Ana'
            ], 200)
        ]);

        $service = new VendedorService();

        $resultado = $service->agregarVendedor(
            'Ana',
            'Gomez',
            'ana@test.com',
            '123456',
            '3001234567'
        );

        $this->assertTrue($resultado['success']);
        $this->assertEquals('Ana', $resultado['data']['nombre']);
    }

    /* ==========================
       🔹 ACTUALIZAR VENDEDOR
    ========================== */

    public function test_actualizar_vendedor_success()
    {
        Http::fake([
            'http://localhost:8080/vendedor/1' => Http::response([
                'id' => 1,
                'nombre' => 'Ana Actualizada'
            ], 200)
        ]);

        $service = new VendedorService();

        $resultado = $service->actualizarVendedor(
            1,
            'Ana Actualizada',
            'Gomez',
            'ana@test.com',
            '123456',
            '3001234567'
        );

        $this->assertTrue($resultado['success']);
        $this->assertEquals('Ana Actualizada', $resultado['data']['nombre']);
    }

    /* ==========================
       🔹 ELIMINAR VENDEDOR
    ========================== */

    public function test_eliminar_vendedor_success()
    {
        Http::fake([
            'http://localhost:8080/vendedor/1' => Http::response(null, 200)
        ]);

        $service = new VendedorService();
        $resultado = $service->eliminarVendedor(1);

        $this->assertTrue($resultado['success']);
    }
}

/** php artisan test --filter=VendedorServiceTest */