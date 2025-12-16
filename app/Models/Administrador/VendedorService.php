<?php

namespace App\Models\Administrador;

use Illuminate\Support\Facades\Http;

class VendedorService
{
    private $baseUrl = "http://localhost:8080/vendedor";
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

    public function obtenerVendedores()
    {
        $response = Http::withHeaders($this->headers())
            ->get($this->baseUrl);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    public function agregarVendedor($nombre, $apellido, $correo_electronico, $contrasena, $telefono)
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl, [
                "nombre" => $nombre,
                "apellido" => $apellido,
                "correo_electronico" => $correo_electronico,
                "contrasena" => $contrasena,
                "telefono" => $telefono
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

    public function actualizarVendedor($id_vendedor, $nombre, $apellido, $correo_electronico, $contrasena, $telefono)
    {
        $response = Http::withHeaders($this->headers())
            ->put($this->baseUrl . "/" . $id_vendedor, [
                "nombre" => $nombre,
                "apellido" => $apellido,
                "correo_electronico" => $correo_electronico,
                "contrasena" => $contrasena,
                "telefono" => $telefono
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

    public function eliminarVendedor($id_vendedor)
    {
        $response = Http::withHeaders($this->headers())
            ->delete($this->baseUrl . "/" . $id_vendedor);

        if ($response->successful()) {
            return ["success" => true];
        }

        return [
            "success" => false,
            "error" => "HTTP " . $response->status()
        ];
    }
}
