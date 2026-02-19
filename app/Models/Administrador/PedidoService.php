<?php

namespace App\Models\Administrador;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


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
            'Accept'        => 'application/json'
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
                'data'    => $data
            ];
        }

        return [
            'success' => false,
            'data'    => [],
            'error'   => 'HTTP ' . $response->status()
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

        return $response->successful()
            ? ['success' => true]
            : ['success' => false, 'error' => 'HTTP ' . $response->status()];
    }

    /* ===================== ACTUALIZAR PEDIDO ===================== */
    public function actualizarPedidoParcial($id, $fecha_pedido, $total)
    {
        $response = Http::withHeaders($this->headers())
            ->put($this->baseUrl . '/' . $id, [
                'fecha_pedido' => $fecha_pedido,
                'total'        => $total
            ]);

        return $response->successful()
            ? ['success' => true]
            : ['success' => false, 'error' => 'HTTP ' . $response->status()];
    }

    /* ===================== CAMBIAR ESTADO ===================== */
    public function actualizarPedidoEstado($id, $estado)
    {
        $response = Http::withHeaders($this->headers())
            ->put($this->baseUrl . '/' . $id, [
                'estado' => $estado
            ]);

        return $response->successful()
            ? ['success' => true]
            : ['success' => false, 'error' => 'HTTP ' . $response->status()];
    }

    /* ===================== OBTENER FACTURA (PDF) ===================== */
public function obtenerFactura($id)
{
    $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])
        ->accept('application/pdf')
        ->get($this->baseUrl . '/' . $id . '/factura/ver');

    Log::info('Factura status: ' . $response->status());
    Log::info('Factura content-type: ' . $response->header('Content-Type'));
    Log::info('Factura body length: ' . strlen($response->body()));
    if ($response->successful() && strlen($response->body()) > 0) {
        return [
            'success' => true,
            'data'    => $response->body()
        ];
    }

    return [
        'success' => false,
        'error'   => 'HTTP ' . $response->status() . ' | Body vacío: ' . (strlen($response->body()) === 0 ? 'SÍ' : 'NO')
    ];
}
}
