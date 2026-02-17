@extends('Administrador.InicioAdmin.InicioAdmin')

@section('contenido')

{{-- MENSAJE --}}
@if (!empty($mensaje))
    <div class="alert alert-info text-center">{{ $mensaje }}</div>
@endif

{{-- ======================== RESUMEN GENERAL ======================== --}}
<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0">Resumen General del Sistema</h5>
    </div>

    <div class="card-body">
        <div class="row text-center">

            <div class="col-md-4 mb-3">
                <div class="border rounded p-3 shadow-sm">
                    <h6>Total Ventas</h6>
                    <h4 class="text-success">$ {{ number_format($totalVentas, 2) }}</h4>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="border rounded p-3 shadow-sm">
                    <h6>Total Productos</h6>
                    <h4 class="text-primary">{{ $totalProductos }}</h4>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="border rounded p-3 shadow-sm">
                    <h6>Total Clientes</h6>
                    <h4 class="text-dark">{{ $totalClientes }}</h4>
                </div>
            </div>

        </div>
    </div>
</div>


{{-- ======================== PEDIDOS RECIENTES ======================== --}}
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Últimos 5 Pedidos</h5>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID Pedido</th>
                    <th>ID Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Método Pago</th>
                </tr>
            </thead>
            <tbody>
                @if($pedidosRecientes->count() > 0)
                    @foreach($pedidosRecientes as $pedido)
                        <tr>
                            <td>{{ $pedido['id_pedido'] }}</td>
                            <td>{{ $pedido['id_cliente'] }}</td>
                            <td>{{ $pedido['fecha_pedido'] }}</td>
                            <td>$ {{ number_format($pedido['total'], 2) }}</td>
                            <td>{{ $pedido['estado'] }}</td>
                            <td>{{ $pedido['metodo_pago'] ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No hay pedidos registrados.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>


{{-- ======================== PRODUCTOS CON STOCK BAJO ======================== --}}
<div class="card mt-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Productos por acabarse (Stock menor a 5)</h5>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @if($productosStockBajo->count() > 0)
                    @foreach($productosStockBajo as $producto)
                        <tr>
                            <td>{{ $producto['id_producto'] }}</td>
                            <td>{{ $producto['nombre'] }}</td>
                            <td class="text-danger"><strong>{{ $producto['cantidad'] }}</strong></td>
                            <td>$ {{ number_format($producto['precio'], 2) }}</td>
                            <td>{{ $producto['estado'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">No hay productos con stock bajo.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection
