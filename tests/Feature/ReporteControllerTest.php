<?php

namespace Tests\Feature\Administrador;

use Tests\TestCase;
use App\Models\Administrador\PedidoService;
use App\Models\Administrador\ProductoService;
use App\Models\Administrador\ClienteService;
use PHPUnit\Framework\Attributes\Test;

class ReporteControllerTest extends TestCase
{
    private array $sessionData = ['token' => 'fake-jwt-token'];

    // ── 1. index() calcula correctamente los totales y filtra stock bajo ──
    #[Test]
    public function index_calcula_reportes_y_retorna_vista(): void
    {
        // 1. Pedidos con datos suficientes para la vista
        $pedidosFake = [
            'success' => true,
            'data' => [
                [
                    'id_pedido' => 1, 
                    'total' => 100, 
                    'fecha_pedido' => '2026-01-01',
                    'id_cliente' => 1,
                    'estado' => 'Completado'
                ],
                [
                    'id_pedido' => 2, 
                    'total' => 200, 
                    'fecha_pedido' => '2026-01-02',
                    'id_cliente' => 2,
                    'estado' => 'Pendiente'
                ],
            ]
        ];

        // 2. Productos: Añadimos 'precio' que es lo que rompió el test anterior
        $productosFake = [
            [
                'id_producto' => 1, 
                'nombre' => 'Tenis Nike', 
                'cantidad' => 2, 
                'precio' => 120.00,
                'estado' => 'Completado'
            ],
            [
                'id_producto' => 2, 
                'nombre' => 'Gorra Vans', 
                'cantidad' => 10, 
                'precio' => 25.00,
                'estado' => 'Pendiente'
            ],
        ];

        // 3. Clientes
        $clientesFake = [
            'success' => true,
            'data' => [
                ['id_cliente' => 1, 'nombre' => 'Usuario Test'],
            ]
        ];

        // Configuración de Mocks
        $this->mock(PedidoService::class, function ($mock) use ($pedidosFake) {
            $mock->shouldReceive('obtenerPedidos')->once()->andReturn($pedidosFake);
        });

        $this->mock(ProductoService::class, function ($mock) use ($productosFake) {
            $mock->shouldReceive('obtenerProductos')->once()->andReturn($productosFake);
        });

        $this->mock(ClienteService::class, function ($mock) use ($clientesFake) {
            $mock->shouldReceive('obtenerClientes')->once()->andReturn($clientesFake);
        });

        // Ejecución
        $response = $this->withSession($this->sessionData)
                         ->get(route('admin.Reportes'));

        // Assertions
        $response->assertStatus(200);
        $response->assertViewIs('Administrador.Reportes');
        $response->assertViewHas('totalVentas', 300);
        $response->assertViewHas('totalProductos', 2);
        
        $productosBajos = $response->viewData('productosStockBajo');
        $this->assertCount(1, $productosBajos);
    }
    // php artisan test --filter=ReporteControllerTest::index_calcula_reportes_y_retorna_vista

    // ── 2. index() maneja respuestas de error de los servicios sin romperse ──
    #[Test]
    public function index_maneja_errores_de_servicios_y_retorna_totales_en_cero(): void
    {
        // Simulamos que los servicios fallan devolviendo success => false
        $this->mock(PedidoService::class, function ($mock) {
            $mock->shouldReceive('obtenerPedidos')->andReturn(['success' => false]);
        });

        $this->mock(ProductoService::class, function ($mock) {
            $mock->shouldReceive('obtenerProductos')->andReturn([]);
        });

        $this->mock(ClienteService::class, function ($mock) {
            $mock->shouldReceive('obtenerClientes')->andReturn(['success' => false]);
        });

        $response = $this->withSession($this->sessionData)
                         ->get(route('admin.Reportes'));

        $response->assertStatus(200);
        $response->assertViewHas('totalVentas', 0);
        $response->assertViewHas('totalClientes', 0);
    }
    // php artisan test --filter=ReporteControllerTest::index_maneja_errores_de_servicios_y_retorna_totales_en_cero
}

// php artisan test --filter=ReporteControllerTest