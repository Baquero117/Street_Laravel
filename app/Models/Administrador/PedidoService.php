<?php

namespace App\Models\Administrador;

use Illuminate\Support\Facades\Http;

class PedidoService
{
    private $baseUrl = "http://localhost:8080/pedido";
    private $token;

    public function __construct()
    {
        // Token almacenado en la sesión
        $this->token = session('token');
    }

    private function headers()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type'  => 'application/json'
        ];
    }

    public function obtenerPedidos()
{
    $response = Http::withHeaders($this->headers())
        ->get($this->baseUrl);

    if ($response->successful()) {

        $data = $response->json();

        // Asegurar que siempre sea un array de pedidos
        if (!is_array($data)) {
            $data = [];
        }

        // Si es un objeto único, convertirlo a array dentro de otro array
        if (isset($data["id_pedido"])) {
            $data = [$data];
        }

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


    public function crearPedido($id_cliente, $fecha_pedido, $total, $estado)
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl, [
                "id_cliente"    => $id_cliente,
                "fecha_pedido"  => $fecha_pedido,
                "total"         => $total,
                "estado"        => $estado
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

    public function actualizarPedido($id, $id_cliente, $fecha_pedido, $total, $estado)
    {
        $response = Http::withHeaders($this->headers())
            ->put($this->baseUrl . "/" . $id, [
                "id_cliente"    => $id_cliente,
                "fecha_pedido"  => $fecha_pedido,
                "total"         => $total,
                "estado"        => $estado
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

    public function eliminarPedido($id)
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
