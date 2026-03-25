<?php
namespace Tests\Feature\MasVistas;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;

class HombreControllerTest extends TestCase
{
    
    // ── 1. index() retorna la vista Hombre con productos cuando la API responde exitosamente ──
    #[Test]
    public function index_retorna_vista_con_productos_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/producto/categoria/20' => Http::response([
                [
                    'id_producto' => 1,
                    'nombre'      => 'Camiseta',
                    'precio'      => 50000,
                    'imagen'      => 'camiseta.jpg',
                    'descripcion' => 'Camiseta urbana',
                    'categoria'   => 'Hombre',
                ],
                [
                    'id_producto' => 2,
                    'nombre'      => 'Pantalón',
                    'precio'      => 80000,
                    'imagen'      => 'pantalon.jpg',
                    'descripcion' => 'Pantalón urbano',
                    'categoria'   => 'Hombre',
                ],
            ], 200),
        ]);

        $response = $this->get(route('hombre'));

        $response->assertStatus(200);
        $response->assertViewIs('Vistas.Hombre');
        $response->assertViewHas('productos', function ($productos) {
            return count($productos) === 2 && $productos[0]['nombre'] === 'Camiseta';
        });
    }
    // php artisan test --filter=HombreControllerTest::index_retorna_vista_con_productos_cuando_api_exitosa

    // ── 2. index() retorna vista con productos vacíos cuando la API falla ──
    #[Test]
    public function index_retorna_productos_vacios_cuando_api_falla(): void
    {
        Http::fake([
            '*/producto/categoria/20' => Http::response([], 500),
        ]);

        $response = $this->get(route('hombre'));

        $response->assertStatus(200);
        $response->assertViewIs('Vistas.Hombre');
        $response->assertViewHas('productos', []);
    }
    // php artisan test --filter=HombreControllerTest::index_retorna_productos_vacios_cuando_api_falla

    // ── 3. index() retorna vista con productos vacíos cuando la API lanza excepción ──
    #[Test]
    public function index_retorna_productos_vacios_cuando_api_lanza_excepcion(): void
    {
        Http::fake([
            '*/producto/categoria/20' => function () {
                throw new \Exception('Connection refused');
            },
        ]);

        $response = $this->get(route('hombre'));

        $response->assertStatus(200);
        $response->assertViewIs('Vistas.Hombre');
        $response->assertViewHas('productos', []);
    }
    // php artisan test --filter=HombreControllerTest::index_retorna_productos_vacios_cuando_api_lanza_excepcion

    // ── 4. detalle() retorna JSON con datos del producto cuando la API responde exitosamente ──
    #[Test]
    public function detalle_retorna_json_cuando_api_exitosa(): void
    {
        Http::fake([
            '*/producto/1/detalle' => Http::response([
                'id_producto' => 1,
                'nombre'      => 'Camiseta',
                'precio'      => 50000,
            ], 200),
        ]);

        $response = $this->get(route('hombre.productos.detalle', ['id' => 1]));

        $response->assertStatus(200);
        $response->assertJson([
            'id_producto' => 1,
            'nombre'      => 'Camiseta',
        ]);
    }
    // php artisan test --filter=HombreControllerTest::detalle_retorna_json_cuando_api_exitosa

    // ── 5. detalle() retorna 404 cuando el producto no existe ──
    #[Test]
    public function detalle_retorna_404_cuando_producto_no_existe(): void
    {
        Http::fake([
            '*/producto/999/detalle' => Http::response(['error' => 'Not found'], 404),
        ]);

        $response = $this->get(route('hombre.productos.detalle', ['id' => 999]));

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Producto no encontrado']);
    }
    // php artisan test --filter=HombreControllerTest::detalle_retorna_404_cuando_producto_no_existe

    // ── 6. detalle() retorna 500 cuando la API lanza excepción ──
    #[Test]
    public function detalle_retorna_500_cuando_api_lanza_excepcion(): void
    {
        Http::fake([
            '*/producto/1/detalle' => function () {
                throw new \Exception('Connection refused');
            },
        ]);

        $response = $this->get(route('hombre.productos.detalle', ['id' => 1]));

        $response->assertStatus(500);
        $response->assertJson(['error' => 'Error al obtener detalle del producto']);
    }
    // php artisan test --filter=HombreControllerTest::detalle_retorna_500_cuando_api_lanza_excepcion
}

// php artisan test --filter=HombreControllerTest