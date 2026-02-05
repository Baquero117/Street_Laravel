@extends('Administrador.InicioAdmin.InicioAdmin')

@section('contenido')

{{-- MENSAJE --}}
@if (!empty($mensaje))
    <div class="alert alert-info text-center">{{ $mensaje }}</div>
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
                    <td colspan="10" class="text-center">No hay productos registrados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
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
          <input class="form-control mb-2" name="id_producto" readonly>
          <input class="form-control mb-2" name="nombre">
          <textarea class="form-control mb-2" name="descripcion"></textarea>
          <input class="form-control mb-2" type="number" name="cantidad">

          {{-- NUEVA IMAGEN (OPCIONAL) --}}
          <input class="form-control mb-2" type="file" name="imagen">

          {{-- IMAGEN ACTUAL --}}
          <div class="mb-2">
            <label class="form-label">Imagen actual</label><br>
            <img id="imagenActual" src="" width="120" class="rounded border">
          </div>

          {{-- 🔑 CLAVE PARA NO BORRAR LA IMAGEN --}}
          <input type="hidden" name="imagen_actual">

          <input class="form-control mb-2" type="number" name="id_vendedor">
          <input class="form-control mb-2" type="number" name="precio" step="0.01">

          <select class="form-select mb-2" name="estado">
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
          </select>

          <input class="form-control" name="color">
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-warning">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ======================== JS ======================== --}}
<script>
function cargarProducto(id, nombre, descripcion, cantidad, id_vendedor, precio, estado, color, imagen) {
    const modal = document.getElementById('modalActualizarProducto');

    modal.querySelector('[name="id_producto"]').value = id;
    modal.querySelector('[name="nombre"]').value = nombre;
    modal.querySelector('[name="descripcion"]').value = descripcion;
    modal.querySelector('[name="cantidad"]').value = cantidad;
    modal.querySelector('[name="id_vendedor"]').value = id_vendedor;
    modal.querySelector('[name="precio"]').value = precio;
    modal.querySelector('[name="estado"]').value = estado;
    modal.querySelector('[name="color"]').value = color;

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
