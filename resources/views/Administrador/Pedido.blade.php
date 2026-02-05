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

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Pedidos</h5>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarPedido">
            <i class="fas fa-plus"></i> Agregar
        </button>
    </div>

    <div class="card-body p-0">
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
                <tr>
                    <td>{{ $pedido['id_pedido'] }}</td>
                    <td>{{ $pedido['id_cliente'] }}</td>
                    <td>{{ $pedido['fecha_pedido'] }}</td>
                    <td>{{ $pedido['total'] }}</td>

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
                        <button class="btn btn-info btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#verPedido{{ $pedido['id_pedido'] }}">
                            Ver
                        </button>

                        <button class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#editarPedido{{ $pedido['id_pedido'] }}">
                            Editar
                        </button>

                        <form method="POST" action="{{ route('pedido.cancelar') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="id_pedido" value="{{ $pedido['id_pedido'] }}">
                            <button class="btn btn-danger btn-sm">Cancelar</button>
                        </form>

                        <button class="btn btn-secondary btn-sm" disabled>
                            Factura
                        </button>
                    </td>
                </tr>

                {{-- MODAL VER INFORMACIÓN --}}
                <div class="modal fade" id="verPedido{{ $pedido['id_pedido'] }}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>Información del Pedido</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <h6>Pedido</h6>
                                <p><b>ID:</b> {{ $pedido['id_pedido'] }}</p>
                                <p><b>Fecha:</b> {{ $pedido['fecha_pedido'] }}</p>
                                <p><b>Total:</b> {{ $pedido['total'] }}</p>
                                <p><b>Estado:</b> {{ $pedido['estado'] }}</p>

                                <hr>

                                <h6>Cliente</h6>
                                @if(isset($pedido['cliente']))
                                    <p><b>Nombre:</b> {{ $pedido['cliente']['nombre'] }} {{ $pedido['cliente']['apellido'] }}</p>
                                    <p><b>Dirección:</b> {{ $pedido['cliente']['direccion'] }}</p>
                                    <p><b>Teléfono:</b> {{ $pedido['cliente']['telefono'] }}</p>
                                    <p><b>Email:</b> {{ $pedido['cliente']['correo_electronico'] }}</p>
                                @else
                                    <p class="text-danger">Cliente no disponible</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL EDITAR --}}
                <div class="modal fade" id="editarPedido{{ $pedido['id_pedido'] }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('pedido.actualizar') }}">
                                @csrf
                                <input type="hidden" name="id_pedido" value="{{ $pedido['id_pedido'] }}">

                                <div class="modal-header">
                                    <h5>Actualizar Pedido</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <label>Fecha</label>
                                    <input type="date" name="fecha_pedido" class="form-control mb-2"
                                        value="{{ $pedido['fecha_pedido'] }}">

                                    <label>Total</label>
                                    <input type="number" step="0.01" name="total" class="form-control mb-2"
                                        value="{{ $pedido['total'] }}">
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-warning">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay pedidos</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL AGREGAR PEDIDO --}}
<div class="modal fade" id="modalAgregarPedido" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="{{ route('pedido.agregar') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Agregar Pedido</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label>ID Cliente</label>
                    <input type="number" name="id_cliente" class="form-control mb-2" required>

                    <label>Fecha</label>
                    <input type="date" name="fecha_pedido" class="form-control mb-2" required>

                    <label>Total</label>
                    <input type="number" step="0.01" name="total" class="form-control mb-2" required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
