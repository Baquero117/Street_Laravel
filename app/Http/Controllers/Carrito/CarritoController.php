<?php

namespace App\Http\Controllers\Carrito;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index()
    {
        // Por ahora metemos datos falsos para que la vista no explote
        $carrito = [];
        $subtotal = 0;
        $total = 0;
        $sugeridos = [];

        return view('CarritoCompras.carrito', compact('carrito', 'subtotal', 'total', 'sugeridos'));
    }
}
