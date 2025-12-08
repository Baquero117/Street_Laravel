<?php

namespace App\Models\Administrador;

use Illuminate\Support\Facades\Http;

class ValoracionService
{
    private $baseUrl = "http://localhost:8080/valoracion";
    private $token;

    public function __construct()
    {
       
        $this->token = session('token');
    }

   
    private function headers()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type'  => 'application/json'
        ];
    }

   
    public function obtenerValoraciones()
    {
        $response = Http::withHeaders($this->headers())
            ->get($this->baseUrl);

        if ($response->successful()) {
            return [
                "success" => true,
                "data"    => $response->json()
            ];
        }

        return [
            "success" => false,
            "error"   => "HTTP " . $response->status()
        ];
    }

   
    public function eliminarValoracion($id_valoracion)
    {
        $response = Http::withHeaders($this->headers())
            ->delete($this->baseUrl . "/" . $id_valoracion);

        if ($response->successful()) {
            return [
                "success" => true,
                "data"    => $response->json()
            ];
        }

        return [
            "success" => false,
            "error"   => "HTTP " . $response->status()
        ];
    }
}
