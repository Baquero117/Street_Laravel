<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrador\DetalleProductoService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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
            'id_categoria' => 'required|numeric',
            'imagen' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cantidad' => 'required|numeric|min:1',
        ]);

       
        $rutaImagen = $request->file('imagen')->store('detalles', 'public');

        $resultado = $this->detalleProductoService->agregarDetalle(
            $request->talla,
            $rutaImagen,
            $request->id_producto,
            $request->id_categoria,
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
            'id_categoria' => 'required|numeric',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cantidad' => 'required|numeric|min:1',
        ]);

        $id = $request->id_detalle_producto;

        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('detalles', 'public');
        }

        $resultado = $this->detalleProductoService->actualizarDetalle(
            $id,
            $request->talla,
            $rutaImagen,
            $request->id_producto,
            $request->id_categoria,
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
}
