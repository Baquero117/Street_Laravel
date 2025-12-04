<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrador\VendedorService;
use Illuminate\Support\Facades\Session;

class VendedorController extends Controller
{
    private $vendedorService;

    public function __construct(VendedorService $vendedorService)
    {
        // 🔥 Protección sin middleware
        if (!session()->has('token')) {
            redirect()->route('login')->send();
        }

        $this->vendedorService = $vendedorService;
    }

    public function index()
    {
        $vendedores = $this->vendedorService->obtenerVendedores();
        $mensaje = Session::get('mensaje', '');

        return view('Administrador.Vendedor', compact('vendedores', 'mensaje'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo_electronico' => 'required|email|max:255',
            'contrasena' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
        ]);

        $resultado = $this->vendedorService->agregarVendedor(
            $request->nombre,
            $request->apellido,
            $request->correo_electronico,
            $request->contrasena,
            $request->telefono
        );

        Session::flash('mensaje', $resultado['success']
            ? "Vendedor agregado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('vendedor.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_vendedor' => 'required|numeric',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo_electronico' => 'required|email|max:255',
            'contrasena' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            
        ]);

        $id = $request->id_vendedor;

        $resultado = $this->vendedorService->actualizarVendedor(
            $id,
            $request->nombre,
            $request->apellido,
            $request->contrasena,
            $request->correo_electronico,
            $request->telefono
        );

        Session::flash('mensaje', $resultado['success']
            ? "Vendedor actualizado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('vendedor.index');
    }

    public function destroy(Request $request)
    {
        $id = $request->id_vendedor;

        $resultado = $this->vendedorService->eliminarVendedor($id);

        Session::flash('mensaje', $resultado['success']
            ? "Vendedor eliminado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('vendedor.index');
    }
}
