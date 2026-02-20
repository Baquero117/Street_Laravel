<?php

namespace App\Models\PuntoInicio;

class PedidosService
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = "http://localhost:8080";
    }

    public function obtenerPedidosPorCliente(int $idCliente, string $token): array
    {
        $curl = curl_init("{$this->apiUrl}/pedido/cliente/{$idCliente}");

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

    public function obtenerFactura(int $idPedido, string $token, bool $descargar): ?string
    {
        $endpoint = $descargar
            ? "{$this->apiUrl}/pedido/{$idPedido}/factura"
            : "{$this->apiUrl}/pedido/{$idPedido}/factura/ver";

        $curl = curl_init($endpoint);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$token}"
        ]);

        $respuesta = curl_exec($curl);
        $httpCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            return $respuesta; // binario PDF
        }

        return null;
    }
}