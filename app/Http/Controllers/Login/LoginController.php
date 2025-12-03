<?php

namespace App\Http\Controllers\Login;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\Login\LoginService;


class LoginController extends Controller
{
    private $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    // Mostrar formulario de login
    public function mostrar()
    {
        return view('login.login'); // resources/views/login/login.blade.php
    }

    // Procesar formulario
    public function procesar(Request $request)
    {
        // Validación
        $request->validate([
            'correo_electronico' => 'required|email',
            'contrasena' => 'required'
        ]);

        $correo = $request->input('correo_electronico');
        $contrasena = $request->input('contrasena');

        // Llamar al servicio
        $resultado = $this->loginService->autenticar($correo, $contrasena);

        if ($resultado) {
            // Guardar sesión
            Session::put('token', $resultado['token']);
            Session::put('usuario_id', $resultado['datos']['id_' . ($resultado['tipo'] === 'cliente' ? 'cliente' : 'vendedor')]);
            Session::put('usuario_nombre', $resultado['datos']['nombre']);
            Session::put('usuario_tipo', $resultado['tipo']);
            Session::put('usuario_correo', $resultado['datos']['correo_electronico']);

            // Redirecciones según tipo
            if ($resultado['tipo'] === 'administrador') {
                return redirect()->route('admin.inicio'); 
            }

            return redirect()->route('home');
        }

        // Credenciales incorrectas
        return redirect()->route('login')
            ->with('error', 'Credenciales inválidas');
    }

    // Cerrar sesión
    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}
