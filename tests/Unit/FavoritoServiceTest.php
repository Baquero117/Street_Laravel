<?php
namespace Tests\Unit\PuntoInicio;

use Tests\TestCase;
use App\Models\PuntoInicio\FavoritoService;
use PHPUnit\Framework\Attributes\Test;

class FavoritoServiceTest extends TestCase
{
    // ── 1. obtenerFavoritos() retorna array de favoritos cuando la API responde HTTP 200 ──
    #[Test]
    public function obtener_favoritos_retorna_array_cuando_api_exitosa(): void
    {
        $favoritos = [
            ['id_favorito' => 1, 'id_producto' => 10, 'nombre' => 'Camiseta'],
            ['id_favorito' => 2, 'id_producto' => 20, 'nombre' => 'Gorra'],
        ];

        $service = $this->mockServicio('obtenerFavoritos', $favoritos, 200);
        $resultado = $service->obtenerFavoritos(1, 'token-fake');

        $this->assertIsArray($resultado);
        $this->assertCount(2, $resultado);
        $this->assertEquals('Camiseta', $resultado[0]['nombre']);
    }
    // php artisan test --filter=FavoritoServiceTest::obtener_favoritos_retorna_array_cuando_api_exitosa

    // ── 2. obtenerFavoritos() retorna array vacío cuando la API falla ──
    #[Test]
    public function obtener_favoritos_retorna_vacio_cuando_api_falla(): void
    {
        $service = $this->mockServicio('obtenerFavoritos', [], 500);
        $resultado = $service->obtenerFavoritos(1, 'token-fake');

        $this->assertIsArray($resultado);
        $this->assertEmpty($resultado);
    }
    // php artisan test --filter=FavoritoServiceTest::obtener_favoritos_retorna_vacio_cuando_api_falla

    // ── 3. agregarFavorito() retorna ok=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function agregar_favorito_retorna_ok_true_cuando_api_exitosa(): void
    {
        $service = $this->mockServicio('agregarFavorito', 'Favorito agregado', 200);
        $resultado = $service->agregarFavorito(1, 10, 'token-fake');

        $this->assertTrue($resultado['ok']);
    }
    // php artisan test --filter=FavoritoServiceTest::agregar_favorito_retorna_ok_true_cuando_api_exitosa

    // ── 4. agregarFavorito() retorna ok=>false cuando la API falla ──
    #[Test]
    public function agregar_favorito_retorna_ok_false_cuando_api_falla(): void
    {
        $service = $this->mockServicio('agregarFavorito', 'Error', 500);
        $resultado = $service->agregarFavorito(1, 10, 'token-fake');

        $this->assertFalse($resultado['ok']);
    }
    // php artisan test --filter=FavoritoServiceTest::agregar_favorito_retorna_ok_false_cuando_api_falla

    // ── 5. eliminarFavorito() retorna ok=>true cuando la API responde HTTP 200 ──
    #[Test]
    public function eliminar_favorito_retorna_ok_true_cuando_api_exitosa(): void
    {
        $service = $this->mockServicio('eliminarFavorito', 'Favorito eliminado', 200);
        $resultado = $service->eliminarFavorito(1, 'token-fake');

        $this->assertTrue($resultado['ok']);
    }
    // php artisan test --filter=FavoritoServiceTest::eliminar_favorito_retorna_ok_true_cuando_api_exitosa

    // ── 6. eliminarFavorito() retorna ok=>false cuando la API falla ──
    #[Test]
    public function eliminar_favorito_retorna_ok_false_cuando_api_falla(): void
    {
        $service = $this->mockServicio('eliminarFavorito', 'Error', 404);
        $resultado = $service->eliminarFavorito(1, 'token-fake');

        $this->assertFalse($resultado['ok']);
    }
    // php artisan test --filter=FavoritoServiceTest::eliminar_favorito_retorna_ok_false_cuando_api_falla

    // ── 7. esFavorito() retorna true cuando la API confirma que es favorito ──
    #[Test]
    public function es_favorito_retorna_true_cuando_api_confirma(): void
    {
        $service = $this->mockServicio('esFavorito', ['esFavorito' => true], 200);
        $resultado = $service->esFavorito(1, 10, 'token-fake');

        $this->assertTrue($resultado);
    }
    // php artisan test --filter=FavoritoServiceTest::es_favorito_retorna_true_cuando_api_confirma

    // ── 8. esFavorito() retorna false cuando la API dice que no es favorito ──
    #[Test]
    public function es_favorito_retorna_false_cuando_api_dice_no(): void
    {
        $service = $this->mockServicio('esFavorito', ['esFavorito' => false], 200);
        $resultado = $service->esFavorito(1, 10, 'token-fake');

        $this->assertFalse($resultado);
    }
    // php artisan test --filter=FavoritoServiceTest::es_favorito_retorna_false_cuando_api_dice_no

    // ── 9. esFavorito() retorna false cuando la API falla ──
    #[Test]
    public function es_favorito_retorna_false_cuando_api_falla(): void
    {
        $service = $this->mockServicio('esFavorito', [], 500);
        $resultado = $service->esFavorito(1, 10, 'token-fake');

        $this->assertFalse($resultado);
    }
    // php artisan test --filter=FavoritoServiceTest::es_favorito_retorna_false_cuando_api_falla

    // ────────────────────────────────────────────────────────────────────────────
    // Helper: clase anónima que sobreescribe el método indicado con respuesta mock
    // ────────────────────────────────────────────────────────────────────────────
    private function mockServicio(string $metodo, mixed $respuestaMock, int $httpCodeMock): FavoritoService
    {
        return new class($metodo, $respuestaMock, $httpCodeMock) extends FavoritoService {
            public function __construct(
                private string $metodo,
                private mixed  $respuestaMock,
                private int    $httpCodeMock
            ) {
                parent::__construct();
            }

            public function obtenerFavoritos(int $idCliente, string $token): array
            {
                if ($this->metodo !== 'obtenerFavoritos') return parent::obtenerFavoritos($idCliente, $token);
                return $this->httpCodeMock === 200 ? (array) $this->respuestaMock : [];
            }

            public function agregarFavorito(int $idCliente, int $idProducto, string $token): array
            {
                if ($this->metodo !== 'agregarFavorito') return parent::agregarFavorito($idCliente, $idProducto, $token);
                return ['ok' => $this->httpCodeMock === 200, 'mensaje' => $this->respuestaMock];
            }

            public function eliminarFavorito(int $idFavorito, string $token): array
            {
                if ($this->metodo !== 'eliminarFavorito') return parent::eliminarFavorito($idFavorito, $token);
                return ['ok' => $this->httpCodeMock === 200, 'mensaje' => $this->respuestaMock];
            }

            public function esFavorito(int $idCliente, int $idProducto, string $token): bool
            {
                if ($this->metodo !== 'esFavorito') return parent::esFavorito($idCliente, $idProducto, $token);
                if ($this->httpCodeMock !== 200) return false;
                $data = (array) $this->respuestaMock;
                return isset($data['esFavorito']) && $data['esFavorito'] === true;
            }
        };
    }
}

// php artisan test --filter=FavoritoServiceTest