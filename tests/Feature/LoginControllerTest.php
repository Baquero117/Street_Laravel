<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Session;
use App\Models\Login\LoginService;

class LoginControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function muestra_la_vista_de_login()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('login.login');
    }

    /** @test */
    public function login_exitoso_cliente()
    {
        // 🔹 Mock del servicio
        $mock = Mockery::mock(LoginService::class);
        $mock->shouldReceive('autenticar')
            ->once()
            ->andReturn([
                'token' => 'fake-jwt',
                'tipo' => 'cliente',
                'datos' => [
                    'id_cliente' => 1,
                    'nombre' => 'Juan',
                    'correo_electronico' => 'juan@test.com'
                ]
            ]);

        $this->app->instance(LoginService::class, $mock);

        $response = $this->post(route('login.procesar'), [
            'correo_electronico' => 'juan@test.com',
            'contrasena' => '123456'
        ]);

        $response->assertRedirect(route('inicio'));

        $this->assertEquals('fake-jwt', Session::get('token'));
        $this->assertEquals('cliente', Session::get('usuario_tipo'));
    }

    /** @test */
    public function login_exitoso_administrador()
    {
        $mock = Mockery::mock(LoginService::class);
        $mock->shouldReceive('autenticar')
            ->once()
            ->andReturn([
                'token' => 'fake-jwt',
                'tipo' => 'administrador',
                'datos' => [
                    'id_vendedor' => 5,
                    'nombre' => 'Admin',
                    'correo_electronico' => 'admin@test.com'
                ]
            ]);

        $this->app->instance(LoginService::class, $mock);

        $response = $this->post(route('login.procesar'), [
            'correo_electronico' => 'admin@test.com',
            'contrasena' => '123456'
        ]);

        $response->assertRedirect(route('admin.Reportes'));
    }

    /** @test */
    public function login_fallido_redirige_con_error()
    {
        $mock = Mockery::mock(LoginService::class);
        $mock->shouldReceive('autenticar')
            ->once()
            ->andReturn(false);

        $this->app->instance(LoginService::class, $mock);

        $response = $this->from(route('login'))
            ->post(route('login.procesar'), [
                'correo_electronico' => 'fail@test.com',
                'contrasena' => 'wrong'
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function logout_limpia_la_sesion()
    {
        Session::put('token', 'abc');

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('inicio'));
        $this->assertNull(Session::get('token'));
    }
}


/** php artisan test --filter=LoginControllerTest */