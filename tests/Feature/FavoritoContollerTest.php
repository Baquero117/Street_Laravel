<?php
namespace Tests\Feature\PuntoInicio;

use Tests\TestCase;
use App\Models\PuntoInicio\FavoritoService;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class FavoritoControllerTest extends TestCase
{
    // ── Helpers de sesión ──
    private function sesionCliente(): void
    {
        Session::put('token', 'token-fake');
        Session::put('usuario_tipo', 'cliente');
        Session::put('usuario_id', 1);
    }

    // ── 1. index() redirige a login si no hay sesión ──
    #[Test]
    public function index_redirige_a_login_si_no_hay_sesion(): void
    {
        $response = $this->get(route('favoritos'));

        $response->assertRedirect(route('login'));
    }
    // php artisan test --filter=FavoritoControllerTest::index_redirige_a_login_si_no_hay_sesion

// ── 2. index() retorna la vista de favoritos cuando el cliente está autenticado ──
#[Test]
public function index_retorna_vista_favoritos_cuando_autenticado(): void
{
    $this->sesionCliente();

    $this->mock(FavoritoService::class, function ($mock) {
        $mock->shouldReceive('obtenerFavoritos')
            ->once()
            ->andReturn([
                [
                    'id_favorito'  => 1,
                    'id_producto'  => 10,
                    'nombre'       => 'Camiseta',
                    'imagen'       => 'camiseta.jpg',
                    'precio'       => 50000,
                    'descripcion'  => 'Camiseta urbana',
                    'talla'        => 'M',
                    'color'        => 'Negro',
                    'categoria'    => 'Hombre',
                ],
            ]);
    });

    $response = $this->get(route('favoritos'));

    $response->assertStatus(200);
    $response->assertViewIs('PuntoInicio.Cliente.Favorito');
    $response->assertViewHas('favoritos');
}
// php artisan test --filter=FavoritoControllerTest::index_retorna_vista_favoritos_cuando_autenticado

    // ── 3. agregar() redirige a login si no hay sesión (interceptado por checkAuth) ──
    #[Test]
    public function agregar_redirige_a_login_si_no_hay_sesion(): void
    {
        $response = $this->postJson(route('favoritos.agregar'), ['id_producto' => 10]);

        $response->assertStatus(302);
    }
    // php artisan test --filter=FavoritoControllerTest::agregar_redirige_a_login_si_no_hay_sesion

    // ── 4. agregar() retorna 400 si no se envía id_producto ──
    #[Test]
    public function agregar_retorna_400_si_no_se_envia_id_producto(): void
    {
        $this->sesionCliente();

        $response = $this->postJson(route('favoritos.agregar'), []);

        $response->assertStatus(400);
        $response->assertJson(['ok' => false, 'mensaje' => 'Producto no especificado.']);
    }
    // php artisan test --filter=FavoritoControllerTest::agregar_retorna_400_si_no_se_envia_id_producto

    // ── 5. agregar() retorna JSON del servicio cuando el cliente está autenticado ──
    #[Test]
    public function agregar_retorna_json_exitoso_cuando_autenticado(): void
    {
        $this->sesionCliente();

        $this->mock(FavoritoService::class, function ($mock) {
            $mock->shouldReceive('agregarFavorito')
                ->once()
                ->andReturn(['ok' => true, 'mensaje' => 'Favorito agregado']);
        });

        $response = $this->postJson(route('favoritos.agregar'), ['id_producto' => 10]);

        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);
    }
    // php artisan test --filter=FavoritoControllerTest::agregar_retorna_json_exitoso_cuando_autenticado


    // ── 6. eliminar() redirige a login si no hay sesión (interceptado por checkAuth) ──
    #[Test]
    public function eliminar_redirige_a_login_si_no_hay_sesion(): void
    {
        $response = $this->deleteJson(route('favoritos.eliminar', ['id' => 1]));

        $response->assertStatus(302);
    }
    // php artisan test --filter=FavoritoControllerTest::eliminar_redirige_a_login_si_no_hay_sesion


    // ── 7. eliminar() retorna JSON del servicio cuando el cliente está autenticado ──
    #[Test]
    public function eliminar_retorna_json_exitoso_cuando_autenticado(): void
    {
        $this->sesionCliente();

        $this->mock(FavoritoService::class, function ($mock) {
            $mock->shouldReceive('eliminarFavorito')
                ->once()
                ->andReturn(['ok' => true, 'mensaje' => 'Favorito eliminado']);
        });

        $response = $this->deleteJson(route('favoritos.eliminar', ['id' => 1]));

        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);
    }
    // php artisan test --filter=FavoritoControllerTest::eliminar_retorna_json_exitoso_cuando_autenticado


    // ── 8. verificar() redirige a login si no hay sesión (interceptado por checkAuth) ──
    #[Test]
    public function verificar_redirige_a_login_si_no_hay_sesion(): void
    {
        $response = $this->getJson(route('favoritos.verificar', ['id' => 10]));

        $response->assertStatus(302);
    }
    // php artisan test --filter=FavoritoControllerTest::verificar_redirige_a_login_si_no_hay_sesion

    // ── 9. verificar() retorna esFavorito=>true cuando el producto es favorito ──
    #[Test]
    public function verificar_retorna_true_cuando_producto_es_favorito(): void
    {
        $this->sesionCliente();

        $this->mock(FavoritoService::class, function ($mock) {
            $mock->shouldReceive('esFavorito')
                ->once()
                ->andReturn(true);
        });

        $response = $this->getJson(route('favoritos.verificar', ['id' => 10]));

        $response->assertStatus(200);
        $response->assertJson(['esFavorito' => true]);
    }
    // php artisan test --filter=FavoritoControllerTest::verificar_retorna_true_cuando_producto_es_favorito

    // ── 10. verificar() retorna esFavorito=>false cuando el producto no es favorito ──
    #[Test]
    public function verificar_retorna_false_cuando_producto_no_es_favorito(): void
    {
        $this->sesionCliente();

        $this->mock(FavoritoService::class, function ($mock) {
            $mock->shouldReceive('esFavorito')
                ->once()
                ->andReturn(false);
        });

        $response = $this->getJson(route('favoritos.verificar', ['id' => 10]));

        $response->assertStatus(200);
        $response->assertJson(['esFavorito' => false]);
    }
    // php artisan test --filter=FavoritoControllerTest::verificar_retorna_false_cuando_producto_no_es_favorito
}

// php artisan test --filter=FavoritoControllerTest