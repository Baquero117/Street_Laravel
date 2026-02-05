<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PublicoController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_JAVA_URL', 'http://localhost:8080');
    }

    public function index()
    {
        try {
            $response = Http::get("{$this->apiUrl}/producto");
            
            if ($response->successful()) {
                $productos = $response->json();
            } else {
                $productos = [];
            }
            
        } catch (\Exception $e) {
            $productos = [];
            Log::error('Error al obtener productos: ' . $e->getMessage());
        }

        return view('PuntoInicio.Inicio', compact('productos'));
    }

    public function detalle($id)
    {
        try {
            $response = Http::get("{$this->apiUrl}/producto/{$id}/detalle");
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Producto no encontrado'], 404);
            
        } catch (\Exception $e) {
            Log::error('Error al obtener detalle: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener detalle del producto'], 500);
        }
    }
}