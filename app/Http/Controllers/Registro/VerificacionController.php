<?php

namespace App\Http\Controllers\Registro; // ✅ Está en Controllers\Registro

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registro\VerificacionService; // ✅ Está en Models\Registro
use Illuminate\Support\Facades\Session;

class VerificacionController extends Controller
{
    private $verificacionService;

    public function __construct(VerificacionService $verificacionService)
    {
        $this->verificacionService = $verificacionService;
    }

    public function mostrar()
    {
        $correo = Session::get('correo_verificacion');

        if (!$correo) {
            return redirect()->route('registro');
        }

        return view('Registro.Verificacion', compact('correo')); // ✅ views/Registro/Verificacion.blade.php
    }

    public function validar(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|size:6'
        ]);

        $correo = Session::get('correo_verificacion');

        if (!$correo) {
            return redirect()->route('registro');
        }

        $resultado = $this->verificacionService->validarCodigo($correo, $request->codigo);

        if (!$resultado['success']) {
            Session::flash('error', $resultado['error']);
            return redirect()->route('verificacion.mostrar');
        }

        Session::forget('correo_verificacion');
        Session::flash('mensaje', '¡Cuenta verificada correctamente! Ya puedes iniciar sesión.');
        return redirect()->route('login');
    }

    public function reenviar()
    {
        $correo = Session::get('correo_verificacion');

        if (!$correo) {
            return redirect()->route('registro');
        }

        $resultado = $this->verificacionService->reenviarCodigo($correo);

        if (!$resultado['success']) {
            Session::flash('error', $resultado['error']);
        } else {
            Session::flash('mensaje', 'Código reenviado correctamente. Revisa tu correo.');
        }

        return redirect()->route('verificacion.mostrar');
    }
}