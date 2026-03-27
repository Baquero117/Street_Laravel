<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PublicoControllerTest extends TestCase
{
    private string $apiUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiUrl = env('API_JAVA_URL', 'http://localhost:8080');
    }

    #[Test]
    public function index_muestra_la_vista_de_inicio_con_productos()
    {
        // Mock de la API con los campos que tu vista "Inicio.blade.php" necesita
        Http::fake([
            "{$this->apiUrl}/producto" => Http::response([
                [
                    'id_producto' => 1, 
                    'nombre' => 'Tenis Street', 
                    'precio' => 100,
                    'imagen' => 'foto_prueba.jpg', // Agregamos la clave que faltaba
                    'descripcion' => 'Descripción de prueba'
                ]
            ], 200)
        ]);

        $response = $this->get('/inicio'); 

        $response->assertStatus(200);
        $response->assertViewIs('PuntoInicio.Inicio');
        $response->assertViewHas('productos');
    }

    #[Test]
    public function detalle_retorna_json_con_datos_del_producto()
    {
        $id = 1;
        Http::fake([
            "{$this->apiUrl}/producto/{$id}/detalle" => Http::response([
                'id_producto' => 1,
                'nombre' => 'Tenis Street',
                'descripcion' => 'Calidad Premium'
            ], 200)
        ]);

        // Corregido a /productos/ (plural) como dice tu web.php
        $response = $this->get("/productos/{$id}/detalle");

        $response->assertStatus(200);
        $response->assertJsonFragment(['nombre' => 'Tenis Street']);
    }

    #[Test]
    public function detalle_retorna_404_si_la_api_falla()
    {
        $id = 999;
        Http::fake([
            "{$this->apiUrl}/producto/{$id}/detalle" => Http::response(['error' => 'No encontrado'], 404)
        ]);

        // Corregido a /productos/ (plural)
        $response = $this->get("/productos/{$id}/detalle");

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Producto no encontrado']);
    }
}

// php artisan test --filter=PublicoControllerTest