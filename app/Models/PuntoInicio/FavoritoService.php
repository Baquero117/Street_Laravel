<?php

namespace App\Models\PuntoInicio;

class FavoritoService
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = "http://localhost:8080";
    }

    /**
     * Obtener todos los favoritos de un cliente (con datos del producto)
     */
    public function obtenerFavoritos(int $idCliente, string $token): array
    {
        $curl = curl_init("{$this->apiUrl}/favorito/{$idCliente}");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: Bearer {$token}"
        ]);

        $respuesta = curl_exec($curl);
        $httpCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            return json_decode($respuesta, true) ?? [];
        }

        return [];
    }

    /**
     * Agregar producto a favoritos
     */
    public function agregarFavorito(int $idCliente, int $idProducto, string $token): array
    {
        $curl = curl_init("{$this->apiUrl}/favorito/{$idCliente}/{$idProducto}");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, '');
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: Bearer {$token}"
        ]);

        $respuesta = curl_exec($curl);
        $httpCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return [
            'ok'      => $httpCode === 200,
            'mensaje' => $respuesta,
        ];
    }

    /**
     * Eliminar favorito por su ID
     */
    public function eliminarFavorito(int $idFavorito, string $token): array
    {
        $curl = curl_init("{$this->apiUrl}/favorito/{$idFavorito}");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: Bearer {$token}"
        ]);

        $respuesta = curl_exec($curl);
        $httpCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return [
            'ok'      => $httpCode === 200,
            'mensaje' => $respuesta,
        ];
    }

    /**
     * Verificar si un producto ya está en favoritos
     */
    public function esFavorito(int $idCliente, int $idProducto, string $token): bool
    {
        $curl = curl_init("{$this->apiUrl}/favorito/verificar/{$idCliente}/{$idProducto}");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: Bearer {$token}"
        ]);

        $respuesta = curl_exec($curl);
        $httpCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            $data = json_decode($respuesta, true);
            return isset($data['esFavorito']) && $data['esFavorito'] === true;
        }

        return false;
    }
}