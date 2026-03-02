<?php

namespace App\Models\Login;

class LoginService
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = "http://localhost:8080/auth/login";
    }

    public function autenticar($correo, $contrasena)
    {
        $datos = [
            'correo_electronico' => $correo,
            'contrasena'         => $contrasena,
        ];

        $curl = curl_init($this->apiUrl);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($datos));

        $respuesta = curl_exec($curl);
        $httpCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        // Login exitoso
        if ($httpCode === 200) {
            $resultado = json_decode($respuesta, true);
            return [
                'tipo'  => $resultado['tipo'],
                'token' => $resultado['token'],
                'datos' => $resultado['usuario']
            ];
        }

        // 👇 Cuenta no verificada
        if ($httpCode === 403) {
            $body = json_decode($respuesta, true);
            if (isset($body['error']) && $body['error'] === 'cuenta_no_verificada') {
                return [
                    'no_verificada' => true,
                    'correo'        => $body['correo'] ?? $correo
                ];
            }
        }

        return null;
    }
}