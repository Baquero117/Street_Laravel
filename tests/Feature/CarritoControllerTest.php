<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Session;
use App\Models\Carrito\CarritoService;
use App\Models\PuntoInicio\PerfilService;

class CarritoControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function index_redirige_a_login_si_no_hay_token()
    {
        $response = $this->get(route('carrito'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function index_muestra_carrito_con_token()
    {
        Session::put('token', 'fake');

        $mock = Mockery::mock(CarritoService::class);
        $mock->shouldReceive('obtenerCarrito')
            ->once()
            ->andReturn(['items' => []]);

        $this->app->instance(CarritoService::class, $mock);
        $this->app->instance(PerfilService::class, Mockery::mock(PerfilService::class));

        $response = $this->get(route('carrito'));

        $response->assertStatus(200);
        $response->assertViewIs('CarritoCompras.Carrito');
    }

    /** @test */
    public function agregar_producto_sin_sesion_devuelve_401()
    {
        $response = $this->postJson(route('carrito.agregar'), [
            'id_producto' => 1,
            'cantidad' => 2
        ]);

        $response->assertStatus(401);
        $response->assertJson(['success' => false]);
    }

    /** @test */
    public function agregar_producto_con_stock_insuficiente()
    {
        Session::put('token', 'fake');

        $mock = Mockery::mock(CarritoService::class);
        $mock->shouldReceive('agregarProducto')
            ->once()
            ->andReturn(['resultado' => -1]);

        $this->app->instance(CarritoService::class, $mock);
        $this->app->instance(PerfilService::class, Mockery::mock(PerfilService::class));

        $response = $this->postJson(route('carrito.agregar'), [
            'id_producto' => 1,
            'cantidad' => 2
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'tipo_error' => 'stock_insuficiente'
        ]);
    }

    /** @test */
    public function contador_sin_sesion_devuelve_cero()
    {
        $response = $this->getJson(route('carrito.contador'));

        $response->assertOk();
        $response->assertJson(['cantidad' => 0]);
    }

    /** @test */
    public function vaciar_sin_token_devuelve_401()
    {
        $response = $this->deleteJson(route('carrito.vaciar'));

        $response->assertStatus(401);
        $response->assertJson(['success' => false]);
    }

    /** @test */
    public function checkout_redirige_si_carrito_vacio()
    {
        Session::put('token', 'fake');

        $carritoMock = Mockery::mock(CarritoService::class);
        $carritoMock->shouldReceive('obtenerCarrito')
            ->once()
            ->andReturn(['items' => []]);

        $this->app->instance(CarritoService::class, $carritoMock);
        $this->app->instance(PerfilService::class, Mockery::mock(PerfilService::class));

        $response = $this->get(route('checkout'));

        $response->assertRedirect(route('carrito'));
        $response->assertSessionHas('warning');
    }

    /** @test */
    public function checkout_exitoso_muestra_vista_pedido()
    {
        Session::put('token', 'fake');
        Session::put('usuario_id', 1);
        Session::put('usuario_nombre', 'Juan');
        Session::put('usuario_correo', 'juan@test.com');
        Session::put('usuario_tipo', 'cliente');

        $carritoMock = Mockery::mock(CarritoService::class);
        $carritoMock->shouldReceive('obtenerCarrito')
            ->once()
            ->andReturn([
                'items' => [
                    [
                        'nombre' => 'Producto X',
                        'talla' => 'M',
                        'cantidad' => 1,
                        'stock_disponible' => 10
                    ]
                ]
            ]);

        $perfilMock = Mockery::mock(PerfilService::class);
        $perfilMock->shouldReceive('obtenerPerfil')
            ->once()
            ->andReturn([
                'id_cliente' => 1,
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'correo_electronico' => 'juan@test.com',
                'telefono' => '123',
                'direccion' => 'Calle 1'
            ]);

        $this->app->instance(CarritoService::class, $carritoMock);
        $this->app->instance(PerfilService::class, $perfilMock);

        $response = $this->get(route('checkout'));

        $response->assertStatus(200);
        $response->assertViewIs('CarritoCompras.Pedido');
    }
}

/** php artisan test --filter=CarritoControllerTest */