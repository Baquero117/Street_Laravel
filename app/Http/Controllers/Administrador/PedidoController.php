<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrador\PedidoService;
use Illuminate\Support\Facades\Session;

class PedidoController extends Controller
{
    private $pedidoService;

    public function __construct(PedidoService $pedidoService)
    {
        // 🔥 Protección sin middleware
        if (!session()->has('token')) {
            redirect()->route('login')->send();
        }

        $this->pedidoService = $pedidoService;
    }

    public function index()
    {
       $result = $this->pedidoService->obtenerPedidos();

$pedidos = $result["success"] ? $result["data"] : [];
$mensaje = $result["success"] ? "" : $result["error"];


        return view('Administrador.Pedido', compact('pedidos', 'mensaje'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cliente'     => 'required|numeric',
            'fecha_pedido'   => 'required|string|max:255',
            'total'          => 'required|numeric',
            'estado'         => 'required|string|max:100',
        ]);

        $resultado = $this->pedidoService->crearPedido(
            $request->id_cliente,
            $request->fecha_pedido,
            $request->total,
            $request->estado
        );

        Session::flash('mensaje', $resultado['success']
            ? "Pedido agregado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('pedido.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_pedido'      => 'required|numeric',
            'id_cliente'     => 'required|numeric',
            'fecha_pedido'   => 'required|string|max:255',
            'total'          => 'required|numeric',
            'estado'         => 'required|string|max:100',
        ]);

        $resultado = $this->pedidoService->actualizarPedido(
            $request->id_pedido,
            $request->id_cliente,
            $request->fecha_pedido,
            $request->total,
            $request->estado
        );

        Session::flash('mensaje', $resultado['success']
            ? "Pedido actualizado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('pedido.index');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id_pedido' => 'required|numeric',
        ]);

        $resultado = $this->pedidoService->eliminarPedido($request->id_pedido);

        Session::flash('mensaje', $resultado['success']
            ? "Pedido eliminado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('pedido.index');
    }
}
