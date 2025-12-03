<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AdminBaseController extends Controller
{
    /**
     * Verifica si existe un token válido en la sesión.
     * Si no existe o está vacío, redirige a login.
     */
    protected function verificarToken()
    {
        $token = session('token');

        // 1. Token inexistente o vacío → logout inmediato
        if (!$token || strlen(trim($token)) === 0) {
            Session::flush();
            return redirect()->route('login');
        }

        return null;
    }
}
