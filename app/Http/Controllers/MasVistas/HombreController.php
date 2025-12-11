<?php

namespace App\Http\Controllers\MasVistas;

use App\Http\Controllers\Controller;

class HombreController extends Controller
{
    public function index()
    {
        return view('Vistas.hombre');
    }
}
