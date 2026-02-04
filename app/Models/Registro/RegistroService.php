<?php

namespace App\Models\Registro;

use Illuminate\Support\Facades\Http;

class RegistroService
{
    private $baseUrl = "http://localhost:8080/cliente";

    public function registrarUsuario($nombre, $apellido, $direccion, $telefono, $correo_electronico, $contrasena)
    {
        $response = Http::post($this->baseUrl, [
            "nombre" => $nombre,
            "apellido" => $apellido,
            "direccion" => $direccion,
            "telefono" => $telefono,
            "correo_electronico" => $correo_electronico,
            "contrasena" => $contrasena
        ]);

        if ($response->successful()) {
            return [
                "success" => true,
                "data" => $response->json()
            ];
        }

        return [
            "success" => false,
            "error" => "HTTP " . $response->status()
        ];
    }
}
