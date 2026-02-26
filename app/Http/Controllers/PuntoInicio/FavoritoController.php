<?php

namespace App\Http\Controllers\PuntoInicio;

use App\Http\Controllers\Controller;
use App\Models\PuntoInicio\FavoritoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FavoritoController extends Controller
{
    private $favoritoService;

    public function __construct(FavoritoService $favoritoService)
    {
        $this->favoritoService = $favoritoService;
    }

    /**
     * Vista principal de favoritos del cliente
     */
    public function index()
    {
        if (!Session::has('token') || Session::get('usuario_tipo') !== 'cliente') {
            return redirect()->route('login');
        }

        $idCliente = Session::get('usuario_id');
        $token     = Session::get('token');

        $favoritos = $this->favoritoService->obtenerFavoritos($idCliente, $token);

        return view('PuntoInicio.Cliente.Favorito', compact('favoritos'));
    }

    /**
     * Agregar producto a favoritos (AJAX)
     */
    public function agregar(Request $request)
    {
        if (!Session::has('token') || Session::get('usuario_tipo') !== 'cliente') {
            return response()->json(['ok' => false, 'mensaje' => 'No autenticado.'], 401);
        }

        $idCliente  = Session::get('usuario_id');
        $token      = Session::get('token');
        $idProducto = $request->input('id_producto');

        if (!$idProducto) {
            return response()->json(['ok' => false, 'mensaje' => 'Producto no especificado.'], 400);
        }

        $resultado = $this->favoritoService->agregarFavorito($idCliente, $idProducto, $token);

        return response()->json($resultado);
    }

    /**
     * Eliminar favorito por ID (AJAX)
     */
    public function eliminar(int $idFavorito)
    {
        if (!Session::has('token') || Session::get('usuario_tipo') !== 'cliente') {
            return response()->json(['ok' => false, 'mensaje' => 'No autenticado.'], 401);
        }

        $token     = Session::get('token');
        $resultado = $this->favoritoService->eliminarFavorito($idFavorito, $token);

        return response()->json($resultado);
    }

    /**
     * Verificar si un producto ya es favorito (AJAX)
     */
    public function verificar(int $idProducto)
    {
        if (!Session::has('token') || Session::get('usuario_tipo') !== 'cliente') {
            return response()->json(['esFavorito' => false]);
        }

        $idCliente  = Session::get('usuario_id');
        $token      = Session::get('token');
        $esFavorito = $this->favoritoService->esFavorito($idCliente, $idProducto, $token);

        return response()->json(['esFavorito' => $esFavorito]);
    }
}