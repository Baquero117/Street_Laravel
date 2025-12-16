@extends('Administrador.InicioAdmin.InicioAdmin')

@section('contenido')

{{-- MENSAJE --}}
@if (!empty($mensaje))
    <div class="alert alert-info text-center">{{ $mensaje }}</div>
@endif

<div class="card mt-3">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Listado de Valoraciones</h5>

        <div>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarValoracion">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
    </div>

    <div class="card-body p-0">

        <form method="GET" action="{{ route('admin.Valoracion') }}">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>ID Cliente</th>
                        <th>ID Producto</th>
                        <th>Calificación</th>
                        <th>Comentario</th>
                        <th>Fecha</th>
                    </tr>
                </thead>

                <tbody>
                    @if (!empty($valoraciones))
                        @foreach ($valoraciones as $valora)
                            <tr>
                                <td>{{ $valora['id_valoracion'] }}</td>
                                <td>{{ $valora['id_cliente'] }}</td>
                                <td>{{ $valora['id_producto'] }}</td>
                                <td>{{ $valora['calificacion'] }}</td>
                                <td>{{ $valora['comentario'] }}</td>
                                <td>{{ $valora['fecha_valoracion'] }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-danger">No se encontraron valoraciones.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </form>

    </div>
</div>

{{-- ======================== MODAL ELIMINAR ======================== --}}
<div class="modal fade" id="modalEliminarValoracion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('valoracion.eliminar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Eliminar Valoración</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Valoración</label>
            <input class="form-control" type="number" name="id_valoracion" required>
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
