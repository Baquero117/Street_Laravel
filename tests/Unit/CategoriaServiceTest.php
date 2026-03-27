<?php
namespace Tests\Unit\Administrador;

use Tests\TestCase;
use App\Models\Administrador\CategoriaService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class CategoriaServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Session::put('token', 'token-fake');
    }

    // ── 1. obtenerCategorias() retorna array de categorías cuando la API responde HTTP 200 ──
    #[Test]
    public function obtener_categorias_retorna_array_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/categoria' => Http::response([
                ['id_categoria' => 1, 'nombre' => 'Hombre'],
                ['id_categoria' => 2, 'nombre' => 'Mujer'],
            ], 200),
        ]);

        $service   = new CategoriaService();
        $resultado = $service->obtenerCategorias();

        $this->assertIsArray($resultado);
        $this->assertCount(2, $resultado);
        $this->assertEquals('Hombre', $resultado[0]['nombre']);
    }
    // php artisan test --filter=CategoriaServiceTest::obtener_categorias_retorna_array_cuando_api_exitosa

    // ── 2. obtenerCategorias() retorna array vacío cuando la API falla ──
    #[Test]
    public function obtener_categorias_retorna_vacio_cuando_api_falla(): void
    {
        Http::fake([
            '*/categoria' => Http::response([], 500),
        ]);

        $service   = new CategoriaService();
        $resultado = $service->obtenerCategorias();

        $this->assertIsArray($resultado);
        $this->assertEmpty($resultado);
    }
    // php artisan test --filter=CategoriaServiceTest::obtener_categorias_retorna_vacio_cuando_api_falla

    // ── 3. agregarCategoria() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function agregar_categoria_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/categoria' => Http::response(['id_categoria' => 3, 'nombre' => 'Moda'], 200),
        ]);

        $service   = new CategoriaService();
        $resultado = $service->agregarCategoria('Moda');

        $this->assertTrue($resultado['success']);
        $this->assertArrayHasKey('data', $resultado);
    }
    // php artisan test --filter=CategoriaServiceTest::agregar_categoria_retorna_success_true_cuando_api_exitosa

    // ── 4. agregarCategoria() retorna success=>false cuando la API falla ──
    #[Test]
    public function agregar_categoria_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/categoria' => Http::response([], 500),
        ]);

        $service   = new CategoriaService();
        $resultado = $service->agregarCategoria('Moda');

        $this->assertFalse($resultado['success']);
        $this->assertStringContainsString('500', $resultado['error']);
    }
    // php artisan test --filter=CategoriaServiceTest::agregar_categoria_retorna_false_cuando_api_falla

    // ── 5. actualizarCategoria() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function actualizar_categoria_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/categoria/1' => Http::response(['id_categoria' => 1, 'nombre' => 'Hombre Updated'], 200),
        ]);

        $service   = new CategoriaService();
        $resultado = $service->actualizarCategoria(1, 'Hombre Updated');

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=CategoriaServiceTest::actualizar_categoria_retorna_success_true_cuando_api_exitosa

    // ── 6. actualizarCategoria() retorna success=>false cuando la API falla ──
    #[Test]
    public function actualizar_categoria_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/categoria/1' => Http::response([], 500),
        ]);

        $service   = new CategoriaService();
        $resultado = $service->actualizarCategoria(1, 'Hombre Updated');

        $this->assertFalse($resultado['success']);
        $this->assertStringContainsString('500', $resultado['error']);
    }
    // php artisan test --filter=CategoriaServiceTest::actualizar_categoria_retorna_false_cuando_api_falla

    // ── 7. eliminarCategoria() retorna success=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function eliminar_categoria_retorna_success_true_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/categoria/1' => Http::response([], 200),
        ]);

        $service   = new CategoriaService();
        $resultado = $service->eliminarCategoria(1);

        $this->assertTrue($resultado['success']);
    }
    // php artisan test --filter=CategoriaServiceTest::eliminar_categoria_retorna_success_true_cuando_api_exitosa

    // ── 8. eliminarCategoria() retorna success=>false cuando la API falla ──
    #[Test]
    public function eliminar_categoria_retorna_false_cuando_api_falla(): void
    {
        Http::fake([
            '*/categoria/1' => Http::response([], 500),
        ]);

        $service   = new CategoriaService();
        $resultado = $service->eliminarCategoria(1);

        $this->assertFalse($resultado['success']);
        $this->assertStringContainsString('500', $resultado['error']);
    }
    // php artisan test --filter=CategoriaServiceTest::eliminar_categoria_retorna_false_cuando_api_falla
}

// php artisan test --filter=CategoriaServiceTest