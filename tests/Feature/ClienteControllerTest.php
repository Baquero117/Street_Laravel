<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\Administrador\ClienteService;

class ClienteControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_muestra_clientes()
    {
        $clienteMock = Mockery::mock(ClienteService::class);

        $clienteMock->shouldReceive('obtenerClientes')
            ->once()
            ->andReturn([
                'success' => true,
                'data' => [
                    [
                        'id_cliente' => 1,
                        'nombre' => 'Juan',
                        'apellido' => 'Pérez',
                        'correo_electronico' => 'juan@test.com',
                        'telefono' => '3001234567',
                        'direccion' => 'Calle 123',
                        'contrasena' => '123'
                    ]
                ]
            ]);

        $this->app->instance(ClienteService::class, $clienteMock);

        session(['token' => '123']);

        $response = $this->get(route('admin.Cliente'));

        $response->assertStatus(200);
        $response->assertViewIs('Administrador.Cliente');
        $response->assertViewHas('clientes');
    }

    public function test_store_crea_cliente()
    {
        $clienteMock = Mockery::mock(ClienteService::class);

        $clienteMock->shouldReceive('agregarCliente')
            ->once()
            ->andReturn([
                'success' => true
            ]);

        $this->app->instance(ClienteService::class, $clienteMock);

        session(['token' => '123']);

        $response = $this->post(route('cliente.agregar'), [
            'nombre' => 'Maria',
            'apellido' => 'Gomez',
            'contrasena' => '123456',
            'direccion' => 'Calle 456',
            'telefono' => '3007654321',
            'correo_electronico' => 'maria@test.com',
        ]);

        $response->assertRedirect(route('admin.Cliente'));
        $response->assertSessionHas('mensaje');
    }

    public function test_update_cliente()
    {
        $clienteMock = Mockery::mock(ClienteService::class);

        $clienteMock->shouldReceive('actualizarCliente')
            ->once()
            ->andReturn([
                'success' => true
            ]);

        $this->app->instance(ClienteService::class, $clienteMock);

        session(['token' => '123']);

        $response = $this->post(route('cliente.actualizar'), [
            'id_cliente' => 1,
            'nombre' => 'Maria',
            'apellido' => 'Gomez',
            'contrasena' => '123456',
            'direccion' => 'Calle 456',
            'telefono' => '3007654321',
            'correo_electronico' => 'maria@test.com',
        ]);

        $response->assertRedirect(route('admin.Cliente'));
        $response->assertSessionHas('mensaje');
    }

    public function test_destroy_cliente()
    {
        $clienteMock = Mockery::mock(ClienteService::class);

        $clienteMock->shouldReceive('eliminarCliente')
            ->once()
            ->andReturn([
                'success' => true
            ]);

        $this->app->instance(ClienteService::class, $clienteMock);

        session(['token' => '123']);

        $response = $this->post(route('cliente.eliminar'), [
            'id_cliente' => 1
        ]);

        $response->assertRedirect(route('admin.Cliente'));
        $response->assertSessionHas('mensaje');
    }
}