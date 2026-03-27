<?php
namespace Tests\Feature\Administrador;

use Tests\TestCase;
use App\Models\Administrador\ProductoService;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class ProductoControllerTest extends TestCase
{
    private function sesionAdmin(): void
    {
        Session::put('token', 'token-fake');
        Session::put('usuario_tipo', 'administrador');
        Session::put('usuario_id', 1);
    }

    // ── 1. index() redirige si no hay token ──
    #[Test]
    public function index_redirige_si_no_hay_token(): void
    {
        $response = $this->get(route('producto.index'));

        $response->assertStatus(302);
    }
    // php artisan test --filter=ProductoControllerTest::index_redirige_si_no_hay_token

    // ── 2. index() retorna la vista de productos cuando hay sesión ──
    #[Test]
    public function index_retorna_vista_cuando_autenticado(): void
    {
        $this->sesionAdmin();

        $this->mock(ProductoService::class, function ($mock) {
            $mock->shouldReceive('obtenerProductos')
                ->once()
                ->andReturn([
                    [
                        'id_producto' => 1,
                        'nombre'      => 'Camiseta',
                        'precio'      => 50000,
                        'imagen'      => 'productos/img.jpg',
                        'descripcion' => 'Descripción',
                        'estado'      => 'Activo',
                        'color'       => 'Negro',
                        'id_categoria'=> 20,
                        'cantidad'    => 10,
                        'id_vendedor' => 1,
                    ],
                ]);
        });

        $response = $this->get(route('producto.index'));

        $response->assertStatus(200);
        $response->assertViewIs('Administrador.Producto');
        $response->assertViewHas('productos');
    }
    // php artisan test --filter=ProductoControllerTest::index_retorna_vista_cuando_autenticado

    // ── 3. store() falla validación si faltan campos requeridos ──
    #[Test]
    public function store_falla_validacion_si_faltan_campos(): void
    {
        $this->sesionAdmin();

        $response = $this->post(route('producto.agregar'), []);

        $response->assertSessionHasErrors([
            'nombre', 'descripcion', 'cantidad',
            'id_vendedor', 'estado', 'imagen', 'precio',
        ]);
    }
    // php artisan test --filter=ProductoControllerTest::store_falla_validacion_si_faltan_campos

    // ── 4. store() redirige con mensaje de éxito cuando el servicio agrega correctamente ──
    #[Test]
    public function store_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();
        Storage::fake('public');

        $this->mock(ProductoService::class, function ($mock) {
            $mock->shouldReceive('agregarProducto')
                ->once()
                ->andReturn(['success' => true, 'data' => []]);
        });

        $response = $this->post(route('producto.agregar'), [
            'nombre'       => 'Camiseta',
            'descripcion'  => 'Descripción',
            'cantidad'     => 10,
            'id_vendedor'  => 1,
            'estado'       => 'Activo',
            'precio'       => 50000,
            'color'        => 'Negro',
            'id_categoria' => 20,
            'imagen'       => UploadedFile::fake()->create('camiseta.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect(route('producto.index'));
        $response->assertSessionHas('mensaje', 'Producto agregado correctamente.');
    }
    // php artisan test --filter=ProductoControllerTest::store_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 5. store() redirige con mensaje de error cuando el servicio falla ──
    #[Test]
    public function store_redirige_con_mensaje_error_cuando_servicio_falla(): void
    {
        $this->sesionAdmin();
        Storage::fake('public');

        $this->mock(ProductoService::class, function ($mock) {
            $mock->shouldReceive('agregarProducto')
                ->once()
                ->andReturn(['success' => false, 'error' => 'HTTP 500']);
        });

        $response = $this->post(route('producto.agregar'), [
            'nombre'       => 'Camiseta',
            'descripcion'  => 'Descripción',
            'cantidad'     => 10,
            'id_vendedor'  => 1,
            'estado'       => 'Activo',
            'precio'       => 50000,
            'imagen'       => UploadedFile::fake()->create('camiseta.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect(route('producto.index'));
        $response->assertSessionHas('mensaje', 'Error: HTTP 500');
    }
    // php artisan test --filter=ProductoControllerTest::store_redirige_con_mensaje_error_cuando_servicio_falla

    // ── 6. update() falla validación si id_producto no es numérico ──
    #[Test]
    public function update_falla_validacion_si_id_no_es_numerico(): void
    {
        $this->sesionAdmin();

        $response = $this->post(route('producto.actualizar'), [
            'id_producto' => 'no-numerico',
            'nombre'      => 'Camiseta',
            'descripcion' => 'Descripción',
            'cantidad'    => 10,
            'id_vendedor' => 1,
            'estado'      => 'Activo',
            'precio'      => 50000,
        ]);

        $response->assertSessionHasErrors(['id_producto']);
    }
    // php artisan test --filter=ProductoControllerTest::update_falla_validacion_si_id_no_es_numerico

    // ── 7. update() redirige con mensaje de éxito cuando el servicio actualiza correctamente ──
    #[Test]
    public function update_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();
        Storage::fake('public');

        $this->mock(ProductoService::class, function ($mock) {
            $mock->shouldReceive('actualizarProducto')
                ->once()
                ->andReturn(['success' => true, 'data' => []]);
        });

        $response = $this->post(route('producto.actualizar'), [
            'id_producto'  => 1,
            'nombre'       => 'Camiseta',
            'descripcion'  => 'Descripción',
            'cantidad'     => 10,
            'id_vendedor'  => 1,
            'estado'       => 'Activo',
            'precio'       => 50000,
            'imagen_actual'=> 'productos/img.jpg',
        ]);

        $response->assertRedirect(route('producto.index'));
        $response->assertSessionHas('mensaje', 'Producto actualizado correctamente.');
    }
    // php artisan test --filter=ProductoControllerTest::update_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 8. destroy() falla validación si id_producto no es numérico ──
    #[Test]
    public function destroy_falla_validacion_si_id_no_es_numerico(): void
    {
        $this->sesionAdmin();

        $response = $this->post(route('producto.eliminar'), [
            'id_producto' => 'no-numerico',
        ]);

        $response->assertSessionHasErrors(['id_producto']);
    }
    // php artisan test --filter=ProductoControllerTest::destroy_falla_validacion_si_id_no_es_numerico

    // ── 9. destroy() redirige con mensaje de éxito cuando el servicio elimina correctamente ──
    #[Test]
    public function destroy_redirige_con_mensaje_exitoso_cuando_servicio_exitoso(): void
    {
        $this->sesionAdmin();
        Storage::fake('public');

        $this->mock(ProductoService::class, function ($mock) {
            $mock->shouldReceive('eliminarProducto')
                ->once()
                ->andReturn(['success' => true]);
        });

        $response = $this->post(route('producto.eliminar'), ['id_producto' => 1]);

        $response->assertRedirect(route('producto.index'));
        $response->assertSessionHas('mensaje', 'Producto eliminado correctamente.');
    }
    // php artisan test --filter=ProductoControllerTest::destroy_redirige_con_mensaje_exitoso_cuando_servicio_exitoso

    // ── 10. buscar() retorna JSON con resultados de búsqueda ──
    #[Test]
    public function buscar_retorna_json_con_resultados(): void
    {
        $this->sesionAdmin();

        $this->mock(ProductoService::class, function ($mock) {
            $mock->shouldReceive('buscarProducto')
                ->once()
                ->with('Camiseta')
                ->andReturn([
                    ['id_producto' => 1, 'nombre' => 'Camiseta'],
                ]);
        });

        $response = $this->getJson(route('producto.buscar', ['nombre' => 'Camiseta']));

        $response->assertStatus(200);
        $response->assertJson([['id_producto' => 1, 'nombre' => 'Camiseta']]);
    }
    // php artisan test --filter=ProductoControllerTest::buscar_retorna_json_con_resultados
}

// php artisan test --filter=ProductoControllerTest