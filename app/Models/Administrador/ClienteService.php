<?php

namespace App\Models\Administrador;

use Illuminate\Support\Facades\Http;

class ClienteService
{
    private $baseUrl = "http://localhost:8080/cliente";
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

   public function obtenerClientes()
{
    $response = Http::withHeaders($this->headers())
        ->get($this->baseUrl);

    if ($response->successful()) {

        $data = $response->json();

        if (!is_array($data)) {
            $data = [];
        }

        // Si es un objeto single (tiene id_cliente directo), lo envuelve en array
        if (isset($data['id_cliente'])) {
            $data = [$data];
        }

        // Filtra cualquier elemento que no sea array (elimina bools, nulls, etc.)
        $data = array_filter($data, fn($item) => is_array($item));
        $data = array_values($data); // Reindexa

        return [
            "success" => true,
            "data" => $data
        ];
    }

    return [
        "success" => false,
        "data" => [],
        "error" => "HTTP " . $response->status()
    ];
}


    public function agregarCliente($nombre, $apellido, $contrasena, $direccion, $telefono, $correo_electronico)
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl, [
                "nombre" => $nombre,
                "apellido" => $apellido,
                "contrasena" => $contrasena,
                "direccion" => $direccion,
                "telefono" => $telefono,
                "correo_electronico" => $correo_electronico
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

    public function actualizarCliente($id, $nombre, $apellido, $contrasena, $direccion, $telefono, $correo_electronico)
    {
        $data = [
            "nombre" => $nombre,
            "apellido" => $apellido,
            "contrasena" => $contrasena, // Siempre viene con valor (actual o nueva)
            "direccion" => $direccion,
            "telefono" => $telefono,
            "correo_electronico" => $correo_electronico
        ];

        $response = Http::withHeaders($this->headers())
            ->put($this->baseUrl . "/" . $id, $data);

        if ($response->successful()) {
            return [
                "success" => true, 
                "data" => $response->json()
            ];
        }

        return [
            "success" => false,
            "error" => "HTTP " . $response->status() . " - " . $response->body()
        ];
    }

    public function eliminarCliente($id)
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

    public function obtenerClientePorId($id)
    {
        $response = Http::withHeaders($this->headers())
            ->get($this->baseUrl . '/' . $id);

        if ($response->successful()) {
            return [
                "success" => true,
                "data" => $response->json()
            ];
        }

        return [
            "success" => false,
            "data" => null,
            "error" => "HTTP " . $response->status()
        ];
    }

public function buscarCliente($dato)
{
    return Http::withHeaders($this->headers())
        ->get($this->baseUrl . '/cliente/buscar', [
            'dato' => $dato
        ])
        ->json();
}


}