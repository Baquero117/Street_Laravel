@extends('Administrador.InicioAdmin.InicioAdmin')

@section('contenido')

@php
$estados = ['Pendiente', 'Procesando', 'Enviado', 'Completado', 'Cancelado'];
@endphp

{{-- MENSAJE --}}
@if (!empty($mensaje))
    <div class="alert alert-info text-center">{{ $mensaje }}</div>
@endif

<div class="card mt-3">

    <div class="card-header">
        <h5 class="mb-0">Gestión de Pedidos</h5>
    </div>

    <div class="card-body p-0">
        <div class="p-3 position-relative">
    <div class="input-group">
       <input type="text"
       id="buscadorPedido"
       class="form-control"
       placeholder="Buscar por ID cliente o estado...">

        <button class="btn btn-primary" onclick="buscarManualPedido()">
            <i class="fas fa-search"></i> Buscar
        </button>
    </div>

    <div id="sugerenciasPedido"
         class="list-group shadow"
         style="position:absolute; top:70px; left:15px; right:15px; z-index:1000;">
    </div>
</div>
        <table class="table table-striped mb-0">
            
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($pedidos as $pedido)
                <tr data-id-cliente="{{ $pedido['id_cliente'] }}" data-estado="{{ $pedido['estado'] }}">
                    <td>{{ $pedido['id_pedido'] }}</td>

                    {{-- NOMBRE DEL CLIENTE --}}
                    <td>
                        @if(isset($pedido['cliente']))
                            {{ $pedido['cliente']['nombre'] }} {{ $pedido['cliente']['apellido'] }}
                        @else
                            <span class="text-danger">No disponible</span>
                        @endif
                    </td>

                    <td>{{ $pedido['fecha_pedido'] }}</td>
                    <td>${{ number_format($pedido['total'], 2) }}</td>

                    {{-- CAMBIAR ESTADO --}}
                    <td>
                        <form method="POST" action="{{ route('pedido.cambiarEstado') }}">
                            @csrf
                            <input type="hidden" name="id_pedido" value="{{ $pedido['id_pedido'] }}">
                            <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach($estados as $estado)
                                    <option value="{{ $estado }}"
                                        {{ $pedido['estado'] === $estado ? 'selected' : '' }}>
                                        {{ $estado }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>

                    {{-- ACCIONES --}}
                    <td class="text-nowrap">
                        {{-- VER DETALLE --}}
                        <button class="btn btn-info btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#verPedido{{ $pedido['id_pedido'] }}">
                            <i class="fas fa-eye"></i> Ver
                        </button>

                        {{-- VER FACTURA --}}
                        <a href="{{ route('pedido.factura', $pedido['id_pedido']) }}"
                           target="_blank"
                           class="btn btn-secondary btn-sm">
                            <i class="fas fa-file-pdf"></i> Factura
                        </a>
                    </td>
                </tr>

                {{-- MODAL VER --}}
                <div class="modal fade" id="verPedido{{ $pedido['id_pedido'] }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Información del Pedido #{{ $pedido['id_pedido'] }}</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">

                                <h6 class="text-muted">Pedido</h6>
                                <p><b>ID:</b> {{ $pedido['id_pedido'] }}</p>
                                <p><b>Fecha:</b> {{ $pedido['fecha_pedido'] }}</p>
                                <p><b>Total:</b> ${{ number_format($pedido['total'], 2) }}</p>
                                <p><b>Estado:</b>
                                    <span class="badge 
                                        @if($pedido['estado'] === 'Completado') bg-success
                                        @elseif($pedido['estado'] === 'Cancelado') bg-danger
                                        @elseif($pedido['estado'] === 'Enviado') bg-primary
                                        @elseif($pedido['estado'] === 'Procesando') bg-warning text-dark
                                        @else bg-secondary
                                        @endif">
                                        {{ $pedido['estado'] }}
                                    </span>
                                </p>

                                <hr>

                                <h6 class="text-muted">Cliente</h6>
                                @if(isset($pedido['cliente']))
                                    <p><b>Nombre:</b> {{ $pedido['cliente']['nombre'] }} {{ $pedido['cliente']['apellido'] }}</p>
                                    <p><b>Dirección:</b> {{ $pedido['cliente']['direccion'] }}</p>
                                    <p><b>Teléfono:</b> {{ $pedido['cliente']['telefono'] }}</p>
                                    <p><b>Email:</b> {{ $pedido['cliente']['correo_electronico'] }}</p>
                                @else
                                    <p class="text-danger">Cliente no disponible</p>
                                @endif

                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('pedido.factura', $pedido['id_pedido']) }}"
                                   target="_blank"
                                   class="btn btn-secondary">
                                    <i class="fas fa-file-pdf"></i> Ver Factura
                                </a>
                                <button class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-3">No hay pedidos registrados</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



<script>
function filtrarTablaPedido(valor) {
    let filas = document.querySelectorAll("table tbody tr");

    filas.forEach(fila => {
        let idPedido = (fila.cells[0]?.textContent || '').trim();
        let nombreCliente = (fila.cells[1]?.textContent || '').toLowerCase();
        let idCliente = fila.getAttribute('data-id-cliente') || '';
        let estado = fila.getAttribute('data-estado') || '';
        let buscar = valor.toLowerCase().trim();

        if (
            idPedido === valor.trim() ||
            idCliente === valor.trim() ||
            nombreCliente.includes(buscar) ||
            estado.toLowerCase().includes(buscar)
        ) {
            fila.style.display = "";
        } else {
            fila.style.display = "none";
        }
    });



}

document.getElementById('buscadorPedido').addEventListener('keyup', function() {
    let query = this.value.trim();
    let sugerencias = document.getElementById('sugerenciasPedido');
    sugerencias.innerHTML = "";

    if (query.length < 1) {
        let filas = document.querySelectorAll("table tbody tr");
        filas.forEach(fila => fila.style.display = "");
        return;
    }

    filtrarTablaPedido(query);
});

function buscarManualPedido() {
    let valor = document.getElementById('buscadorPedido').value;
    filtrarTablaPedido(valor);
}
</script>

@endsection