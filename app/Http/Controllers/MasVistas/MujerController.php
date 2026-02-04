<?php

namespace App\Http\Controllers\MasVistas;

use App\Http\Controllers\Controller;

class MujerController extends Controller
{
    public function index()
    {
        return view('Vistas.Mujer');
    }
}
