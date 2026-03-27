<?php

namespace Tests\Feature\Administrador;

use Tests\TestCase;
use App\Models\Administrador\VendedorService;
use PHPUnit\Framework\Attributes\Test;

class VendedorControllerTest extends TestCase
{
    private array $sessionData = ['token' => 'fake-jwt-token'];

    // ── 1. index() retorna la vista con la lista de vendedores ──
    #[Test]
    public function index_retorna_vista_con_vendedores(): void
    {
        // Añadimos 'correo_electronico' y 'telefono' para que la vista no falle al renderizar
        $vendedoresFake = [
            [
                'id_vendedor' => 1, 
                'nombre' => 'Carlos', 
                'apellido' => 'Pérez',
                'correo_electronico' => 'carlos@test.com',
                'telefono' => '12345678'
            ],
        ];

        $this->mock(VendedorService::class, function ($mock) use ($vendedoresFake) {
            $mock->shouldReceive('obtenerVendedores')
                ->once()
                ->andReturn($vendedoresFake);
        });

        $response = $this->withSession($this->sessionData)
                         ->get(route('vendedor.index'));

        $response->assertStatus(200);
        $response->assertViewIs('Administrador.Vendedor');
        $response->assertViewHas('vendedores', $vendedoresFake);
    }
    // php artisan test --filter=VendedorControllerTest::index_retorna_vista_con_vendedores

    // ── 2. store() crea un vendedor exitosamente ──
    #[Test]
    public function store_crea_vendedor_y_redirige_con_exito(): void
    {
        $this->mock(VendedorService::class, function ($mock) {
            $mock->shouldReceive('agregarVendedor')
                ->once()
                ->andReturn(['success' => true]);
        });

        $response = $this->withSession($this->sessionData)
                         ->post(route('vendedor.agregar'), [
                             'nombre' => 'Luis',
                             'apellido' => 'Rodríguez',
                             'correo_electronico' => 'luis@test.com',
                             'contrasena' => 'password123',
                             'telefono' => '3001234567'
                         ]);

        $response->assertRedirect(route('vendedor.index'));
        $response->assertSessionHas('mensaje', 'Vendedor agregado correctamente.');
    }
    // php artisan test --filter=VendedorControllerTest::store_crea_vendedor_y_redirige_con_exito

    // ── 3. store() falla validación por campos vacíos ──
    #[Test]
    public function store_falla_validacion_si_campos_vacios(): void
    {
        $response = $this->withSession($this->sessionData)
                         ->post(route('vendedor.agregar'), []);

        $response->assertSessionHasErrors(['nombre', 'apellido', 'correo_electronico', 'contrasena', 'telefono']);
    }
    // php artisan test --filter=VendedorControllerTest::store_falla_validacion_si_campos_vacios

    // ── 4. update() actualiza un vendedor correctamente ──
    #[Test]
    public function update_actualiza_vendedor_y_redirige_con_exito(): void
    {
        $this->mock(VendedorService::class, function ($mock) {
            $mock->shouldReceive('actualizarVendedor')
                ->once()
                ->andReturn(['success' => true]);
        });

        $response = $this->withSession($this->sessionData)
                         ->post(route('vendedor.actualizar'), [
                             'id_vendedor' => 1,
                             'nombre' => 'Carlos Editado',
                             'apellido' => 'Pérez',
                             'correo_electronico' => 'carlos@test.com',
                             'contrasena' => 'nueva123',
                             'telefono' => '3119998877'
                         ]);

        $response->assertRedirect(route('vendedor.index'));
        $response->assertSessionHas('mensaje', 'Vendedor actualizado correctamente.');
    }
    // php artisan test --filter=VendedorControllerTest::update_actualiza_vendedor_y_redirige_con_exito

    // ── 5. destroy() elimina un vendedor exitosamente ──
    #[Test]
    public function destroy_elimina_vendedor_y_redirige_con_exito(): void
    {
        $this->mock(VendedorService::class, function ($mock) {
            $mock->shouldReceive('eliminarVendedor')
                ->with(10)
                ->once()
                ->andReturn(['success' => true]);
        });

        $response = $this->withSession($this->sessionData)
                         ->post(route('vendedor.eliminar'), [
                             'id_vendedor' => 10
                         ]);

        $response->assertRedirect(route('vendedor.index'));
        $response->assertSessionHas('mensaje', 'Vendedor eliminado correctamente.');
    }
    // php artisan test --filter=VendedorControllerTest::destroy_elimina_vendedor_y_redirige_con_exito

    // ── 6. store() maneja el error cuando el servicio (API) falla ──
    #[Test]
    public function store_redirige_con_error_si_servicio_falla(): void
    {
        $this->mock(VendedorService::class, function ($mock) {
            $mock->shouldReceive('agregarVendedor')
                ->once()
                ->andReturn(['success' => false, 'error' => 'El correo ya existe en el sistema externo']);
        });

        $response = $this->withSession($this->sessionData)
                         ->post(route('vendedor.agregar'), [
                             'nombre' => 'Luis',
                             'apellido' => 'Rodríguez',
                             'correo_electronico' => 'error@test.com',
                             'contrasena' => 'password123',
                             'telefono' => '3001234567'
                         ]);

        $response->assertRedirect(route('vendedor.index'));
        $response->assertSessionHas('mensaje', 'Error: El correo ya existe en el sistema externo');
    }
    // php artisan test --filter=VendedorControllerTest::store_redirige_con_error_si_servicio_falla
}

// php artisan test --filter=VendedorControllerTest