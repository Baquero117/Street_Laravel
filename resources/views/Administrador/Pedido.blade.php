@extends('Administrador.InicioAdmin.InicioAdmin')

@section('contenido')

{{-- MENSAJE --}}
@if (!empty($mensaje))
    <div class="alert alert-info text-center">{{ $mensaje }}</div>
@endif

<div class="card mt-3">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Pedidos</h5>

        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarPedido">
                <i class="fas fa-plus"></i> Agregar
            </button>

            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalActualizarPedido">
                <i class="fas fa-edit"></i> Actualizar
            </button>

            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarPedido">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
    </div>

    <div class="card-body p-0">
        <form method="GET" action="{{ route('admin.Pedido') }}">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    @csrf
                    <tr>
                        <th>ID Pedido</th>
                        <th>ID Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($pedidos))
                       @foreach ($pedidos as $pedido)
                        <tr>
                        <td>{{ $pedido['id_pedido'] }}</td>
                        <td>{{ $pedido['id_cliente'] }}</td>
                        <td>{{ $pedido['fecha_pedido'] }}</td>
                      <td>{{ $pedido['total'] }}</td>
                      <td>{{ $pedido['estado'] }}</td>
                        </tr>
                    @endforeach

                    @else
                        <tr>
                            <td colspan="5" class="text-center">No se encontraron pedidos.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </form>
    </div>
</div>

{{-- ============================= MODAL AGREGAR ============================= --}}
<div class="modal fade" id="modalAgregarPedido" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('pedido.agregar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Agregar Pedido</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Cliente</label>
            <input type="number" name="id_cliente" class="form-control" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha_pedido" class="form-control" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Total</label>
            <input type="number" step="0.01" name="total" class="form-control" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Estado</label>
            <input type="text" name="estado" class="form-control" required>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Agregar</button>
        </div>

      </form>

    </div>
  </div>
</div>

{{-- ============================= MODAL ACTUALIZAR ============================= --}}
<div class="modal fade" id="modalActualizarPedido" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('pedido.actualizar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Actualizar Pedido</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Pedido</label>
            <input type="number" name="id_pedido" class="form-control" required>
          </div>

          <div class="mb-2">
            <label class="form-label">ID Cliente</label>
            <input type="number" name="id_cliente" class="form-control" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha_pedido" class="form-control" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Total</label>
            <input type="number" step="0.01" name="total" class="form-control" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Estado</label>
            <input type="text" name="estado" class="form-control" required>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">Actualizar</button>
        </div>

      </form>

    </div>
  </div>
</div>

{{-- ============================= MODAL ELIMINAR ============================= --}}
<div class="modal fade" id="modalEliminarPedido" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('pedido.eliminar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Eliminar Pedido</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Pedido</label>
            <input type="number" name="id_pedido" class="form-control" required>
          </div>

          <p class="text-danger">⚠ Esta acción no se puede deshacer.</p>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </div>

      </form>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection
