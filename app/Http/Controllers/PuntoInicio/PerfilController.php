<?php

namespace App\Http\Controllers\PuntoInicio;

use App\Http\Controllers\Controller;
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
        // Validación de sesión
        if (!Session::has('usuario_id')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        // Obtener datos del perfil
        $perfil = $this->perfilService->obtenerPerfil();

        if (!$perfil) {
            return redirect()->route('inicio')->with('error', 'No se pudo obtener la información del perfil.');
        }

        return view('PuntoInicio.perfil', [
            'perfil' => $perfil
        ]);
    }
}
