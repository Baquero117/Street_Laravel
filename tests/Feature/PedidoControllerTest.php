<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\Administrador\PedidoService;
use App\Models\Administrador\ClienteService;

class PedidoControllerTest extends TestCase
{
    public function test_index_muestra_pedidos()
{
    $pedidoMock = Mockery::mock(PedidoService::class);
    $clienteMock = Mockery::mock(ClienteService::class);

    $pedidoMock->shouldReceive('obtenerPedidos')
        ->once()
        ->andReturn([
            'success' => true,
            'data' => [
                [
                    'id_pedido' => 1,
                    'id_cliente' => 1,
                    'fecha_pedido' => '2026-02-18',
                    'total' => 100000,
                      'estado' => 'Pendiente' // 👈 AGREGAR ESTO
                ]
            ]
        ]);

    $clienteMock->shouldReceive('obtenerClientes')
        ->once()
        ->andReturn([
            'success' => true,
            'data' => []
        ]);

    $clienteMock->shouldReceive('obtenerClientePorId')
        ->once()
        ->andReturn([
            'success' => true,
            'data' => [
                'id_cliente' => 1,
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'correo_electronico' => 'juan@test.com',
                'telefono' => '3001234567',
                'direccion' => 'Calle 123',
                'contrasena' => '123'
            ]
        ]);

    $this->app->instance(PedidoService::class, $pedidoMock);
    $this->app->instance(ClienteService::class, $clienteMock);

    session(['token' => '123']);

    $response = $this->get(route('pedido.index'));

    $response->assertStatus(200);
    $response->assertViewIs('Administrador.Pedido');
    $response->assertViewHas('pedidos');
}

}

