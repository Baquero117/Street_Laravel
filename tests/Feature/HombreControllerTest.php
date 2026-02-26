<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HombreControllerTest extends TestCase
{
    /** @test */
    public function index_muestra_productos_cuando_api_responde_ok()
    {
        Http::fake([
            '*' => Http::response([
                ['id_producto' => 1, 'nombre' => 'Camisa Hombre']
            ], 200)
        ]);

        $response = $this->get(route('hombre'));

        $response->assertStatus(200);
        $response->assertViewIs('Vistas.Hombre');
        $response->assertViewHas('productos');
    }

    /** @test */
    public function index_devuelve_lista_vacia_si_api_falla()
    {
        Http::fake([
            '*' => Http::response([], 500)
        ]);

        $response = $this->get(route('hombre'));

        $response->assertStatus(200);
        $response->assertViewHas('productos', []);
    }

    /** @test */
    public function index_maneja_excepcion_y_devuelve_vacio()
    {
        Http::fake(function () {
            throw new \Exception('API caída');
        });

        Log::spy();

        $response = $this->get(route('hombre'));

        $response->assertStatus(200);
        $response->assertViewHas('productos', []);

        Log::shouldHaveReceived('error');
    }

    /** @test */
    public function detalle_devuelve_json_cuando_existe()
    {
        Http::fake([
            '*' => Http::response([
                'id_producto' => 1,
                'nombre' => 'Camisa Hombre'
            ], 200)
        ]);

        $response = $this->getJson(route('hombre.productos.detalle', 1));

        $response->assertOk();
        $response->assertJson([
            'id_producto' => 1
        ]);
    }

    /** @test */
    public function detalle_devuelve_404_cuando_no_existe()
    {
        Http::fake([
            '*' => Http::response([], 404)
        ]);

        $response = $this->getJson(route('hombre.productos.detalle', 999));

        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Producto no encontrado'
        ]);
    }

    /** @test */
    public function detalle_maneja_excepcion_y_devuelve_500()
    {
        Http::fake(function () {
            throw new \Exception('API caída');
        });

        Log::spy();

        $response = $this->getJson(route('hombre.productos.detalle', 1));

        $response->assertStatus(500);
        $response->assertJson([
            'error' => 'Error al obtener detalle del producto'
        ]);

        Log::shouldHaveReceived('error');
    }
}

/*php artisan test --filter=HombreControllerTest*/