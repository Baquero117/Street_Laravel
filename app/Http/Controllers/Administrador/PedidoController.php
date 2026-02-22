<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrador\PedidoService;
use App\Models\Administrador\ClienteService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    private $pedidoService;
    private $clienteService;

    public function __construct(
        PedidoService $pedidoService,
        ClienteService $clienteService
    ) {
        if (!session()->has('token')) {
            redirect()->route('login')->send();
        }

        $this->pedidoService = $pedidoService;
        $this->clienteService = $clienteService;
    }

    /* ===================== LISTAR PEDIDOS ===================== */
   public function index()
{
    $result = $this->pedidoService->obtenerPedidos();
    $clientesResult = $this->clienteService->obtenerClientes(); // 👈 FALTA ESTO

    $pedidos = [];
    $mensaje = '';
    $clientes = [];

    if ($clientesResult['success']) {
        $clientes = $clientesResult['data'];
    }

    if ($result['success']) {
        foreach ($result['data'] as $pedido) {

            $cliente = $this->clienteService
                ->obtenerClientePorId($pedido['id_cliente']);

            if ($cliente['success']) {
                unset($cliente['data']['contrasena']);
                $pedido['cliente'] = $cliente['data'];
            }

            $pedidos[] = $pedido;
        }
    } else {
        $mensaje = $result['error'];
    }

    return view('Administrador.Pedido', compact('pedidos', 'mensaje', 'clientes'));
}


    /* ===================== AGREGAR PEDIDO ===================== */
    public function store(Request $request)
    {
        $request->validate([
            'id_cliente'   => 'required|numeric',
            'fecha_pedido' => 'required|date',
            'total'        => 'required|numeric'
        ]);

        $resultado = $this->pedidoService->crearPedido(
            $request->id_cliente,
            $request->fecha_pedido,
            $request->total,
            'Pendiente'
        );

        Session::flash(
            'mensaje',
            $resultado['success']
                ? 'Pedido agregado correctamente.'
                : 'Error al agregar pedido.'
        );

        return redirect()->route('pedido.index');
    }

    /* ===================== ACTUALIZAR PEDIDO ===================== */
    public function actualizar(Request $request)
    {
        $request->validate([
            'id_pedido'    => 'required|numeric',
            'fecha_pedido' => 'required|date',
            'total'        => 'required|numeric'
        ]);

        $resultado = $this->pedidoService->actualizarPedidoParcial(
            $request->id_pedido,
            $request->fecha_pedido,
            $request->total
        );

        Session::flash(
            'mensaje',
            $resultado['success']
                ? 'Pedido actualizado correctamente.'
                : 'Error al actualizar pedido.'
        );

        return redirect()->route('pedido.index');
    }

    /* ===================== CAMBIAR ESTADO ===================== */
    public function cambiarEstado(Request $request)
    {
        $request->validate([
            'id_pedido' => 'required|numeric',
            'estado'    => 'required|string'
        ]);

        $resultado = $this->pedidoService->actualizarPedidoEstado(
            $request->id_pedido,
            $request->estado
        );

        Session::flash(
            'mensaje',
            $resultado['success']
                ? 'Estado actualizado.'
                : 'Error al cambiar estado.'
        );

        return redirect()->route('pedido.index');
    }

    /* ===================== CANCELAR PEDIDO ===================== */
    public function cancelar(Request $request)
    {
        $request->validate([
            'id_pedido' => 'required|numeric'
        ]);

        $resultado = $this->pedidoService->actualizarPedidoEstado(
            $request->id_pedido,
            'Cancelado'
        );

        Session::flash(
            'mensaje',
            $resultado['success']
                ? 'Pedido cancelado.'
                : 'Error al cancelar pedido.'
        );

        return redirect()->route('pedido.index');
    }

    /* ===================== VER FACTURA ===================== */
public function verFactura($id)
{
    $resultado = $this->pedidoService->obtenerFactura($id);

    Log::info('verFactura resultado:', [
        'success' => $resultado['success'],
        'data_length' => isset($resultado['data']) ? strlen($resultado['data']) : 0
    ]);

    if ($resultado['success']) {
        $pdfContent = $resultado['data'];

        return response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="factura_' . $id . '.pdf"',
            'Content-Length'      => strlen($pdfContent),
            'Cache-Control'       => 'no-cache, no-store',
            'Pragma'              => 'no-cache',
        ]);
    }

    return redirect()->route('pedido.index')
        ->with('mensaje', 'Error al generar factura: ' . ($resultado['error'] ?? 'desconocido'));
}





}
