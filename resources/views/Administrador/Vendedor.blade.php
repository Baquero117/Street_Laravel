@extends('Administrador.InicioAdmin.InicioAdmin')

@section('contenido')

{{-- MENSAJE --}}
@if (!empty($mensaje))
    <div class="alert alert-info text-center">{{ $mensaje }}</div>
@endif

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Vendedores</h5>

        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarVendedor">
                <i class="fas fa-plus"></i> Agregar
            </button>

            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalActualizarVendedor">
                <i class="fas fa-edit"></i> Actualizar
            </button>

            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarVendedor">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
    </div>

    <div class="card-body p-0">
       <form method="GET" action="{{ route('admin.Vendedor') }}">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
               @csrf
                <tr>
                    <th>ID Vendedor</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>contraseña</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($vendedores))
                    @foreach ($vendedores as $vendedor)
                        <tr>
                            <td>{{ $vendedor['id_vendedor'] }}</td>
                            <td>{{ $vendedor['nombre'] }}</td>
                            <td>{{ $vendedor['apellido'] }}</td>
                            <td>{{ $vendedor['correo_electronico'] }}</td>
                            <td>{{ $vendedor['contrasena'] }}</td>
                            <td>{{ $vendedor['telefono'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron vendedores.</td>
                    </tr>
                @endif
            </tbody>
        </table>
       </form>
    </div>
</div>

{{-- ======================== MODAL AGREGAR ======================== --}}
<div class="modal fade" id="modalAgregarVendedor" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('vendedor.agregar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Agregar Vendedor</h5>
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
            <label class="form-label">Correo Electrónico</label>
            <input class="form-control" type="email" name="correo_electronico" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Contraseña</label>
            <input class="form-control" type="password" name="contrasena" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Teléfono</label>
            <input class="form-control" type="text" name="telefono" required>
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
<div class="modal fade" id="modalActualizarVendedor" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('vendedor.actualizar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Actualizar Vendedor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Vendedor</label>
            <input type="number" name="id_vendedor" class="form-control" required>
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
            <label class="form-label">Correo Electrónico</label>
            <input class="form-control" type="email" name="correo_electronico" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Contraseña</label>
            <input class="form-control" type="password" name="contrasena" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Teléfono</label>
            <input class="form-control" type="text" name="telefono" required>
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
<div class="modal fade" id="modalEliminarVendedor" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('vendedor.eliminar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Eliminar Vendedor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Vendedor</label>
            <input type="number" name="id_vendedor" class="form-control" required>
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
