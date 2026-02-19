<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\Administrador\ProductoService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductoControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // ============================
    // 🔹 TEST INDEX
    // ============================
    public function test_index_muestra_productos()
    {
        $mock = Mockery::mock(ProductoService::class);

        $mock->shouldReceive('obtenerProductos')
            ->once()
           ->andReturn([
    [
        'id_producto' => 1,
        'nombre' => 'Producto Test',
        'descripcion' => 'Descripción de prueba',
        'cantidad' => 10,
        'imagen' => 'productos/test.jpg',
        'precio' => 50000,
        'estado' => 'Activo',
        'id_vendedor' => 1,
        'color' => 'Rojo',
        'id_categoria' => 1
    ]
]);


        $this->app->instance(ProductoService::class, $mock);

        session(['token' => '123']); // necesario por tu constructor

        $response = $this->get(route('producto.index'));

        $response->assertStatus(200);
        $response->assertViewIs('Administrador.Producto');
        $response->assertViewHas('productos');
    }

    // ============================
    // 🔹 TEST STORE
    // ============================
    public function test_store_crea_producto()
    {
        Storage::fake('public');

        $mock = Mockery::mock(ProductoService::class);

        $mock->shouldReceive('agregarProducto')
            ->once()
            ->andReturn(['success' => true]);

        $this->app->instance(ProductoService::class, $mock);

        session(['token' => '123']);

        $response = $this->post(route('producto.agregar'), [
            'nombre' => 'Producto Nuevo',
            'descripcion' => 'Descripción',
            'cantidad' => 5,
            'id_vendedor' => 1,
            'estado' => 'Activo',
            'imagen' => UploadedFile::fake()->image('producto.jpg'),
            'precio' => 100000,
            'color' => 'Rojo',
            'id_categoria' => 1,
        ]);

        $response->assertRedirect(route('producto.index'));
    }

    // ============================
    // 🔹 TEST UPDATE
    // ============================
    public function test_update_producto()
    {
        Storage::fake('public');

        $mock = Mockery::mock(ProductoService::class);

        $mock->shouldReceive('actualizarProducto')
            ->once()
            ->andReturn(['success' => true]);

        $this->app->instance(ProductoService::class, $mock);

        session(['token' => '123']);

        $response = $this->post(route('producto.actualizar'), [
            'id_producto' => 1,
            'nombre' => 'Producto Editado',
            'descripcion' => 'Nueva descripción',
            'cantidad' => 8,
            'imagen_actual' => 'productos/test.jpg',
            'id_vendedor' => 1,
            'estado' => 'Activo',
            'precio' => 120000,
            'color' => 'Azul',
            'id_categoria' => 2,
        ]);

        $response->assertRedirect(route('producto.index'));
    }

    // ============================
    // 🔹 TEST DESTROY
    // ============================
    public function test_destroy_producto()
    {
        $mock = Mockery::mock(ProductoService::class);

        $mock->shouldReceive('eliminarProducto')
            ->once()
            ->andReturn(['success' => true]);

        $this->app->instance(ProductoService::class, $mock);

        session(['token' => '123']);

        $response = $this->post(route('producto.eliminar'), [
            'id_producto' => 1,
        ]);

        $response->assertRedirect(route('producto.index'));
    }
}
