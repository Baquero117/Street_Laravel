<?php

$vendedores = $vendedores ?? [];
$mensaje = $mensaje ?? "";
?>

<div class="container">
    <?php if (!empty($mensaje)) echo $mensaje; ?>

    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Vendedores</h5>
            <div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarVendedor">
                    <i class="fas fa-plus"></i> Agregar
                </button>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalActualizarVendedor">
                    <i class="fas fa-edit"></i> Actualizar
                </button>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarVendedor">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($vendedores)): ?>
                        <?php foreach ($vendedores as $Venito): ?>
                            <tr>
                                <td><?= htmlspecialchars($Venito['id_vendedor']) ?></td>
                                <td><?= htmlspecialchars($Venito['nombre']) ?></td>
                                <td><?= htmlspecialchars($Venito['apellido']) ?></td>
                                <td><?= htmlspecialchars($Venito['correo_electronico']) ?></td>
                                <td><?= htmlspecialchars($Venito['telefono'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">No hay vendedores registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="modalAgregarVendedor" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="index.php?opcion=vendedor">
        <input type="hidden" name="_action" value="agregar">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Vendedor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input class="form-control" type="text" name="nombre" placeholder="Ingrese nombre" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Apellido</label>
            <input class="form-control" type="text" name="apellido" placeholder="Ingrese apellido" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Correo electrónico</label>
            <input class="form-control" type="email" name="correo_electronico" placeholder="ejemplo@correo.com" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Teléfono</label>
            <input class="form-control" type="tel" name="telefono" placeholder="Ingrese teléfono" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Contraseña</label>
            <input class="form-control" type="password" name="contrasena" placeholder="Ingrese contraseña" required>
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


<div class="modal fade" id="modalActualizarVendedor" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="index.php?opcion=vendedor">
        <input type="hidden" name="_action" value="actualizar">
        <div class="modal-header">
          <h5 class="modal-title">Actualizar Vendedor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">ID Vendedor</label>
            <input class="form-control" type="number" name="id_vendedor" placeholder="ID del vendedor" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input class="form-control" type="text" name="nombre" placeholder="Ingrese nombre" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Apellido</label>
            <input class="form-control" type="text" name="apellido" placeholder="Ingrese apellido" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Correo electrónico</label>
            <input class="form-control" type="email" name="correo_electronico" placeholder="ejemplo@correo.com" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Teléfono</label>
            <input class="form-control" type="tel" name="telefono" placeholder="Ingrese teléfono" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Contraseña</label>
            <input class="form-control" type="password" name="contrasena" placeholder="Ingrese contraseña" required>
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


<div class="modal fade" id="modalEliminarVendedor" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="index.php?opcion=vendedor">
        <input type="hidden" name="_action" value="eliminar">
        <div class="modal-header">
          <h5 class="modal-title">Eliminar Vendedor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">ID Vendedor</label>
            <input class="form-control" type="number" name="id_vendedor" placeholder="ID del vendedor" required>
          </div>
          <p class="text-danger">Esta acción no se puede deshacer</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>
