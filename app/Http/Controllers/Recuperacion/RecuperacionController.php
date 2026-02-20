<?php

namespace App\Http\Controllers\Recuperacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recuperacion\RecuperacionService;
use Illuminate\Support\Facades\Session;

class RecuperacionController extends Controller
{
    private $recuperacionService;

    public function __construct(RecuperacionService $recuperacionService)
    {
        $this->recuperacionService = $recuperacionService;
    }

    // Paso 1: Mostrar formulario para ingresar el correo
    public function mostrarSolicitud()
    {
        return view('recuperacion.solicitud');
    }

    // Paso 2: Procesar el correo y pedir a Spring Boot que envíe el email
    public function procesarSolicitud(Request $request)
    {
        $request->validate([
            'correo_electronico' => 'required|email|max:255'
        ]);

        $resultado = $this->recuperacionService->solicitarRecuperacion(
            $request->correo_electronico
        );

        // Siempre mostramos el mismo mensaje por seguridad
        Session::flash('mensaje', 'Si el correo existe, recibirás un enlace para restablecer tu contraseña.');
        return redirect()->route('recuperacion.solicitud');
    }

    // Paso 3: Mostrar formulario de nueva contraseña (llega desde el email)
    public function mostrarRestablecimiento(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            Session::flash('error', 'Token inválido o expirado.');
            return redirect()->route('login');
        }

        return view('recuperacion.restablecer', compact('token'));
    }

    // Paso 4: Procesar la nueva contraseña
    public function procesarRestablecimiento(Request $request)
    {
        $request->validate([
            'token'              => 'required|string',
            'contrasena'         => 'required|string|min:8|max:255',
            'contrasena_confirm' => 'required|same:contrasena'
        ]);

        $resultado = $this->recuperacionService->restablecerContrasena(
            $request->token,
            $request->contrasena
        );

        if (!$resultado['success']) {
            Session::flash('error', $resultado['error']);
            return redirect()->back()->withInput();
        }

        Session::flash('mensaje', 'Contraseña actualizada correctamente. Ya puedes iniciar sesión.');
        return redirect()->route('login');
    }
}