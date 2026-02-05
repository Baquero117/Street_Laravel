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

    // ============================
    // 🔹 LISTAR PRODUCTOS
    // ============================
    public function index()
    {
        $productos = $this->productoService->obtenerProductos();
        $mensaje = Session::get('mensaje', '');

        return view('Administrador.Producto', compact('productos', 'mensaje'));
    }

    // ============================
    // 🔹 CREAR PRODUCTO
    // ============================
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

        // Guardar imagen
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
            ? 'Producto agregado correctamente.'
            : 'Error: ' . $resultado['error']
        );

        return redirect()->route('producto.index');
    }

    // ============================
    // 🔹 ACTUALIZAR PRODUCTO
    // ============================
   public function update(Request $request)
{
    $request->validate([
        'id_producto' => 'required|numeric',
        'nombre' => 'required|string|max:150',
        'descripcion' => 'required|string',
        'cantidad' => 'required|numeric',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'imagen_actual' => 'nullable|string',
        'id_vendedor' => 'required|numeric',
        'estado' => 'required|string|max:30',
        'precio' => 'required|numeric|min:0',
        'color' => 'nullable|string|max:50',
    ]);

    $id = $request->id_producto;

    // 🔹 Por defecto conservar imagen actual
    $rutaImagen = $request->imagen_actual;

    // 🔹 Si se sube una nueva imagen
    if ($request->hasFile('imagen')) {

        // Eliminar imagen anterior
        if (
            !empty($request->imagen_actual) &&
            Storage::disk('public')->exists($request->imagen_actual)
        ) {
            Storage::disk('public')->delete($request->imagen_actual);
        }

        // Guardar nueva imagen
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
        ? 'Producto actualizado correctamente.'
        : 'Error: ' . $resultado['error']
    );

    return redirect()->route('producto.index');
}


    // ============================
    // 🔹 ELIMINAR PRODUCTO
    // ============================
    public function destroy(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|numeric'
        ]);

        $id = $request->id_producto;

        // 🔹 Obtener producto para borrar imagen
        $productoActual = $this->productoService->obtenerProductoPorId($id);

        if (
            !empty($productoActual['imagen']) &&
            Storage::disk('public')->exists($productoActual['imagen'])
        ) {
            Storage::disk('public')->delete($productoActual['imagen']);
        }

        $resultado = $this->productoService->eliminarProducto($id);

        Session::flash('mensaje', $resultado['success']
            ? 'Producto eliminado correctamente.'
            : 'Error: ' . $resultado['error']
        );

        return redirect()->route('producto.index');
    }
}
