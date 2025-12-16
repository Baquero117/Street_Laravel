<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrador\CategoriaService;
use Illuminate\Support\Facades\Session;

class CategoriaController extends Controller
{
    private $categoriaService;

    public function __construct(CategoriaService $categoriaService)
    {
        
        if (!session()->has('token')) {
            redirect()->route('login')->send();
        }

        $this->categoriaService = $categoriaService;
    }

    public function index()
    {
        $categorias = $this->categoriaService->obtenerCategorias();
        $mensaje = Session::get('mensaje', '');

        return view('administrador.categoria', compact('categorias', 'mensaje'));
         
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $resultado = $this->categoriaService->agregarCategoria($request->nombre);

        Session::flash('mensaje', $resultado['success']
            ? "Categoría agregada correctamente"
            : "Error: " . $resultado['error']
        );

        return redirect()->route('categoria.index');
    }

    public function update(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255'
    ]);

    $id = $request->id_categoria; 

    $resultado = $this->categoriaService->actualizarCategoria($id, $request->nombre);

    Session::flash('mensaje', $resultado['success']
        ? "Categoría actualizada correctamente"
        : "Error: " . $resultado['error']
    );

    return redirect()->route('categoria.index');
}


  public function destroy(Request $request)
{
    $id = $request->id_categoria;

    $resultado = $this->categoriaService->eliminarCategoria($id);

    Session::flash('mensaje', $resultado['success']
        ? "Categoría eliminada correctamente"
        : "Error: " . $resultado['error']
    );

    return redirect()->route('categoria.index');
}

}
