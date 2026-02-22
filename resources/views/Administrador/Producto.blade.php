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
        <div class="p-3 position-relative">
            <div class="input-group">
                <input type="text"
                       id="buscadorProducto"
                       class="form-control"
                       placeholder="Buscar producto...">

                <button class="btn btn-primary" onclick="buscarManual()">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>

            <div id="sugerenciasProducto"
                 class="list-group shadow"
                 style="position:absolute; top:70px; left:15px; right:15px; z-index:1000;">
            </div>
        </div>

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
          <h5 class="modal-title modal-text">Agregar Producto</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label modal-text">Nombre *</label>
            <input class="form-control" name="nombre" placeholder="Ej: Camiseta Nike" required>
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Descripción *</label>
            <textarea class="form-control" name="descripcion" rows="3" placeholder="Describe el producto..." required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Cantidad *</label>
            <input class="form-control" type="number" name="cantidad" placeholder="Ej: 10" required min="0">
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Imagen *</label>
            <input class="form-control" type="file" name="imagen" accept="image/*" required>
            <small class="text-muted modal-text">Formatos: JPG, JPEG, PNG, WEBP (Máx. 2MB)</small>
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">ID Vendedor *</label>
            <input class="form-control" type="number" name="id_vendedor" placeholder="Ej: 1" required min="0">
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Precio *</label>
            <input class="form-control" type="number" name="precio" step="0.01" placeholder="Ej: 49.99" required min="0">
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Estado *</label>
            <select class="form-select" name="estado" required>
              <option value="">Seleccione estado</option>
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Color</label>
            <input class="form-control" name="color" placeholder="Ej: Rojo, Azul, Negro">
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Categoría *</label>
            <select class="form-select" name="id_categoria" required>
                <option value="">Seleccione categoría</option>
                <option value="20">Hombre</option>
                <option value="21">Mujer</option>
                <option value="22">Unisex</option>
            </select>
          </div>

          <small class="text-muted modal-text">* Campos obligatorios</small>
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
          <h5 class="modal-title modal-text">Actualizar Producto</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label modal-text">ID Producto</label>
            <input class="form-control" name="id_producto" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Nombre *</label>
            <input class="form-control" name="nombre" placeholder="Nombre del producto" required>
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Descripción *</label>
            <textarea class="form-control" name="descripcion" rows="3" placeholder="Descripción del producto" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Cantidad *</label>
            <input class="form-control" type="number" name="cantidad" placeholder="Cantidad disponible" required min="0">
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Nueva Imagen (opcional)</label>
            <input class="form-control" type="file" name="imagen" accept="image/*">
            <small class="text-muted modal-text">Si no seleccionas una imagen, se mantendrá la actual</small>
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Imagen actual</label><br>
            <img id="imagenActual" src="" width="120" class="rounded border" alt="Sin imagen">
          </div>

          <input type="hidden" name="imagen_actual">

          <div class="mb-3">
            <label class="form-label modal-text">ID Vendedor *</label>
            <input class="form-control" type="number" name="id_vendedor" placeholder="ID del vendedor" required min="0">
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Precio *</label>
            <input class="form-control" type="number" name="precio" step="0.01" placeholder="Precio del producto" required min="0">
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Estado *</label>
            <select class="form-select" name="estado" required>
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">Color</label>
            <input class="form-control" name="color" placeholder="Color del producto">
          </div>

          <div class="mb-3">
            <label class="form-label modal-text">ID Categoría *</label>
            <input class="form-control" type="number" name="id_categoria" placeholder="Ej: 20" required min="0">
            <small class="text-muted modal-text">20 = Hombre &nbsp;|&nbsp; 21 = Mujer &nbsp;|&nbsp; 22 = Unisex</small>
          </div>

          <small class="text-muted modal-text">* Campos obligatorios</small>
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
          <h5 class="modal-title modal-text">Eliminar Producto</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id_producto">
          <p class="modal-text">¿Está seguro que desea eliminar este producto?</p>
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

    modal.querySelector('[name="imagen_actual"]').value = imagen;
    document.getElementById('imagenActual').src = imagen ? '/storage/' + imagen : '';
}

function setEliminarId(id) {
    document.querySelector('#modalEliminarProducto [name="id_producto"]').value = id;
}

// Evitar valores negativos
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('input', () => {
        if(parseFloat(input.value) < 0){
            input.value = 0;
        }
    });
});

// BUSCADOR
let timeout = null;

document.getElementById('buscadorProducto').addEventListener('keyup', function() {
    clearTimeout(timeout);

    let query = this.value;
    let sugerencias = document.getElementById('sugerenciasProducto');

    if(query.length < 2){
        sugerencias.innerHTML = "";
        return;
    }

    timeout = setTimeout(() => {
        fetch(`/producto/buscar?nombre=${query}`)
            .then(response => response.json())
            .then(data => {
                sugerencias.innerHTML = "";

                if(data.length === 0){
                    sugerencias.innerHTML = `<div class="list-group-item text-muted">No se encontraron resultados</div>`;
                    return;
                }

                data.forEach(producto => {
                    let item = document.createElement('a');
                    item.classList.add('list-group-item', 'list-group-item-action');
                    item.innerHTML = `<strong>${producto.nombre}</strong> - $${producto.precio}`;

                    item.onclick = function(){
                        document.getElementById('buscadorProducto').value = producto.nombre;
                        sugerencias.innerHTML = "";
                        mostrarSoloProducto(producto.nombre);
                    }

                    sugerencias.appendChild(item);
                });
            });

    }, 300);
});

function buscarManual(){
    let valor = document.getElementById('buscadorProducto').value;
    filtrarTabla(valor);
}

function mostrarSoloProducto(nombreSeleccionado) {
    let filas = document.querySelectorAll("table tbody tr");

    filas.forEach(fila => {
        let nombreProducto = fila.children[1].textContent.trim().toLowerCase();

        fila.style.display = nombreProducto === nombreSeleccionado.toLowerCase() ? "" : "none";
    });
}

document.getElementById('buscadorProducto').addEventListener('input', function() {
    if(this.value === ""){
        let filas = document.querySelectorAll("table tbody tr");
        filas.forEach(fila => fila.style.display = "");
    }
});
</script>

{{-- ======================== CSS PARA MODO OSCURO ======================== --}}
<style>
.modal-text {
    color: #212529; /* Texto por defecto oscuro */
}

body.dark-mode .modal-text {
    color: #f8f9fa; /* Texto claro para dark mode */
}
</style>

@endsection