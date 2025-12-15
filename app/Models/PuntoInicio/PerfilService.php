<?php

namespace App\Models\PuntoInicio;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PerfilService
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = "http://localhost:8080/cliente";
    }

    public function obtenerPerfil()
    {
        $token = Session::get('token');

        if (!$token) {
            return null;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($this->apiUrl . "/perfil");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function actualizarPerfil(
        string $nombre,
        string $apellido,
        ?string $contrasena,
        string $direccion,
        string $telefono,
        string $correo_electronico
    ) {
        $token = Session::get('token');

        if (!$token) {
            return [
                "success" => false,
                "error" => "Token no encontrado"
            ];
        }

        $data = [
            "nombre" => $nombre,
            "apellido" => $apellido,
            "direccion" => $direccion,
            "telefono" => $telefono,
            "correo_electronico" => $correo_electronico
        ];

        if (!empty($contrasena)) {
            $data["contrasena"] = $contrasena;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->put($this->apiUrl . "/perfil", $data);

        if ($response->successful()) {
            return [
                "success" => true,
                "data" => $response->json()
            ];
        }

        return [
            "success" => false,
            "error" => "HTTP " . $response->status(),
            "response" => $response->body()
        ];
    }
}
