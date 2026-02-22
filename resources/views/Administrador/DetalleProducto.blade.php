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
        </div>
    </div>

    <div class="card-body p-0">

<div class="p-3 position-relative">
    <div class="input-group">
        <input type="number"
               id="buscadorDetalle"
               class="form-control"
               placeholder="Buscar por ID Producto...">

        <button class="btn btn-primary" onclick="buscarDetalleManual()">
            <i class="fas fa-search"></i> Buscar
        </button>
    </div>

    <div id="sugerenciasDetalle"
         class="list-group shadow"
         style="position:absolute; top:70px; left:15px; right:15px; z-index:1000;">
    </div>
</div>

<table class="table table-striped mb-0">
    <thead class="table-dark">
        <tr>
            <th>ID Detalle</th>
            <th>Talla</th>
            <th>Imagen</th>
            <th>ID Producto</th>
            <th>Cantidad</th>
            <th>Acciones</th>
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
                                     width="80" height="80"
                                     class="rounded border">
                            </a>
                        @else
                            <span>Sin imagen</span>
                        @endif
                    </td>

                    <td>{{ $deta['id_producto'] }}</td>
                    <td>{{ $deta['cantidad'] }}</td>

                   <td class="align-middle">
    <div class="d-flex gap-2 justify-content-center">
                        <button type="button"
                            class="btn btn-sm btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#modalActualizarDetalle"
                            onclick="cargarDetalle(
                                '{{ $deta['id_detalle_producto'] }}',
                                '{{ $deta['talla'] }}',
                                '{{ $deta['id_producto'] }}',
                                '{{ $deta['cantidad'] }}'
                            )">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button type="button"
                            class="btn btn-sm btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEliminarDetalle"
                            onclick="setEliminarDetalleId('{{ $deta['id_detalle_producto'] }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center">
                    No se encontraron detalles de productos.
                </td>
            </tr>
        @endif

    </tbody>
</table>
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


<script>
let timeoutDetalle = null;

document.getElementById('buscadorDetalle').addEventListener('keyup', function() {

    clearTimeout(timeoutDetalle);

    let query = this.value;
    let sugerencias = document.getElementById('sugerenciasDetalle');

    if(query.length < 1){
        sugerencias.innerHTML = "";
        return;
    }

    timeoutDetalle = setTimeout(() => {

        fetch(`/detalle_producto/buscar?id_producto=${query}`)
            .then(response => response.json())
            .then(data => {

                sugerencias.innerHTML = "";

                if(!data || data.length === 0){
                    sugerencias.innerHTML = `
                        <div class="list-group-item text-muted">
                            No se encontraron resultados
                        </div>`;
                    return;
                }

                data.forEach(detalle => {

                    let item = document.createElement('a');
                    item.classList.add('list-group-item', 'list-group-item-action');
                    item.innerHTML = `
                        <strong>ID Detalle:</strong> ${detalle.id_detalle_producto}
                        | <strong>Talla:</strong> ${detalle.talla}
                    `;

                    item.onclick = function(){
                        document.getElementById('buscadorDetalle').value = detalle.id_producto;
                        sugerencias.innerHTML = "";
                        mostrarSoloDetalle(detalle.id_producto);
                    }

                    sugerencias.appendChild(item);
                });
            });

    }, 300);
});


function buscarDetalleManual(){
    let valor = document.getElementById('buscadorDetalle').value;
    mostrarSoloDetalle(valor);
}


function mostrarSoloDetalle(idProductoSeleccionado) {

    let filas = document.querySelectorAll("table tbody tr");

    filas.forEach(fila => {
        let idProducto = fila.children[3].textContent.trim();

        if(idProducto === idProductoSeleccionado){
            fila.style.display = "";
        } else {
            fila.style.display = "none";
        }
    });
}


// Cuando se borra el input, mostrar todo
document.getElementById('buscadorDetalle').addEventListener('input', function() {
    if(this.value === ""){
        let filas = document.querySelectorAll("table tbody tr");
        filas.forEach(fila => fila.style.display = "");
    }
});
</script>

<script>
function cargarDetalle(id, talla, id_producto, cantidad) {

    const modal = document.getElementById('modalActualizarDetalle');

    modal.querySelector('[name="id_detalle_producto"]').value = id;
    modal.querySelector('[name="talla"]').value = talla;
    modal.querySelector('[name="id_producto"]').value = id_producto;
    modal.querySelector('[name="cantidad"]').value = cantidad;
}

function setEliminarDetalleId(id) {
    document.querySelector('#modalEliminarDetalle [name="id_detalle_producto"]').value = id;
}
</script>




@endsection
