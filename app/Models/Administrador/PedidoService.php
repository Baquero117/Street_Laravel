<?php

namespace App\Models\Administrador;

use Illuminate\Support\Facades\Http;

class PedidoService
{
    private $baseUrl = "http://localhost:8080/pedido";
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

 
/* ===================== OBTENER PEDIDOS ===================== */
    public function obtenerPedidos()
    {
        $response = Http::withHeaders($this->headers())
            ->get($this->baseUrl);

        if ($response->successful()) {
            $data = $response->json();

            if (!is_array($data)) {
                $data = [];
            }

            if (isset($data['id_pedido'])) {
                $data = [$data];
            }

            return [
                'success' => true,
                'data' => $data
            ];
        }

        return [
            'success' => false,
            'data' => [],
            'error' => 'HTTP ' . $response->status()
        ];
    }

    /* ===================== CREAR PEDIDO ===================== */
    public function crearPedido($id_cliente, $fecha_pedido, $total, $estado)
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl, [
                'id_cliente'   => $id_cliente,
                'fecha_pedido' => $fecha_pedido,
                'total'        => $total,
                'estado'       => $estado
            ]);

        if ($response->successful()) {
            return ['success' => true];
        }

        return [
            'success' => false,
            'error' => 'HTTP ' . $response->status()
        ];
    }

    /* ===================== ACTUALIZAR PEDIDO (PARCIAL) ===================== */
    public function actualizarPedidoParcial($id, $fecha_pedido, $total)
    {
        $response = Http::withHeaders($this->headers())
            ->put($this->baseUrl . '/' . $id, [
                'fecha_pedido' => $fecha_pedido,
                'total'        => $total
            ]);

        if ($response->successful()) {
            return ['success' => true];
        }

        return [
            'success' => false,
            'error' => 'HTTP ' . $response->status()
        ];
    }

    /* ===================== CAMBIAR ESTADO ===================== */
    public function actualizarPedidoEstado($id, $estado)
    {
        $response = Http::withHeaders($this->headers())
            ->put($this->baseUrl . '/' . $id, [
                'estado' => $estado
            ]);

        if ($response->successful()) {
            return ['success' => true];
        }

        return [
            'success' => false,
            'error' => 'HTTP ' . $response->status()
        ];
    }

    /* ===================== ELIMINAR (YA NO SE USA) ===================== */
    // ❌ Eliminado intencionalmente
}
