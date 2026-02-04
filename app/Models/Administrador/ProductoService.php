<?php

namespace App\Models\Administrador;

use Illuminate\Support\Facades\Http;

class ProductoService
{
    private $baseUrl = "http://localhost:8080/producto";
    private $token;

    public function __construct()
    {
        // Token guardado en la sesión
        $this->token = session('token');
    }

    private function headers()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json'
        ];
    }

    // ============================
    // 🔹 OBTENER LISTA DE PRODUCTOS
    // ============================
    public function obtenerProductos()
    {
        $response = Http::withHeaders($this->headers())
            ->get($this->baseUrl);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    // ============================
    // 🔹 AGREGAR PRODUCTO
    // ============================
    public function agregarProducto($nombre, $descripcion, $cantidad, $rutaImagen, $id_vendedor, $estado, $precio, $color)
    {
        $payload = [
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "cantidad" => $cantidad,
            "imagen" => $rutaImagen,
            "id_vendedor" => $id_vendedor,
            "estado" => $estado,
            "precio" => $precio,
            "color" => $color
        ];

        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl, $payload);

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

    // ============================
    // 🔹 ACTUALIZAR PRODUCTO
    // ============================
    public function actualizarProducto($id, $nombre, $descripcion, $cantidad, $rutaImagen = null, $id_vendedor, $estado, $precio, $color)
    {
        $payload = [
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "cantidad" => $cantidad,
            "id_vendedor" => $id_vendedor,
            "estado" => $estado,
            "precio" => $precio,
            "color" => $color
        ];

        // Solo enviar imagen si se envió una nueva
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

    // ============================
    // 🔹 ELIMINAR PRODUCTO
    // ============================
    public function eliminarProducto($id)
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
