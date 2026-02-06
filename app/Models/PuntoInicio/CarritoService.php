<?php

namespace App\Models\Carrito;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CarritoService
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = "http://localhost:8080/carrito";
    }

    private function obtenerToken()
    {
        return Session::get('token');
    }

    public function obtenerCarrito()
    {
        $token = $this->obtenerToken();

        if (!$token) {
            return ['items' => [], 'total' => 0, 'cantidad_items' => 0];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($this->apiUrl . "/mis-productos");

        if ($response->successful()) {
            return $response->json();
        }

        return ['items' => [], 'total' => 0, 'cantidad_items' => 0];
    }

    public function agregarProducto(int $idProducto, string $talla, int $cantidad, float $precio)
    {
        $token = $this->obtenerToken();

        if (!$token) {
            return ['success' => false, 'mensaje' => 'No autenticado'];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->post($this->apiUrl . "/agregar", [
            'id_producto' => $idProducto,
            'talla' => $talla,
            'cantidad' => $cantidad,
            'precio' => $precio
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return ['success' => false, 'mensaje' => 'Error al agregar al carrito'];
    }

    public function actualizarCantidad(int $idCarrito, int $cantidad)
    {
        $token = $this->obtenerToken();

        if (!$token) {
            return ['success' => false];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->put($this->apiUrl . "/actualizar", [
            'id_carrito' => $idCarrito,
            'cantidad' => $cantidad
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return ['success' => false];
    }

    public function eliminarItem(int $idCarrito)
    {
        $token = $this->obtenerToken();

        if (!$token) {
            return ['success' => false];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->delete($this->apiUrl . "/eliminar/{$idCarrito}");

        if ($response->successful()) {
            return $response->json();
        }

        return ['success' => false];
    }

    public function obtenerContador()
    {
        $token = $this->obtenerToken();

        if (!$token) {
            return ['cantidad' => 0];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($this->apiUrl . "/contador");

        if ($response->successful()) {
            return $response->json();
        }

        return ['cantidad' => 0];
    }

    public function vaciarCarrito()
    {
        $token = $this->obtenerToken();

        if (!$token) {
            return ['success' => false];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->delete($this->apiUrl . "/vaciar");

        if ($response->successful()) {
            return $response->json();
        }

        return ['success' => false];
    }
}