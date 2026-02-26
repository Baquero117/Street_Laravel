<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\Administrador\VendedorService;

class VendedorControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_muestra_vendedores()
    {
        $mock = Mockery::mock(VendedorService::class);

        $mock->shouldReceive('obtenerVendedores')
            ->once()
            ->andReturn([]);

        $this->app->instance(VendedorService::class, $mock);

        session(['token' => '123']);

        $response = $this->get(route('admin.Vendedor'));

        $response->assertStatus(200);
        $response->assertViewIs('Administrador.Vendedor');
        $response->assertViewHas('vendedores');
    }

    public function test_store_crea_vendedor()
    {
        $mock = Mockery::mock(VendedorService::class);

        $mock->shouldReceive('agregarVendedor')
            ->once()
            ->andReturn([
                'success' => true
            ]);

        $this->app->instance(VendedorService::class, $mock);

        session(['token' => '123']);

        $response = $this->post(route('vendedor.agregar'), [
            'nombre' => 'Carlos',
            'apellido' => 'Lopez',
            'correo_electronico' => 'carlos@test.com',
            'contrasena' => '123456',
            'telefono' => '3001112222',
        ]);

        $response->assertRedirect(route('vendedor.index'));
        $response->assertSessionHas('mensaje');
    }

    public function test_update_vendedor()
    {
        $mock = Mockery::mock(VendedorService::class);

        $mock->shouldReceive('actualizarVendedor')
            ->once()
            ->andReturn([
                'success' => true
            ]);

        $this->app->instance(VendedorService::class, $mock);

        session(['token' => '123']);

        $response = $this->post(route('vendedor.actualizar'), [
            'id_vendedor' => 1,
            'nombre' => 'Carlos',
            'apellido' => 'Lopez',
            'correo_electronico' => 'carlos@test.com',
            'contrasena' => '123456',
            'telefono' => '3001112222',
        ]);

        $response->assertRedirect(route('vendedor.index'));
        $response->assertSessionHas('mensaje');
    }

    public function test_destroy_vendedor()
    {
        $mock = Mockery::mock(VendedorService::class);

        $mock->shouldReceive('eliminarVendedor')
            ->once()
            ->andReturn([
                'success' => true
            ]);

        $this->app->instance(VendedorService::class, $mock);

        session(['token' => '123']);

        $response = $this->post(route('vendedor.eliminar'), [
            'id_vendedor' => 1
        ]);

        $response->assertRedirect(route('vendedor.index'));
        $response->assertSessionHas('mensaje');
    }
}

/** php artisan test --filter=VendedorControllerTest */