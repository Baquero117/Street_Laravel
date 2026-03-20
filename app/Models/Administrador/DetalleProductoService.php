<?php

namespace App\Models\Administrador;

use Illuminate\Support\Facades\Http;

class DetalleProductoService
{
    private $baseUrl = "http://34.225.197.89:8080/detalle_producto";
    private $token;

    public function __construct()
    {
        // Token guardado en session
        $this->token = session('token');
    }

    private function headers()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json'
        ];
    }

    public function obtenerDetalles()
    {
        $response = Http::withHeaders($this->headers())
            ->get($this->baseUrl);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    public function agregarDetalle($talla, $id_producto, $cantidad)
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl, [
                "talla" => $talla,
                "id_producto" => $id_producto,
                "cantidad" => $cantidad
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

    public function actualizarDetalle($id, $talla, $id_producto, $cantidad)
    {
        $payload = [
            "talla" => $talla,
            "id_producto" => $id_producto,
            "cantidad" => $cantidad
        ];

        $response = Http::withHeaders($this->headers())
            ->put($this->baseUrl . "/" . $id, $payload);

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

    public function eliminarDetalle($id)
    {
        $response = Http::withHeaders($this->headers())
            ->delete($this->baseUrl . "/" . $id);

        if ($response->successful()) {
            return ["success" => true];
        }

        return [
            "success" => false,
            "error" => "HTTP " . $response->status()
        ];
    }

    public function buscarDetalleProducto($id_producto)
    {
        return Http::withHeaders($this->headers())
            ->get($this->baseUrl . '/detalle_producto/buscar', [
                'id_producto' => $id_producto
            ])
            ->json();
    }
}