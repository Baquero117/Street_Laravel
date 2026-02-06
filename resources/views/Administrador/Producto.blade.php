@extends('Administrador.InicioAdmin.InicioAdmin')

@section('contenido')

{{-- MENSAJES --}}
@if (!empty($mensaje))
    <div class="alert alert-info text-center">{{ $mensaje }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card mt-3">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Productos</h5>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">
            <i class="fas fa-plus"></i> Agregar
        </button>
    </div>

    <div class="card-body p-0">
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
                    <th>Precio</th>
                    <th>Color</th>
                    <th>ID Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($productos as $pro)
                <tr>
                    <td>{{ $pro['id_producto'] }}</td>
                    <td>{{ $pro['nombre'] }}</td>
                    <td>{{ $pro['descripcion'] }}</td>
                    <td>{{ $pro['cantidad'] }}</td>

                    <td>
                        @if (!empty($pro['imagen']))
                            <img src="{{ asset('storage/' . $pro['imagen']) }}" width="70" class="rounded border">
                        @else
                            <span>Sin imagen</span>
                        @endif
                    </td>

                    <td>{{ $pro['id_vendedor'] }}</td>
                    <td>{{ $pro['estado'] }}</td>
                    <td>{{ $pro['precio'] }}</td>
                    <td>{{ $pro['color'] }}</td>
                    <td>{{ $pro['id_categoria'] }}</td>

                    <td>
                        <button class="btn btn-sm btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#modalActualizarProducto"
                            onclick="cargarProducto(
                                '{{ $pro['id_producto'] }}',
                                '{{ $pro['nombre'] }}',
                                '{{ $pro['descripcion'] }}',
                                '{{ $pro['cantidad'] }}',
                                '{{ $pro['id_vendedor'] }}',
                                '{{ $pro['precio'] }}',
                                '{{ $pro['estado'] }}',
                                '{{ $pro['color'] }}',
                                '{{ $pro['id_categoria'] }}',
                                '{{ $pro['imagen'] }}'
                            )">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button class="btn btn-sm btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEliminarProducto"
                            onclick="setEliminarId('{{ $pro['id_producto'] }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">No hay productos registrados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ======================== MODAL AGREGAR ======================== --}}
<div class="modal fade" id="modalAgregarProducto" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data" action="{{ route('producto.agregar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Agregar Producto</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nombre *</label>
            <input class="form-control" name="nombre" placeholder="Ej: Camiseta Nike" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción *</label>
            <textarea class="form-control" name="descripcion" rows="3" placeholder="Describe el producto..." required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Cantidad *</label>
            <input class="form-control" type="number" name="cantidad" placeholder="Ej: 10" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Imagen *</label>
            <input class="form-control" type="file" name="imagen" accept="image/*" required>
            <small class="text-muted">Formatos: JPG, JPEG, PNG, WEBP (Máx. 2MB)</small>
          </div>

          <div class="mb-3">
            <label class="form-label">ID Vendedor *</label>
            <input class="form-control" type="number" name="id_vendedor" placeholder="Ej: 1" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Precio *</label>
            <input class="form-control" type="number" name="precio" step="0.01" placeholder="Ej: 49.99" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Estado *</label>
            <select class="form-select" name="estado" required>
              <option value="">Seleccione estado</option>
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Color</label>
            <input class="form-control" name="color" placeholder="Ej: Rojo, Azul, Negro">
          </div>

          <div class="mb-3">
            <label class="form-label">ID Categoría</label>
            <input class="form-control" type="number" name="id_categoria" placeholder="Ej: 5">
          </div>

          <small class="text-muted">* Campos obligatorios</small>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ======================== MODAL ACTUALIZAR ======================== --}}
<div class="modal fade" id="modalActualizarProducto" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data" action="{{ route('producto.actualizar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Actualizar Producto</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">ID Producto</label>
            <input class="form-control" name="id_producto" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Nombre *</label>
            <input class="form-control" name="nombre" placeholder="Nombre del producto" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción *</label>
            <textarea class="form-control" name="descripcion" rows="3" placeholder="Descripción del producto" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Cantidad *</label>
            <input class="form-control" type="number" name="cantidad" placeholder="Cantidad disponible" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Nueva Imagen (opcional)</label>
            <input class="form-control" type="file" name="imagen" accept="image/*">
            <small class="text-muted">Si no seleccionas una imagen, se mantendrá la actual</small>
          </div>

          <div class="mb-3">
            <label class="form-label">Imagen actual</label><br>
            <img id="imagenActual" src="" width="120" class="rounded border" alt="Sin imagen">
          </div>

          {{-- 🔑 CLAVE PARA NO BORRAR LA IMAGEN --}}
          <input type="hidden" name="imagen_actual">

          <div class="mb-3">
            <label class="form-label">ID Vendedor *</label>
            <input class="form-control" type="number" name="id_vendedor" placeholder="ID del vendedor" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Precio *</label>
            <input class="form-control" type="number" name="precio" step="0.01" placeholder="Precio del producto" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Estado *</label>
            <select class="form-select" name="estado" required>
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Color</label>
            <input class="form-control" name="color" placeholder="Color del producto">
          </div>

          <div class="mb-3">
            <label class="form-label">ID Categoría</label>
            <input class="form-control" type="number" name="id_categoria" placeholder="ID de la categoría">
          </div>

          <small class="text-muted">* Campos obligatorios</small>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">
            <i class="fas fa-sync-alt"></i> Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ======================== MODAL ELIMINAR ======================== --}}
<div class="modal fade" id="modalEliminarProducto" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('producto.eliminar') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Eliminar Producto</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id_producto">
          <p>¿Está seguro que desea eliminar este producto?</p>
          <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ======================== JS ======================== --}}
<script>
function cargarProducto(id, nombre, descripcion, cantidad, id_vendedor, precio, estado, color, id_categoria, imagen) {
    const modal = document.getElementById('modalActualizarProducto');

    modal.querySelector('[name="id_producto"]').value = id;
    modal.querySelector('[name="nombre"]').value = nombre;
    modal.querySelector('[name="descripcion"]').value = descripcion;
    modal.querySelector('[name="cantidad"]').value = cantidad;
    modal.querySelector('[name="id_vendedor"]').value = id_vendedor;
    modal.querySelector('[name="precio"]').value = precio;
    modal.querySelector('[name="estado"]').value = estado;
    modal.querySelector('[name="color"]').value = color;
    modal.querySelector('[name="id_categoria"]').value = id_categoria;

    // ✅ MOSTRAR Y GUARDAR IMAGEN ACTUAL
    modal.querySelector('[name="imagen_actual"]').value = imagen;
    document.getElementById('imagenActual').src = imagen 
        ? '/storage/' + imagen 
        : '';
}

function setEliminarId(id) {
    document.querySelector('#modalEliminarProducto [name="id_producto"]').value = id;
}
</script>

@endsection