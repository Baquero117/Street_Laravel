<?php

namespace App\Http\Controllers\PuntoInicio;

use App\Http\Controllers\Controller;
use App\Models\PuntoInicio\PedidosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PedidosController extends Controller
{
    private $pedidosService;

    public function __construct(PedidosService $pedidosService)
    {
        $this->pedidosService = $pedidosService;
    }

    public function index()
    {
        if (!Session::has('token') || Session::get('usuario_tipo') !== 'cliente') {
            return redirect()->route('login');
        }

        $idCliente = Session::get('usuario_id');
        $token     = Session::get('token');

        $pedidos = $this->pedidosService->obtenerPedidosPorCliente($idCliente, $token);

        return view('PuntoInicio.Cliente.Pedidos', compact('pedidos'));
    }

    public function verFactura($id)
    {
        if (!Session::has('token')) {
            return redirect()->route('login');
        }

        $pdf = $this->pedidosService->obtenerFactura($id, Session::get('token'), false);

        if (!$pdf) {
            abort(404, 'Factura no encontrada');
        }

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline');
    }

    public function descargarFactura($id)
    {
        if (!Session::has('token')) {
            return redirect()->route('login');
        }

        $pdf = $this->pedidosService->obtenerFactura($id, Session::get('token'), true);

        if (!$pdf) {
            abort(404, 'Factura no encontrada');
        }

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="factura-' . $id . '.pdf"');
    }
}