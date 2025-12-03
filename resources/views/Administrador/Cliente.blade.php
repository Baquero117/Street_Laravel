@extends('Administrador.InicioAdmin.InicioAdmin')

@section('contenido')

{{-- MENSAJE --}}
@if (!empty($mensaje))
    <div class="alert alert-info text-center">{{ $mensaje }}</div>
@endif

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Clientes</h5>

        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarCliente">
                <i class="fas fa-plus"></i> Agregar
            </button>

            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalActualizarCliente">
                <i class="fas fa-edit"></i> Actualizar
            </button>

            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarCliente">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID Cliente</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($clientes))
                    @foreach ($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente['id_cliente'] }}</td>
                            <td>{{ $cliente['nombre'] }}</td>
                            <td>{{ $cliente['apellido'] }}</td>
                            <td>{{ $cliente['correo_electronico'] }}</td>
                            <td>{{ $cliente['telefono'] }}</td>
                            <td>{{ $cliente['direccion'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron clientes.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- ======================== MODAL AGREGAR ======================== --}}
<div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('cliente.agregar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Agregar Cliente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input class="form-control" type="text" name="nombre" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Apellido</label>
            <input class="form-control" type="text" name="apellido" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Contraseña</label>
            <input class="form-control" type="password" name="contrasena" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Dirección</label>
            <input class="form-control" type="text" name="direccion" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Teléfono</label>
            <input class="form-control" type="text" name="telefono" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Correo Electrónico</label>
            <input class="form-control" type="email" name="correo_electronico" required>
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

{{-- ======================== MODAL ACTUALIZAR ======================== --}}
<div class="modal fade" id="modalActualizarCliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('cliente.actualizar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Actualizar Cliente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Cliente</label>
            <input type="number" name="id_cliente" class="form-control" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input class="form-control" type="text" name="nombre" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Apellido</label>
            <input class="form-control" type="text" name="apellido" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Contraseña</label>
            <input class="form-control" type="password" name="contrasena" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Dirección</label>
            <input class="form-control" type="text" name="direccion" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Teléfono</label>
            <input class="form-control" type="text" name="telefono" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Correo Electrónico</label>
            <input class="form-control" type="email" name="correo_electronico" required>
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

{{-- ======================== MODAL ELIMINAR ======================== --}}
<div class="modal fade" id="modalEliminarCliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('cliente.eliminar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Eliminar Cliente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Cliente</label>
            <input type="number" name="id_cliente" class="form-control" required>
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
