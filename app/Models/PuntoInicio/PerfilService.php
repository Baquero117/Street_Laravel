<?php

namespace App\Models\PuntoInicio;

use Illuminate\Support\Facades\Session;

class PerfilService
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = "http://localhost:8080/cliente/";
    }

    public function obtenerPerfil()
    {
        // Obtener datos guardados al iniciar sesión
        $token = Session::get('token');
        $idCliente = Session::get('usuario_id');

        if (!$token || !$idCliente) {
            return null; // No está logeado
        }

        // Construcción del endpoint
        $url = $this->apiUrl . $idCliente;

        // Inicializar CURL
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "Content-Type: application/json"
        ]);

        $respuesta = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        // Verificar respuesta correcta
        if ($httpCode === 200) {
            return json_decode($respuesta, true);
        }

        return null;
    }
}

