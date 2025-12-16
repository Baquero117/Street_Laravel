<?php

namespace App\Models\Administrador;

use Illuminate\Support\Facades\Http;

class CategoriaService
{
    private $baseUrl = "http://localhost:8080/categoria";
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

    public function obtenerCategorias()
    {
        $response = Http::withHeaders($this->headers())
            ->get($this->baseUrl);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    public function agregarCategoria($nombre)
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl, [
                "nombre" => $nombre
            ]);

        if ($response->successful()) {
            return ["success" => true, "data" => $response->json()];
        }

        return ["success" => false, "error" => "HTTP " . $response->status()];
    }

    public function actualizarCategoria($id, $nombre)
    {
        $response = Http::withHeaders($this->headers())
            ->put($this->baseUrl . "/" . $id, [
                "nombre" => $nombre
            ]);

        if ($response->successful()) {
            return ["success" => true, "data" => $response->json()];
        }

        return ["success" => false, "error" => "HTTP " . $response->status()];
    }

    public function eliminarCategoria($id)
    {
        $response = Http::withHeaders($this->headers())
            ->delete($this->baseUrl . "/" . $id);

        if ($response->successful()) {
            return ["success" => true];
        }

        return ["success" => false, "error" => "HTTP " . $response->status()];
    }
}
