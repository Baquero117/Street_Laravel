<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\Administrador\PedidoService;
use App\Models\Administrador\ProductoService;
use App\Models\Administrador\ClienteService;
use Illuminate\Support\Collection;

class ReporteController extends Controller
{
    public function index(
        PedidoService $pedidoService,
        ProductoService $productoService,
        ClienteService $clienteService
    ) {

       
        $pedidosResponse = $pedidoService->obtenerPedidos();

        $pedidos = collect([]);

        if ($pedidosResponse['success']) {
            $pedidos = collect($pedidosResponse['data']);
        }

        $pedidosRecientes = $pedidos
            ->sortByDesc('fecha_pedido')
            ->take(5);

        $totalVentas = $pedidos->sum('total');

        
        $productos = collect($productoService->obtenerProductos());

        $productosStockBajo = $productos
            ->where('cantidad', '<', 5);

        $totalProductos = $productos->count();

      
        $clientes = collect($clienteService->obtenerClientes());
        $totalClientes = $clientes->count();

        return view('Administrador.Reportes', compact(
            'pedidosRecientes',
            'productosStockBajo',
            'totalVentas',
            'totalProductos',
            'totalClientes'
        ));
    }
}
