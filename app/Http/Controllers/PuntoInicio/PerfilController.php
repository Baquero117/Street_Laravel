<?php

namespace App\Http\Controllers\PuntoInicio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\PuntoInicio\PerfilService;

class PerfilController extends Controller
{
    private $perfilService;

    public function __construct(PerfilService $perfilService)
    {
        $this->perfilService = $perfilService;
    }

    // Mostrar página de perfil
    public function mostrar()
    {

        if (!Session::has('token')) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        $perfil = $this->perfilService->obtenerPerfil();

        if (!$perfil) {
            return redirect()->route('inicio')->with('error', 'No se pudo obtener la información del perfil.');
        }

        return view('PuntoInicio.Cliente.Perfil', [
            'perfil' => $perfil
        ]);

        
    }

    public function mostrarCuenta()
{
    if (!Session::has('token')) {
        return redirect()->route('login');
    }

    $perfil = $this->perfilService->obtenerPerfil();

    if (!$perfil) {
        return redirect()->route('inicio');
    }

    return view('PuntoInicio.Cliente.Cuenta', [
        'perfil' => $perfil
    ]);
}


       public function actualizar(Request $request)
{
    if (!Session::has('token')) {
        return redirect()->route('login');
    }

    $request->validate([
        'nombre' => 'required|string|max:100',
        'apellido' => 'required|string|max:100',
        'correo_electronico' => 'required|email|max:150',
        'telefono' => 'required|string|max:20',
        'direccion' => 'required|string|max:255',
        'contrasena' => 'nullable|string|min:6|confirmed'
    ]);

    $resultado = $this->perfilService->actualizarPerfil(
        $request->nombre,
        $request->apellido,
        $request->contrasena,
        $request->direccion,
        $request->telefono,
        $request->correo_electronico
    );

    if ($resultado['success']) {
        Session::put('usuario_nombre', $request->nombre);
        return redirect()->back()->with('success', 'Datos actualizados correctamente.');
    }

    return redirect()->back()->with('error', 'No se pudieron actualizar los datos');
}


}