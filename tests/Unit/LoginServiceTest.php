<?php

namespace Tests\Unit;

use App\Models\Login\LoginService;
use Tests\TestCase;

class LoginServiceTest extends TestCase
{
    private LoginService $loginService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loginService = new LoginService();
    }

    // autenticar - Login exitoso
    public function test_autenticar_login_exitoso()
    {
        $reflection = new \ReflectionClass($this->loginService);
        $property = $reflection->getProperty('apiUrl');
        $property->setAccessible(true);
        
        $mockResponse = [
            'tipo' => 'cliente',
            'token' => 'token_valido_123',
            'usuario' => ['id_cliente' => 1, 'nombre' => 'Juan', 'correo_electronico' => 'juan@example.com']
        ];
        
        $resultado = $this->loginService->autenticar('juan@example.com', 'password123');
        $this->assertIsArray($resultado);
    }
    // php artisan test --filter=test_autenticar_login_exitoso

    public function test_autenticar_cuenta_no_verificada()
    {
        $resultado = $this->loginService->autenticar('noVerificado@example.com', 'password123');
        $this->assertNull($resultado);
    }
    // php artisan test --filter=test_autenticar_cuenta_no_verificada

    public function test_autenticar_credenciales_invalidas()
    {
        $resultado = $this->loginService->autenticar('invalid@example.com', 'wrongpassword');
        $this->assertNull($resultado);
    }
    // php artisan test --filter=test_autenticar_credenciales_invalidas
}

/*
php artisan test tests/Unit/LoginServiceTest.php
*/