<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Administrador\DetalleProductoService;

class DetalleProductoControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_muestra_detalles()
    {
        $mock = Mockery::mock(DetalleProductoService::class);

        $mock->shouldReceive('obtenerDetalles')
            ->once()
            ->andReturn([]);

        $this->app->instance(DetalleProductoService::class, $mock);

        session(['token' => '123']);

        $response = $this->get(route('admin.DetalleProducto'));

        $response->assertStatus(200);
        $response->assertViewIs('Administrador.DetalleProducto');
        $response->assertViewHas('detalles');
    }

    public function test_store_crea_detalle()
    {
        Storage::fake('public');

        $mock = Mockery::mock(DetalleProductoService::class);

        $mock->shouldReceive('agregarDetalle')
            ->once()
            ->andReturn([
                'success' => true
            ]);

        $this->app->instance(DetalleProductoService::class, $mock);

        session(['token' => '123']);

        $file = UploadedFile::fake()->image('producto.jpg');

        $response = $this->post(route('detalle.agregar'), [
            'talla' => 'M',
            'id_producto' => 1,
            'imagen' => $file,
            'cantidad' => 10
        ]);

        $response->assertRedirect(route('detalle.index'));
        $response->assertSessionHas('mensaje');
    }

    public function test_update_detalle()
    {
        Storage::fake('public');

        $mock = Mockery::mock(DetalleProductoService::class);

        $mock->shouldReceive('actualizarDetalle')
            ->once()
            ->andReturn([
                'success' => true
            ]);

        $this->app->instance(DetalleProductoService::class, $mock);

        session(['token' => '123']);

        $file = UploadedFile::fake()->image('producto.jpg');

        $response = $this->post(route('detalle.actualizar'), [
            'id_detalle_producto' => 1,
            'talla' => 'L',
            'id_producto' => 1,
            'imagen' => $file,
            'cantidad' => 5
        ]);

        $response->assertRedirect(route('detalle.index'));
        $response->assertSessionHas('mensaje');
    }

    public function test_destroy_detalle()
    {
        $mock = Mockery::mock(DetalleProductoService::class);

        $mock->shouldReceive('eliminarDetalle')
            ->once()
            ->andReturn([
                'success' => true
            ]);

        $this->app->instance(DetalleProductoService::class, $mock);

        session(['token' => '123']);

        $response = $this->post(route('detalle.eliminar'), [
            'id_detalle_producto' => 1
        ]);

        $response->assertRedirect(route('detalle.index'));
        $response->assertSessionHas('mensaje');
    }
}