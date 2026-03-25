<?php
namespace Tests\Feature\Registro;

use Tests\TestCase;
use App\Models\Registro\RegistroService;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class RegistroControllerTest extends TestCase
{
    // ── Datos base reutilizables ──
    private function datosFormulario(array $override = []): array
    {
        return array_merge([
            'nombre'             => 'Juan',
            'apellido'           => 'Pérez',
            'departamento'       => 'Antioquia',
            'municipio'          => 'Medellín',
            'direccion'          => 'Calle 123',
            'telefono'           => '3001234567',
            'correo_electronico' => 'juan@test.com',
            'contrasena'         => 'pass1234',
        ], $override);
    }

    // ── 1. mostrar() retorna la vista de registro ──
    #[Test]
    public function mostrar_retorna_vista_registro(): void
    {
        $response = $this->get(route('registro'));

        $response->assertStatus(200);
        $response->assertViewIs('registro.registro');
    }
    // php artisan test --filter=RegistroControllerTest::mostrar_retorna_vista_registro

    // ── 2. procesar() falla validación si faltan campos requeridos ──
    #[Test]
    public function procesar_falla_validacion_si_faltan_campos(): void
    {
        $response = $this->post(route('registro.procesar'), []);

        $response->assertSessionHasErrors([
            'nombre', 'apellido', 'departamento', 'municipio',
            'direccion', 'telefono', 'correo_electronico', 'contrasena',
        ]);
    }
    // php artisan test --filter=RegistroControllerTest::procesar_falla_validacion_si_faltan_campos

    // ── 3. procesar() falla validación si el correo no es válido ──
    #[Test]
    public function procesar_falla_validacion_si_correo_invalido(): void
    {
        $response = $this->post(route('registro.procesar'), $this->datosFormulario([
            'correo_electronico' => 'no-es-un-correo',
        ]));

        $response->assertSessionHasErrors(['correo_electronico']);
    }
    // php artisan test --filter=RegistroControllerTest::procesar_falla_validacion_si_correo_invalido

    // ── 4. procesar() redirige a verificacion con mensaje cuando el registro es exitoso ──
    #[Test]
    public function procesar_redirige_a_verificacion_cuando_registro_exitoso(): void
    {
        $this->mock(RegistroService::class, function ($mock) {
            $mock->shouldReceive('registrarUsuario')
                ->once()
                ->andReturn(['success' => true, 'data' => ['id_cliente' => 1]]);
        });

        $response = $this->post(route('registro.procesar'), $this->datosFormulario());

        $response->assertRedirect(route('verificacion.mostrar'));
        $response->assertSessionHas('mensaje', 'Cuenta creada. Revisa tu correo e ingresa el código de verificación.');
        $this->assertEquals('juan@test.com', Session::get('correo_verificacion'));
    }
    // php artisan test --filter=RegistroControllerTest::procesar_redirige_a_verificacion_cuando_registro_exitoso

    // ── 5. procesar() guarda el correo en sesión cuando el registro es exitoso ──
    #[Test]
    public function procesar_guarda_correo_en_sesion_cuando_registro_exitoso(): void
    {
        $this->mock(RegistroService::class, function ($mock) {
            $mock->shouldReceive('registrarUsuario')
                ->once()
                ->andReturn(['success' => true, 'data' => []]);
        });

        $this->post(route('registro.procesar'), $this->datosFormulario());

        $this->assertEquals('juan@test.com', Session::get('correo_verificacion'));
    }
    // php artisan test --filter=RegistroControllerTest::procesar_guarda_correo_en_sesion_cuando_registro_exitoso

    // ── 6. procesar() redirige a registro con error cuando el correo ya está registrado ──
    #[Test]
    public function procesar_redirige_a_registro_con_error_cuando_correo_duplicado(): void
    {
        $this->mock(RegistroService::class, function ($mock) {
            $mock->shouldReceive('registrarUsuario')
                ->once()
                ->andReturn([
                    'success' => false,
                    'error'   => 'Este correo electrónico ya tiene una cuenta registrada.',
                ]);
        });

        $response = $this->post(route('registro.procesar'), $this->datosFormulario());

        $response->assertRedirect(route('registro'));
        $response->assertSessionHas('error', 'Este correo electrónico ya tiene una cuenta registrada.');
    }
    // php artisan test --filter=RegistroControllerTest::procesar_redirige_a_registro_con_error_cuando_correo_duplicado

    // ── 7. procesar() redirige a registro con error genérico cuando el servicio falla ──
    #[Test]
    public function procesar_redirige_a_registro_con_error_generico_cuando_servicio_falla(): void
    {
        $this->mock(RegistroService::class, function ($mock) {
            $mock->shouldReceive('registrarUsuario')
                ->once()
                ->andReturn([
                    'success' => false,
                    'error'   => 'Ocurrió un error al registrar. Intenta de nuevo más tarde.',
                ]);
        });

        $response = $this->post(route('registro.procesar'), $this->datosFormulario());

        $response->assertRedirect(route('registro'));
        $response->assertSessionHas('error', 'Ocurrió un error al registrar. Intenta de nuevo más tarde.');
    }
    // php artisan test --filter=RegistroControllerTest::procesar_redirige_a_registro_con_error_generico_cuando_servicio_falla
}

// php artisan test --filter=RegistroControllerTest