<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrador\DetalleProductoService;
use Illuminate\Support\Facades\Session;

class DetalleProductoController extends Controller
{
    private $detalleProductoService;

    public function __construct(DetalleProductoService $detalleProductoService)
    {
        
        if (!session()->has('token')) {
            redirect()->route('login')->send();
        }

        $this->detalleProductoService = $detalleProductoService;
    }

    public function index()
    {
        $detalles = $this->detalleProductoService->obtenerDetalles();
        $mensaje = Session::get('mensaje', '');

        return view('Administrador.DetalleProducto', compact('detalles', 'mensaje'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'talla' => 'required|string|max:50',
            'id_producto' => 'required|numeric',
            'cantidad' => 'required|numeric|min:1',
        ]);

        $resultado = $this->detalleProductoService->agregarDetalle(
            $request->talla,
            $request->id_producto,
            $request->cantidad,
        );

        Session::flash('mensaje', $resultado['success']
            ? "Detalle agregado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('detalle.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_detalle_producto' => 'required|numeric',
            'talla' => 'required|string|max:50',
            'id_producto' => 'required|numeric',
            'cantidad' => 'required|numeric|min:1',
        ]);

        $id = $request->id_detalle_producto;

        $resultado = $this->detalleProductoService->actualizarDetalle(
            $id,
            $request->talla,
            $request->id_producto,
            $request->cantidad
        );

        Session::flash('mensaje', $resultado['success']
            ? "Detalle actualizado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('detalle.index');
    }

    public function destroy(Request $request)
    {
        $id = $request->id_detalle_producto;

        $resultado = $this->detalleProductoService->eliminarDetalle($id);

        Session::flash('mensaje', $resultado['success']
            ? "Detalle eliminado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('detalle.index');
    }

    public function buscar(Request $request)
    {
        $detalles = $this->detalleProductoService
                         ->buscarDetalleProducto($request->id_producto);

        return response()->json($detalles);
    }
}