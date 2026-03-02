<?php

namespace App\Models\Registro; // ✅ Está en Models\Registro

use Illuminate\Support\Facades\Http;

class VerificacionService
{
    private $baseUrl = "http://localhost:8080/verificacion";

    public function validarCodigo($correo, $codigo)
    {
        try {
            $response = Http::post($this->baseUrl . '/validar', [
                'correo_electronico' => $correo,
                'codigo'             => $codigo
            ]);

            if ($response->successful()) {
                return ['success' => true];
            }

            $error = match($response->status()) {
                400 => $response->json('error') ?? 'Código incorrecto o expirado.',
                default => 'Error al verificar el código. Intenta de nuevo.'
            };

            return ['success' => false, 'error' => $error];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Error al conectar con el servidor.'];
        }
    }

    public function reenviarCodigo($correo)
    {
        try {
            $response = Http::post($this->baseUrl . '/reenviar', [
                'correo_electronico' => $correo
            ]);

            if ($response->successful()) {
                return ['success' => true];
            }

            return ['success' => false, 'error' => 'No se pudo reenviar el código.'];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Error al conectar con el servidor.'];
        }
    }
}