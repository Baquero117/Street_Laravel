<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PuntoInicio\CarritoService;

class CarritoController extends Controller
{
    private $carritoService;

    public function __construct(CarritoService $carritoService)
    {
        $this->carritoService = $carritoService;
    }

    public function obtenerCarrito($id_cliente)
    {
        $carrito = $this->carritoService->obtenerCarrito($id_cliente);
        return response()->json($carrito);
    }

    public function agregarProducto(Request $request)
    {
        $data = $request->all();
        $this->carritoService->agregarProducto($data);

        return response()->json(["mensaje" => "Producto agregado al carrito"]);
    }

    public function eliminarProducto($id_detalle)
    {
        $this->carritoService->eliminarProducto($id_detalle);
        return response()->json(["mensaje" => "Producto eliminado del carrito"]);
    }
}
