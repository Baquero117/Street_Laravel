<?php

namespace App\Http\Controllers\Carrito;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Carrito\CarritoService;

class CarritoController extends Controller
{
    private $carritoService;

    public function __construct(CarritoService $carritoService)
    {
        $this->carritoService = $carritoService;
    }

    // Mostrar vista del carrito
    public function index()
    {
        if (!Session::has('token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tu carrito');
        }

        $carrito = $this->carritoService->obtenerCarrito();

        return view('Carrito.Carrito', compact('carrito'));
    }

    // Agregar producto al carrito
    public function agregar(Request $request)
    {
        if (!Session::has('token')) {
            return response()->json([
                'success' => false, 
                'mensaje' => 'Debes iniciar sesión',
                'redirect' => route('login')
            ], 401);
        }

        $resultado = $this->carritoService->agregarProducto(
            $request->id_producto,
            $request->talla,
            $request->cantidad ?? 1,
            $request->precio
        );

        return response()->json($resultado);
    }

    // Eliminar item del carrito
    public function eliminar($idCarrito)
    {
        if (!Session::has('token')) {
            return response()->json(['success' => false], 401);
        }

        $resultado = $this->carritoService->eliminarItem($idCarrito);

        return response()->json($resultado);
    }

    // Actualizar cantidad
    public function actualizar(Request $request)
    {
        if (!Session::has('token')) {
            return response()->json(['success' => false], 401);
        }

        $resultado = $this->carritoService->actualizarCantidad(
            $request->id_carrito,
            $request->cantidad
        );

        return response()->json($resultado);
    }

    // Obtener contador
    public function contador()
    {
        if (!Session::has('token')) {
            return response()->json(['cantidad' => 0]);
        }

        $resultado = $this->carritoService->obtenerContador();

        return response()->json($resultado);
    }

    // Vaciar carrito
    public function vaciar()
    {
        if (!Session::has('token')) {
            return response()->json(['success' => false], 401);
        }

        $resultado = $this->carritoService->vaciarCarrito();

        return response()->json($resultado);
    }
}