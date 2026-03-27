<?php

namespace Tests\Unit\Administrador;

use Tests\TestCase;
use App\Models\Administrador\VendedorService;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;

class VendedorServiceTest extends TestCase
{
    private string $baseUrl = "http://localhost:8080/vendedor";

    // ── 1. obtenerVendedores() retorna lista de vendedores cuando la API responde 200 ──
    #[Test]
    public function obtener_vendedores_retorna_lista_exitosa(): void
    {
        $vendedoresFake = [
            ['id_vendedor' => 1, 'nombre' => 'Vendedor 1'],
            ['id_vendedor' => 2, 'nombre' => 'Vendedor 2'],
        ];

        Http::fake([
            $this->baseUrl => Http::response($vendedoresFake, 200)
        ]);

        $service = new VendedorService();
        $resultado = $service->obtenerVendedores();

        $this->assertCount(2, $resultado);
        $this->assertEquals('Vendedor 1', $resultado[0]['nombre']);
    }
    // php artisan test --filter=VendedorServiceTest::obtener_vendedores_retorna_lista_exitosa

    // ── 2. obtenerVendedores() retorna array vacío si la API falla ──
    #[Test]
    public function obtener_vendedores_retorna_vacio_si_api_falla(): void
    {
        Http::fake([
            $this->baseUrl => Http::response([], 500)
        ]);

        $service = new VendedorService();
        $resultado = $service->obtenerVendedores();

        $this->assertIsArray($resultado);
        $this->assertEmpty($resultado);
    }
    // php artisan test --filter=VendedorServiceTest::obtener_vendedores_retorna_vacio_si_api_falla

    // ── 3. agregarVendedor() retorna éxito cuando la creación es correcta ──
    #[Test]
    public function agregar_vendedor_retorna_success_true(): void
    {
        Http::fake([
            $this->baseUrl => Http::response(['id' => 100], 201)
        ]);

        $service = new VendedorService();
        $resultado = $service->agregarVendedor('Juan', 'Perez', 'juan@test.com', '123', '300');

        $this->assertTrue($resultado['success']);
        $this->assertArrayHasKey('data', $resultado);
    }
    // php artisan test --filter=VendedorServiceTest::agregar_vendedor_retorna_success_true

    // ── 4. agregarVendedor() retorna error si la API devuelve un fallo ──
    #[Test]
    public function agregar_vendedor_retorna_error_si_api_falla(): void
    {
        Http::fake([
            $this->baseUrl => Http::response(null, 400)
        ]);

        $service = new VendedorService();
        $resultado = $service->agregarVendedor('Juan', 'Perez', 'juan@test.com', '123', '300');

        $this->assertFalse($resultado['success']);
        $this->assertEquals('HTTP 400', $resultado['error']);
    }
    // php artisan test --filter=VendedorServiceTest::agregar_vendedor_retorna_error_si_api_falla

    // ── 5. actualizarVendedor() funciona correctamente con PUT ──
    #[Test]
    public function actualizar_vendedor_retorna_success_true(): void
    {
        Http::fake([
            $this->baseUrl . "/*" => Http::response(['status' => 'updated'], 200)
        ]);

        $service = new VendedorService();
        $resultado = $service->actualizarVendedor(1, 'Edit', 'Edit', 'e@t.com', '1', '1');

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=VendedorServiceTest::actualizar_vendedor_retorna_success_true

    // ── 6. eliminarVendedor() retorna éxito con DELETE ──
    #[Test]
    public function eliminar_vendedor_retorna_success_true(): void
    {
        Http::fake([
            $this->baseUrl . "/10" => Http::response(null, 200)
        ]);

        $service = new VendedorService();
        $resultado = $service->eliminarVendedor(10);

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=VendedorServiceTest::eliminar_vendedor_retorna_success_true
}

// php artisan test --filter=VendedorServiceTest