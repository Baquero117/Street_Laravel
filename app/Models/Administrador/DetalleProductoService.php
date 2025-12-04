<?php

namespace App\Services\Administrador;

use Illuminate\Support\Facades\Http;

class DetalleProductoService
{
    private $baseUrl = "http://localhost:8080/detalle_producto";
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

    public function agregarDetalle($talla, $color, $rutaImagen, $id_producto, $id_categoria, $precio)
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl, [
                "talla" => $talla,
                "color" => $color,
                "imagen" => $rutaImagen,   // <= nombre del archivo ya guardado en storage
                "id_producto" => $id_producto,
                "id_categoria" => $id_categoria,
                "precio" => $precio
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

    public function actualizarDetalle($id, $talla, $color, $rutaImagen = null, $id_producto, $id_categoria, $precio)
    {
        $payload = [
            "talla" => $talla,
            "color" => $color,
            "id_producto" => $id_producto,
            "id_categoria" => $id_categoria,
            "precio" => $precio
        ];

        // 🔥 Solo enviar imagen si existe una nueva
        if ($rutaImagen !== null) {
            $payload["imagen"] = $rutaImagen;
        }

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
}
