@extends('Administrador.InicioAdmin.InicioAdmin')

@section('contenido')

{{-- MENSAJE --}}
@if (!empty($mensaje))
    <div class="alert alert-info text-center">{{ $mensaje }}</div>
@endif

<div class="card mt-3">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Detalles de Productos</h5>

        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarDetalle">
                <i class="fas fa-plus"></i> Agregar
            </button>

            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalActualizarDetalle">
                <i class="fas fa-edit"></i> Actualizar
            </button>

            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarDetalle">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
    </div>

    <div class="card-body p-0">
        <form method="GET" action="{{ route('admin.DetalleProducto') }}">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID Detalle</th>
                    <th>Talla</th>
                    <th>Imagen</th>
                    <th>ID Producto</th>
                    <th>ID Categoría</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>

                @if (!empty($detalles))
                    @foreach ($detalles as $deta)
                        <tr>
                            <td>{{ $deta['id_detalle_producto'] }}</td>
                            <td>{{ $deta['talla'] }}</td>

                            <td>
                                @if (!empty($deta['imagen']))
                                    <a href="{{ asset('storage/' . $deta['imagen']) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $deta['imagen']) }}"
                                             alt="Imagen detalle"
                                             width="80" height="80"
                                             class="rounded border">
                                    </a>
                                @else
                                    <span>Sin imagen</span>
                                @endif
                            </td>

                            <td>{{ $deta['id_producto'] }}</td>
                            <td>{{ $deta['id_categoria'] }}</td>
                            <td>{{ $deta['cantidad'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron detalles de productos.</td>
                    </tr>
                @endif

            </tbody>
        </table>
        </form>
    </div>
</div>



{{-- ======================== MODAL AGREGAR ======================== --}}
<div class="modal fade" id="modalAgregarDetalle" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" enctype="multipart/form-data" action="{{ route('detalle.agregar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Agregar Detalle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">Talla</label>
            <input class="form-control" type="text" name="talla" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Imagen</label>
            <input class="form-control" type="file" name="imagen" accept="image/*" required>
          </div>

          <div class="mb-2">
            <label class="form-label">ID Producto</label>
            <input class="form-control" type="number" name="id_producto" required>
          </div>

          <div class="mb-2">
            <label class="form-label">ID Categoría</label>
            <input class="form-control" type="number" name="id_categoria" required>
          </div>

          
          <div class="mb-2">
            <label class="form-label">Cantidad</label>
            <input class="form-control" type="number" step="0.01" name="cantidad" required>
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
<div class="modal fade" id="modalActualizarDetalle" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" enctype="multipart/form-data" action="{{ route('detalle.actualizar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Actualizar Detalle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Detalle</label>
            <input class="form-control" type="number" name="id_detalle_producto" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Talla</label>
            <input class="form-control" type="text" name="talla" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Imagen</label>
            <input class="form-control" type="file" name="imagen" accept="image/*">
            <small class="text-muted">Si no selecciona una nueva imagen, se mantiene la actual.</small>
          </div>

          <div class="mb-2">
            <label class="form-label">ID Producto</label>
            <input class="form-control" type="number" name="id_producto" required>
          </div>

          <div class="mb-2">
            <label class="form-label">ID Categoría</label>
            <input class="form-control" type="number" name="id_categoria" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Cantidad</label>
            <input class="form-control" type="number" step="0.01" name="cantidad" required>
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
<div class="modal fade" id="modalEliminarDetalle" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('detalle.eliminar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Eliminar Detalle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Detalle</label>
            <input class="form-control" type="number" name="id_detalle_producto" required>
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
