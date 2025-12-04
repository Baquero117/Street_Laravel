@extends('Administrador.InicioAdmin.InicioAdmin')

@section('contenido')

{{-- MENSAJE --}}
@if (!empty($mensaje))
    <div class="alert alert-info text-center">{{ $mensaje }}</div>
@endif

<div class="card mt-3">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Productos</h5>

        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">
                <i class="fas fa-plus"></i> Agregar
            </button>

            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalActualizarProducto">
                <i class="fas fa-edit"></i> Actualizar
            </button>

            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarProducto">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
    </div>

    <div class="card-body p-0">

        <form method="GET" action="{{ route('admin.Producto') }}">

        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Imagen</th>
                    <th>ID Vendedor</th>
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>

                @if (!empty($productos))
                    @foreach ($productos as $pro)
                        <tr>
                            <td>{{ $pro['id_producto'] }}</td>
                            <td>{{ $pro['nombre'] }}</td>
                            <td>{{ $pro['descripcion'] }}</td>
                            <td>{{ $pro['cantidad'] }}</td>

                            <td>
                                @if (!empty($pro['imagen']))
                                   <a href="{{ asset('storage/' . $pro['imagen']) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $pro['imagen']) }}"
                              width="80" height="80" 
                          class="rounded border">
                          </a>

                                @else
                                    <span>Sin imagen</span>
                                @endif
                            </td>

                            <td>{{ $pro['id_vendedor'] }}</td>
                            <td>{{ $pro['estado'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">No hay productos registrados.</td>
                    </tr>
                @endif

            </tbody>
        </table>

        </form>

    </div>
</div>



{{-- ======================== MODAL AGREGAR ======================== --}}
<div class="modal fade" id="modalAgregarProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" enctype="multipart/form-data" action="{{ route('producto.agregar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Agregar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input class="form-control" type="text" name="nombre" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" required></textarea>
          </div>

          <div class="mb-2">
            <label class="form-label">Cantidad</label>
            <input class="form-control" type="number" name="cantidad" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Imagen</label>
            <input class="form-control" type="file" name="imagen" accept="image/*" required>
          </div>

          <div class="mb-2">
            <label class="form-label">ID Vendedor</label>
            <input class="form-control" type="number" name="id_vendedor" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado" required>
                <option value="">Seleccione</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
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
<div class="modal fade" id="modalActualizarProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" enctype="multipart/form-data" action="{{ route('producto.actualizar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Actualizar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Producto</label>
            <input class="form-control" type="number" name="id_producto" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input class="form-control" type="text" name="nombre" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" required></textarea>
          </div>

          <div class="mb-2">
            <label class="form-label">Cantidad</label>
            <input class="form-control" type="number" name="cantidad" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Imagen</label>
            <input class="form-control" type="file" name="imagen" accept="image/*">
            <small class="text-muted">Si no selecciona una nueva imagen, la actual se mantiene.</small>
          </div>

          <div class="mb-2">
            <label class="form-label">ID Vendedor</label>
            <input class="form-control" type="number" name="id_vendedor" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado" required>
                <option value="">Seleccione</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
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
<div class="modal fade" id="modalEliminarProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('producto.eliminar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Eliminar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="form-label">ID Producto</label>
            <input class="form-control" type="number" name="id_producto" required>
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
