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
                    <th class="text-center">Acciones</th>
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
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-warning btn-sm btn-editar" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalActualizarCliente"
                                        data-id="{{ $cliente['id_cliente'] }}"
                                        data-nombre="{{ $cliente['nombre'] }}"
                                        data-apellido="{{ $cliente['apellido'] }}"
                                        data-correo="{{ $cliente['correo_electronico'] }}"
                                        data-telefono="{{ $cliente['telefono'] }}"
                                        data-direccion="{{ $cliente['direccion'] }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <button type="button" 
                                        class="btn btn-danger btn-sm btn-eliminar" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEliminarCliente"
                                        data-id="{{ $cliente['id_cliente'] }}"
                                        data-nombre="{{ $cliente['nombre'] }} {{ $cliente['apellido'] }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron clientes.</td>
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
            <input type="number" name="id_cliente" id="update_id_cliente" class="form-control" readonly>
          </div>

          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input class="form-control" type="text" name="nombre" id="update_nombre" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Apellido</label>
            <input class="form-control" type="text" name="apellido" id="update_apellido" required>
          </div>

          <!-- Campo oculto con valor placeholder para la contraseña -->
          <input type="hidden" name="contrasena" value="SIN_CAMBIOS_PASSWORD_2024">

          <div class="mb-2">
            <label class="form-label">Dirección</label>
            <input class="form-control" type="text" name="direccion" id="update_direccion" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Teléfono</label>
            <input class="form-control" type="text" name="telefono" id="update_telefono" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Correo Electrónico</label>
            <input class="form-control" type="email" name="correo_electronico" id="update_correo" required>
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
            <input type="number" name="id_cliente" id="delete_id_cliente" class="form-control" readonly>
          </div>

          <p class="text-danger"><strong>⚠ ¿Está seguro de eliminar al cliente <span id="delete_nombre_cliente"></span>?</strong></p>
          <p class="text-danger">Esta acción no se puede deshacer.</p>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </div>

      </form>

    </div>
  </div>
</div>

{{-- ======================== SCRIPTS ======================== --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Cargar datos cuando se abre el modal de actualización
document.addEventListener('DOMContentLoaded', function() {
    
    // Event listener para botones de editar
    const botonesEditar = document.querySelectorAll('.btn-editar');
    botonesEditar.forEach(function(boton) {
        boton.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const apellido = this.getAttribute('data-apellido');
            const correo = this.getAttribute('data-correo');
            const telefono = this.getAttribute('data-telefono');
            const direccion = this.getAttribute('data-direccion');
            
            document.getElementById('update_id_cliente').value = id;
            document.getElementById('update_nombre').value = nombre;
            document.getElementById('update_apellido').value = apellido;
            document.getElementById('update_correo').value = correo;
            document.getElementById('update_telefono').value = telefono;
            document.getElementById('update_direccion').value = direccion;
            document.getElementById('update_contrasena').value = '';
        });
    });
    
    // Event listener para botones de eliminar
    const botonesEliminar = document.querySelectorAll('.btn-eliminar');
    botonesEliminar.forEach(function(boton) {
        boton.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            
            document.getElementById('delete_id_cliente').value = id;
            document.getElementById('delete_nombre_cliente').textContent = nombre;
        });
    });
});
</script>

@endsection