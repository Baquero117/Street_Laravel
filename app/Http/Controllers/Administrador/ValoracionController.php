<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrador\ValoracionService;
use Illuminate\Support\Facades\Session;

class ValoracionController extends Controller
{
    private $valoracionService;

    public function __construct(ValoracionService $valoracionService)
    {
       
        if (!session()->has('token')) {
            redirect()->route('login')->send();
        }

        $this->valoracionService = $valoracionService;
    }

    
    public function index()
    {
        $resultado = $this->valoracionService->obtenerValoraciones();

        $valoraciones = $resultado['success'] ? $resultado['data'] : [];
        $mensaje = Session::get('mensaje', '');

        return view('Administrador.Valoracion', compact('valoraciones', 'mensaje'));
    }

    
    public function destroy(Request $request)
    {
        $request->validate([
            'id_valoracion' => 'required|numeric'
        ]);

        $id = $request->id_valoracion;

        $resultado = $this->valoracionService->eliminarValoracion($id);

        Session::flash('mensaje', $resultado['success']
            ? "Valoración eliminada correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('valoracion.index');
    }
}
