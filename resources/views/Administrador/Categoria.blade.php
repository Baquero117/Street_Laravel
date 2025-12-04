@extends('Administrador.InicioAdmin.InicioAdmin') 

@section('contenido')



{{-- MENSAJE --}}
@if (!empty($mensaje))
    <div class="alert alert-info text-center">{{ $mensaje }}</div>
@endif

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Categorías</h5>

        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarCategoria">
                <i class="fas fa-plus"></i> Agregar
            </button>

            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalActualizarCategoria">
                <i class="fas fa-edit"></i> Actualizar
            </button>

            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarCategoria">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
    </div>

    <div class="card-body p-0">
      <form method="GET" action="{{ route('admin.Categoria') }}">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
              @csrf
                <tr>
                    <th>ID Categoría</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($categorias))
                    @foreach ($categorias as $cate)
                        <tr>
                            <td>{{ $cate['id_categoria'] }}</td>
                            <td>{{ $cate['nombre'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="text-center">No se encontraron categorías.</td>
                    </tr>
                @endif
            </tbody>
        </table>
      </form>
    </div>
</div>

{{-- ======================== MODAL AGREGAR ======================== --}}
<div class="modal fade" id="modalAgregarCategoria" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('categoria.agregar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Agregar Categoría</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" placeholder="Ingrese el nombre" required>
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
<div class="modal fade" id="modalActualizarCategoria" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('categoria.actualizar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Actualizar Categoría</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Categoría</label>
            <input type="number" name="id_categoria" class="form-control" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Nuevo Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
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
<div class="modal fade" id="modalEliminarCategoria" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('categoria.eliminar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Eliminar Categoría</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Categoría</label>
            <input type="number" name="id_categoria" class="form-control" required>
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

<!-- Al final del body -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection
