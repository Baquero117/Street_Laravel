<?php
namespace Tests\Feature\Administrador;

use Tests\TestCase;
use App\Models\Administrador\CategoriaService;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class CategoriaControllerTest extends TestCase
{
    // ── Helper de sesión admin ──
    private function sesionAdmin(): void
    {
        Session::put('token', 'token-fake');
        Session::put('usuario_tipo', 'administrador');
        Session::put('usuario_id', 1);
    }

    // ── 1. index() redirige a login si no hay token (interceptado por constructor) ──
    #[Test]
    public function index_redirige_a_login_si_no_hay_token(): void
    {
        $response = $this->get(route('categoria.index'));

        $response->assertStatus(302);
    }
    // php artisan test --filter=CategoriaControllerTest::index_redirige_a_login_si_no_hay_token

    // ── 2. index() retorna la vista de categorias cuando hay sesión ──
    #[Test]
    public function index_retorna_vista_cuando_autenticado(): void
    {
        $this->sesionAdmin();

        $this->mock(CategoriaService::class, function ($mock) {
            $mock->shouldReceive('obtenerCategorias')
                ->once()
                ->andReturn([
                    ['id_categoria' => 1, 'nombre' => 'Hombre'],
                    ['id_categoria' => 2, 'nombre' => 'Mujer'],
                ]);
        });

        $response = $this->get(route('categoria.index'));

        $response->assertStatus(200);
        $response->assertViewIs('administrador.categoria');
        $response->assertViewHas('categorias');
    }
    // php artisan test --filter=CategoriaControllerTest::index_retorna_vista_cuando_autenticado

    // ── 3. store() falla validación si el nombre está vacío ──
    #[Test]
    public function store_falla_validacion_si_nombre_vacio(): void
    {
        $this->sesionAdmin();

        $response = $this->post(route('categoria.agregar'), ['nombre' => '']);

        $response->assertSessionHasErrors(['nombre']);
    }
    // php artisan test --filter=CategoriaControllerTest::store_falla_validacion_si_nombre_vacio

    // ── 4. store() redirige con mensaje de éxito cuando el servicio agrega correctamente ──
    #[Test]
    public function store_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();

        $this->mock(CategoriaService::class, function ($mock) {
            $mock->shouldReceive('agregarCategoria')
                ->once()
                ->andReturn(['success' => true, 'data' => []]);
        });

        $response = $this->post(route('categoria.agregar'), ['nombre' => 'Moda']);

        $response->assertRedirect(route('categoria.index'));
        $response->assertSessionHas('mensaje', 'Categoría agregada correctamente');
    }
    // php artisan test --filter=CategoriaControllerTest::store_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 5. store() redirige con mensaje de error cuando el servicio falla ──
    #[Test]
    public function store_redirige_con_mensaje_error_cuando_servicio_falla(): void
    {
        $this->sesionAdmin();

        $this->mock(CategoriaService::class, function ($mock) {
            $mock->shouldReceive('agregarCategoria')
                ->once()
                ->andReturn(['success' => false, 'error' => 'HTTP 500']);
        });

        $response = $this->post(route('categoria.agregar'), ['nombre' => 'Moda']);

        $response->assertRedirect(route('categoria.index'));
        $response->assertSessionHas('mensaje', 'Error: HTTP 500');
    }
    // php artisan test --filter=CategoriaControllerTest::store_redirige_con_mensaje_error_cuando_servicio_falla

    // ── 6. update() falla validación si el nombre está vacío ──
    #[Test]
    public function update_falla_validacion_si_nombre_vacio(): void
    {
        $this->sesionAdmin();

        $response = $this->post(route('categoria.actualizar'), [
            'id_categoria' => 1,
            'nombre'       => '',
        ]);

        $response->assertSessionHasErrors(['nombre']);
    }
    // php artisan test --filter=CategoriaControllerTest::update_falla_validacion_si_nombre_vacio

    // ── 7. update() redirige con mensaje de éxito cuando el servicio actualiza correctamente ──
    #[Test]
    public function update_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();

        $this->mock(CategoriaService::class, function ($mock) {
            $mock->shouldReceive('actualizarCategoria')
                ->once()
                ->andReturn(['success' => true, 'data' => []]);
        });

        $response = $this->post(route('categoria.actualizar'), [
            'id_categoria' => 1,
            'nombre'       => 'Hombre Updated',
        ]);

        $response->assertRedirect(route('categoria.index'));
        $response->assertSessionHas('mensaje', 'Categoría actualizada correctamente');
    }
    // php artisan test --filter=CategoriaControllerTest::update_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 8. update() redirige con mensaje de error cuando el servicio falla ──
    #[Test]
    public function update_redirige_con_mensaje_error_cuando_servicio_falla(): void
    {
        $this->sesionAdmin();

        $this->mock(CategoriaService::class, function ($mock) {
            $mock->shouldReceive('actualizarCategoria')
                ->once()
                ->andReturn(['success' => false, 'error' => 'HTTP 500']);
        });

        $response = $this->post(route('categoria.actualizar'), [
            'id_categoria' => 1,
            'nombre'       => 'Hombre Updated',
        ]);

        $response->assertRedirect(route('categoria.index'));
        $response->assertSessionHas('mensaje', 'Error: HTTP 500');
    }
    // php artisan test --filter=CategoriaControllerTest::update_redirige_con_mensaje_error_cuando_servicio_falla

    // ── 9. destroy() redirige con mensaje de éxito cuando el servicio elimina correctamente ──
    #[Test]
    public function destroy_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();

        $this->mock(CategoriaService::class, function ($mock) {
            $mock->shouldReceive('eliminarCategoria')
                ->once()
                ->andReturn(['success' => true]);
        });

        $response = $this->post(route('categoria.eliminar'), ['id_categoria' => 1]);

        $response->assertRedirect(route('categoria.index'));
        $response->assertSessionHas('mensaje', 'Categoría eliminada correctamente');
    }
    // php artisan test --filter=CategoriaControllerTest::destroy_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 10. destroy() redirige con mensaje de error cuando el servicio falla ──
    #[Test]
    public function destroy_redirige_con_mensaje_error_cuando_servicio_falla(): void
    {
        $this->sesionAdmin();

        $this->mock(CategoriaService::class, function ($mock) {
            $mock->shouldReceive('eliminarCategoria')
                ->once()
                ->andReturn(['success' => false, 'error' => 'HTTP 500']);
        });

        $response = $this->post(route('categoria.eliminar'), ['id_categoria' => 1]);

        $response->assertRedirect(route('categoria.index'));
        $response->assertSessionHas('mensaje', 'Error: HTTP 500');
    }
    // php artisan test --filter=CategoriaControllerTest::destroy_redirige_con_mensaje_error_cuando_servicio_falla
}

// php artisan test --filter=CategoriaControllerTest