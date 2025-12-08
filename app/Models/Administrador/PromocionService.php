<?php

namespace App\Models\Administrador;

use Illuminate\Support\Facades\Http;

class PromocionService
{
    private $baseUrl = "http://localhost:8080/promocion";
    private $token;

    public function __construct()
    {
        
        $this->token = session('token');
    }

    private function headers()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json'
        ];
    }

    public function obtenerPromociones()
    {
        $response = Http::withHeaders($this->headers())
            ->get($this->baseUrl);

        if ($response->successful()) {
            return $response->json(); 
        }

        return [];
    }

    public function crearPromocion($descripcion, $descuento, $fecha_inicio, $fecha_fin, $id_producto)
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl, [
                "descripcion" => $descripcion,
                "descuento" => $descuento,
                "fecha_inicio" => $fecha_inicio,
                "fecha_fin" => $fecha_fin,
                "id_producto" => $id_producto
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

    public function actualizarPromocion($id_promocion, $descripcion, $descuento, $fecha_inicio, $fecha_fin, $id_producto)
    {
        $response = Http::withHeaders($this->headers())
            ->put($this->baseUrl . "/" . $id_promocion, [
                "descripcion" => $descripcion,
                "descuento" => $descuento,
                "fecha_inicio" => $fecha_inicio,
                "fecha_fin" => $fecha_fin,
                "id_producto" => $id_producto
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

    public function eliminarPromocion($id_promocion)
    {
        $response = Http::withHeaders($this->headers())
            ->delete($this->baseUrl . "/" . $id_promocion);

        if ($response->successful()) {
            return ["success" => true];
        }

        return [
            "success" => false,
            "error" => "HTTP " . $response->status()
        ];
    }
}
