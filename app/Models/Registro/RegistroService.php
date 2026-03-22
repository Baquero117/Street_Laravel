<?php

namespace App\Models\Registro;

use Illuminate\Support\Facades\Http;

class RegistroService
{
    private $baseUrl = "http://localhost:8080/cliente";

    public function registrarUsuario($nombre, $apellido, $departamento, $municipio, $direccion, $telefono, $correo_electronico, $contrasena)
    {
        $response = Http::post($this->baseUrl, [
            "nombre"             => $nombre,
            "apellido"           => $apellido,
            "departamento"       => $departamento,
            "municipio"          => $municipio,
            "direccion"          => $direccion,
            "telefono"           => $telefono,
            "correo_electronico" => $correo_electronico,
            "contrasena"         => $contrasena
        ]);

        if ($response->successful()) {
            return ['success' => true, 'data' => $response->json()];
        }

        if ($response->status() === 403) {
            return [
                'success' => false,
                'error'   => 'Este correo electrónico ya tiene una cuenta registrada.'
            ];
        }

        if ($response->status() === 404) {
            return [
                'success' => false,
                'error'   => 'La dirección de correo electrónico no fue encontrada. Verifica que sea correcta.'
            ];
        }

        return [
            'success' => false,
            'error'   => 'Ocurrió un error al registrar. Intenta de nuevo más tarde.'
        ];
    }
}
