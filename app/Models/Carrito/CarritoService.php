<?php

namespace App\Models\Carrito;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

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

    private function obtenerHeaders()
    {
        $token = $this->obtenerToken();
        
        if (!$token) {
            return null;
        }

        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
    }

    // 🛒 Obtener carrito completo del usuario autenticado
    public function obtenerCarrito()
    {
        $headers = $this->obtenerHeaders();

        if (!$headers) {
            Log::warning('No hay token de sesión');
            return ['items' => [], 'total' => 0, 'cantidad_items' => 0];
        }

        try {
            $response = Http::withHeaders($headers)
                ->get($this->apiUrl . "/mis-productos");

            Log::info('Respuesta obtener carrito: ' . $response->status());

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Datos del carrito: ', $data);
                return $data;
            }

            Log::error('Error al obtener carrito: ' . $response->body());
            return ['items' => [], 'total' => 0, 'cantidad_items' => 0];

        } catch (\Exception $e) {
            Log::error('Excepción al obtener carrito: ' . $e->getMessage());
            return ['items' => [], 'total' => 0, 'cantidad_items' => 0];
        }
    }

    // ➕ Agregar producto al carrito - AHORA RECIBE UN ARRAY
    public function agregarProducto(array $datosInput)
    {
        $headers = $this->obtenerHeaders();

        if (!$headers) {
            return ['success' => false, 'mensaje' => 'No autenticado'];
        }

        try {
            // Mapeamos los datos al formato que Java espera exactamente
            $datosParaJava = [
                'id_detalle_producto' => (int)$datosInput['id_detalle_producto'], 
                'talla'               => $datosInput['talla'],
                'cantidad'            => (int)$datosInput['cantidad'],
                'precio'              => (float)$datosInput['precio']
            ];

            Log::info('Enviando datos a Java: ', $datosParaJava);

            $response = Http::withHeaders($headers)
                ->post($this->apiUrl . "/agregar", $datosParaJava);

            Log::info('Respuesta agregar producto: ' . $response->status());
            
            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Error al agregar producto: ' . $response->body());
            return ['success' => false, 'mensaje' => 'Error al agregar al carrito'];

        } catch (\Exception $e) {
            Log::error('Excepción al agregar producto: ' . $e->getMessage());
            return ['success' => false, 'mensaje' => 'Error al agregar al carrito: ' . $e->getMessage()];
        }
    }

    // 🔄 Actualizar cantidad (ahora usa id_detalle_carrito)
    public function actualizarCantidad(int $idDetalleCarrito, int $cantidad)
    {
        $headers = $this->obtenerHeaders();

        if (!$headers) {
            return ['success' => false];
        }

        try {
            $response = Http::withHeaders($headers)
                ->put($this->apiUrl . "/actualizar", [
                    'id_carrito' => $idDetalleCarrito, // Java lo espera como id_carrito
                    'cantidad' => $cantidad
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return ['success' => false];

        } catch (\Exception $e) {
            Log::error('Error al actualizar cantidad: ' . $e->getMessage());
            return ['success' => false];
        }
    }

    // 🗑️ Eliminar item (usa id_detalle_carrito)
    public function eliminarItem(int $idDetalleCarrito)
    {
        $headers = $this->obtenerHeaders();

        if (!$headers) {
            return ['success' => false];
        }

        try {
            $response = Http::withHeaders($headers)
                ->delete($this->apiUrl . "/eliminar/{$idDetalleCarrito}");

            if ($response->successful()) {
                return $response->json();
            }

            return ['success' => false];

        } catch (\Exception $e) {
            Log::error('Error al eliminar item: ' . $e->getMessage());
            return ['success' => false];
        }
    }

    // 🔢 Obtener contador de items
    public function obtenerContador()
    {
        $headers = $this->obtenerHeaders();

        if (!$headers) {
            return ['cantidad' => 0];
        }

        try {
            $response = Http::withHeaders($headers)
                ->get($this->apiUrl . "/contador");

            if ($response->successful()) {
                return $response->json();
            }

            return ['cantidad' => 0];

        } catch (\Exception $e) {
            Log::error('Error al obtener contador: ' . $e->getMessage());
            return ['cantidad' => 0];
        }
    }

    // 🗑️ Vaciar carrito completo
    public function vaciarCarrito()
    {
        $headers = $this->obtenerHeaders();

        if (!$headers) {
            return ['success' => false];
        }

        try {
            $response = Http::withHeaders($headers)
                ->delete($this->apiUrl . "/vaciar");

            if ($response->successful()) {
                return $response->json();
            }

            return ['success' => false];

        } catch (\Exception $e) {
            Log::error('Error al vaciar carrito: ' . $e->getMessage());
            return ['success' => false];
        }
    }
}