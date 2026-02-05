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
        </div>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID Vendedor</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th class="text-center">Acciones</th>
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
                            <td>{{ $vendedor['telefono'] }}</td>
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-warning btn-sm btn-editar" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalActualizarVendedor"
                                        data-id="{{ $vendedor['id_vendedor'] }}"
                                        data-nombre="{{ $vendedor['nombre'] }}"
                                        data-apellido="{{ $vendedor['apellido'] }}"
                                        data-correo="{{ rawurlencode($vendedor['correo_electronico']) }}"
                                        data-telefono="{{ $vendedor['telefono'] }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <button type="button" 
                                        class="btn btn-danger btn-sm btn-eliminar" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEliminarVendedor"
                                        data-id="{{ $vendedor['id_vendedor'] }}"
                                        data-nombre="{{ $vendedor['nombre'] }} {{ $vendedor['apellido'] }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron vendedores.</td>
                    </tr>
                @endif
            </tbody>
        </table>
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
            <div class="input-group">
              <input class="form-control" type="password" name="contrasena" id="contrasena_agregar" required>
              <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('contrasena_agregar', this)">
                <i class="fas fa-eye"></i>
              </button>
            </div>
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
            <input type="number" name="id_vendedor" id="update_id_vendedor" class="form-control" readonly>
          </div>

          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input class="form-control" type="text" name="nombre" id="update_nombre" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Apellido</label>
            <input class="form-control" type="text" name="apellido" id="update_apellido" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Correo Electrónico</label>
            <input class="form-control" type="email" name="correo_electronico" id="update_correo" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Nueva Contraseña (opcional)</label>
            <div class="input-group">
              <input class="form-control" type="password" name="contrasena" id="update_contrasena">
              <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('update_contrasena', this)">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <small class="text-muted">Dejar vacío para mantener la contraseña actual</small>
          </div>

          <div class="mb-2">
            <label class="form-label">Teléfono</label>
            <input class="form-control" type="text" name="telefono" id="update_telefono" required>
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
            <input type="number" name="id_vendedor" id="delete_id_vendedor" class="form-control" readonly>
          </div>

          <p class="text-danger"><strong>⚠ ¿Está seguro de eliminar al vendedor <span id="delete_nombre_vendedor"></span>?</strong></p>
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
// Función para mostrar/ocultar contraseña
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Cargar datos cuando se abre el modal de actualización
document.addEventListener('DOMContentLoaded', function() {
    
    // Event listener para botones de editar
    const botonesEditar = document.querySelectorAll('.btn-editar');
    botonesEditar.forEach(function(boton) {
        boton.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const apellido = this.getAttribute('data-apellido');
            const correo = decodeURIComponent(this.getAttribute('data-correo')); // Decodificar el email
            const telefono = this.getAttribute('data-telefono');
            
            document.getElementById('update_id_vendedor').value = id;
            document.getElementById('update_nombre').value = nombre;
            document.getElementById('update_apellido').value = apellido;
            document.getElementById('update_correo').value = correo;
            document.getElementById('update_telefono').value = telefono;
            document.getElementById('update_contrasena').value = ''; // Limpiar contraseña
        });
    });
    
    // Event listener para botones de eliminar
    const botonesEliminar = document.querySelectorAll('.btn-eliminar');
    botonesEliminar.forEach(function(boton) {
        boton.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            
            document.getElementById('delete_id_vendedor').value = id;
            document.getElementById('delete_nombre_vendedor').textContent = nombre;
        });
    });
});
</script>

@endsection