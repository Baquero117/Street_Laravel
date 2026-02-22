<?php

namespace App\Models\Recuperacion;

use Illuminate\Support\Facades\Http;

class RecuperacionService
{
    private $baseUrl = "http://localhost:8080/recuperacion";

    // Llama a Spring Boot para que genere el token y envíe el email
    public function solicitarRecuperacion($correo)
    {
        try {
            $response = Http::post($this->baseUrl . '/solicitar', [
                'correo_electronico' => $correo
            ]);

            return ['success' => true];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Error al conectar con el servidor.'];
        }
    }

    // Llama a Spring Boot para restablecer la contraseña con el token
    public function restablecerContrasena($token, $contrasena)
    {
        try {
            $response = Http::post($this->baseUrl . '/restablecer', [
                'token'     => $token,
                'contrasena' => $contrasena
            ]);

            if ($response->successful()) {
                return ['success' => true];
            }

            // Manejar errores específicos de Spring Boot
            $error = match($response->status()) {
                400 => 'El token es inválido o ha expirado.',
                default => 'Error al restablecer la contraseña. Intenta de nuevo.'
            };

            return ['success' => false, 'error' => $error];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Error al conectar con el servidor.'];
        }
    }
}