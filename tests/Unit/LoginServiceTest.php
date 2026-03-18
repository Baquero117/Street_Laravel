<?php

namespace App\Models\Login {

    function curl_init($url)
    {
        return fopen('php://memory', 'r+');
    }

    function curl_setopt($ch, $option, $value)
    {
        return true;
    }

    function curl_exec($ch)
    {
        return json_encode([
            'tipo' => 'cliente',
            'token' => 'abc123',
            'usuario' => [
                'id_cliente' => 1,
                'nombre' => 'Alexandra',
                'correo_electronico' => 'alex@test.com'
            ]
        ]);
    }

    function curl_getinfo($ch, $opt)
    {
        return 200;
    }

    function curl_close($ch)
    {
        return true;
    }
}

namespace Tests\Unit {

    use Tests\TestCase;
    use App\Models\Login\LoginService; 

    class LoginServiceTest extends TestCase
    {
       
        public function autenticar_retorna_datos_cuando_respuesta_es_200()
        {
            $service = new LoginService();

            $resultado = $service->autenticar('alex@test.com', '123456');

            $this->assertNotNull($resultado);
            $this->assertEquals('cliente', $resultado['tipo']);
            $this->assertEquals('abc123', $resultado['token']);
            $this->assertEquals('Alexandra', $resultado['datos']['nombre']);
        }
    }
} //php artisan test --filter=LoginServiceTest