<?php

namespace App\Http\Controllers\Registro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registro\RegistroService;
use Illuminate\Support\Facades\Session;

class RegistroController extends Controller
{
    private $registroService;

    public function __construct(RegistroService $registroService)
    {
        $this->registroService = $registroService;
    }

    public function mostrar()
    {
        return view('registro.registro');
    }

    public function procesar(Request $request)
    {
        $request->validate([
            'nombre'             => 'required|string|max:255',
            'apellido'           => 'required|string|max:255',
            'departamento'       => 'required|string|max:100',
            'municipio'          => 'required|string|max:100',
            'direccion'          => 'required|string|max:255',
            'telefono'           => 'required|string|max:20',
            'correo_electronico' => 'required|email|max:255',
            'contrasena'         => 'required|string|max:255'
        ]);

        $resultado = $this->registroService->registrarUsuario(
            $request->nombre,
            $request->apellido,
            $request->departamento,
            $request->municipio,
            $request->direccion,
            $request->telefono,
            $request->correo_electronico,
            $request->contrasena
        );

        if (!$resultado['success']) {
            Session::flash('error', $resultado['error']);
            return redirect()->route('registro');
        }

        Session::put('correo_verificacion', $request->correo_electronico);
        Session::flash('mensaje', 'Cuenta creada. Revisa tu correo e ingresa el código de verificación.');
        return redirect()->route('verificacion.mostrar');
    }
}