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
        $this->apiUrl = "http://34.225.197.89:8080/carrito";
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

    // ✅ MEJORADO: Agregar producto con manejo de códigos de error
    public function agregarProducto(array $datosInput)
    {
        $headers = $this->obtenerHeaders();

        if (!$headers) {
            return ['success' => false, 'mensaje' => 'No autenticado'];
        }

        try {
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
            Log::info('Body respuesta: ' . $response->body());
            
            if ($response->successful()) {
                $data = $response->json();
                
                // ✅ Interpretar el resultado de Java
                if (isset($data['success'])) {
                    return $data;
                }
                
                // Si Java no devuelve 'success' pero devuelve un número (códigos de error)
                // Esto puede pasar si Java devuelve directamente el resultado del UPDATE/INSERT
                return $data;
            }

            Log::error('Error al agregar producto: ' . $response->body());
            return ['success' => false, 'mensaje' => 'Error al agregar al carrito'];

        } catch (\Exception $e) {
            Log::error('Excepción al agregar producto: ' . $e->getMessage());
            return ['success' => false, 'mensaje' => 'Error al agregar al carrito: ' . $e->getMessage()];
        }
    }

    // ✅ MEJORADO: Actualizar cantidad con códigos de error
    public function actualizarCantidad(int $idDetalleCarrito, int $cantidad)
    {
        $headers = $this->obtenerHeaders();

        if (!$headers) {
            return ['success' => false];
        }

        try {
            $response = Http::withHeaders($headers)
                ->put($this->apiUrl . "/actualizar", [
                    'id_carrito' => $idDetalleCarrito,
                    'cantidad' => $cantidad
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // ✅ Verificar si Java devuelve código de error
                if (isset($data['success'])) {
                    return $data;
                }
                
                // Si es un número, interpretarlo
                if (is_numeric($data)) {
                    if ($data == -1) {
                        return [
                            'success' => false,
                            'resultado' => -1,
                            'mensaje' => 'Stock insuficiente'
                        ];
                    } elseif ($data > 0) {
                        return ['success' => true];
                    }
                }
                
                return $data;
            }

            return ['success' => false];

        } catch (\Exception $e) {
            Log::error('Error al actualizar cantidad: ' . $e->getMessage());
            return ['success' => false];
        }
    }

    // 🗑️ Eliminar item
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