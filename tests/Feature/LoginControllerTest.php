<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Session;
use App\Models\Login\LoginService;
use Mockery;

class LoginControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function muestra_vista_login()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('login.login');
    }

    /** @test */
    public function login_exitoso_como_administrador()
    {
        $mock = Mockery::mock(LoginService::class);
        $this->app->instance(LoginService::class, $mock);

        $mock->shouldReceive('autenticar')
            ->once()
            ->andReturn([
                'token' => 'abc123',
                'tipo' => 'administrador',
                'datos' => [
                    'id_vendedor' => 1,
                    'nombre' => 'Admin',
                    'correo_electronico' => 'admin@test.com'
                ]
            ]);

        $response = $this->post(route('login.procesar'), [
            'correo_electronico' => 'admin@test.com',
            'contrasena' => '123456'
        ]);

        $response->assertRedirect(route('admin.Reportes'));
        $this->assertEquals('abc123', Session::get('token'));
        $this->assertEquals('administrador', Session::get('usuario_tipo'));
    }

    /** @test */
    public function login_exitoso_como_cliente()
    {
        $mock = Mockery::mock(LoginService::class);
        $this->app->instance(LoginService::class, $mock);

        $mock->shouldReceive('autenticar')
            ->once()
            ->andReturn([
                'token' => 'xyz789',
                'tipo' => 'cliente',
                'datos' => [
                    'id_cliente' => 5,
                    'nombre' => 'Alexandra',
                    'correo_electronico' => 'alex@test.com'
                ]
            ]);

        $response = $this->post(route('login.procesar'), [
            'correo_electronico' => 'alex@test.com',
            'contrasena' => '123456'
        ]);

        $response->assertRedirect(route('inicio'));
        $this->assertEquals('xyz789', Session::get('token'));
        $this->assertEquals('cliente', Session::get('usuario_tipo'));
    }

    /** @test */
    public function login_fallido_redirige_con_error()
    {
        $mock = Mockery::mock(LoginService::class);
        $this->app->instance(LoginService::class, $mock);

        $mock->shouldReceive('autenticar')
            ->once()
            ->andReturn(null);

        $response = $this->post(route('login.procesar'), [
            'correo_electronico' => 'wrong@test.com',
            'contrasena' => 'incorrecta'
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function logout_limpia_sesion_y_redirige()
    {
        Session::put('token', 'abc123');

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('inicio'));
        $this->assertNull(Session::get('token'));
    }
}  //php artisan test --filter=LoginControllerTest