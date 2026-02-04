<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrador\ProductoService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage; 

class ProductoController extends Controller
{
    private $productoService;

    public function __construct(ProductoService $productoService)
    {
        
        if (!session()->has('token')) {
            redirect()->route('login')->send();
        }

        $this->productoService = $productoService;
    }

   
    public function index()
    {
        $productos = $this->productoService->obtenerProductos();
        $mensaje = Session::get('mensaje', '');

        return view('Administrador.Producto', compact('productos', 'mensaje'));
    }

  
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'required|string',
            'cantidad' => 'required|numeric',
            'id_vendedor' => 'required|numeric',
            'estado' => 'required|string|max:30',
            'imagen' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'precio' => 'required|numeric|min:0',
            'color' => 'nullable|string|max:50',
        ]);

       
        $rutaImagen = $request->file('imagen')->store('productos', 'public');

        $resultado = $this->productoService->agregarProducto(
            $request->nombre,
            $request->descripcion,
            $request->cantidad,
            $rutaImagen,
            $request->id_vendedor,
            $request->estado,
            $request->precio,
            $request->color
        );

        Session::flash('mensaje', $resultado['success']
            ? "Producto agregado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('producto.index');
    }

    
    public function update(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|numeric',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'required|string',
            'cantidad' => 'required|numeric',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'id_vendedor' => 'required|numeric',
            'estado' => 'required|string|max:30',
            'precio' => 'required|numeric|min:0',
            'color' => 'nullable|string|max:50',
        ]);

        $id = $request->id_producto;

        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('productos', 'public');
        }

        $resultado = $this->productoService->actualizarProducto(
            $id,
            $request->nombre,
            $request->descripcion,
            $request->cantidad,
            $rutaImagen,
            $request->id_vendedor,
            $request->estado,
            $request->precio,
            $request->color
        );

        Session::flash('mensaje', $resultado['success']
            ? "Producto actualizado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('producto.index');
    }

   
    public function destroy(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|numeric'
        ]);

        $id = $request->id_producto;

        $resultado = $this->productoService->eliminarProducto($id);

        Session::flash('mensaje', $resultado['success']
            ? "Producto eliminado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('producto.index');
    }
}
