<?php

$productos = $productos ?? [];
$mensaje = $mensaje ?? "";
?>

<div class="container">
    <?php if (!empty($mensaje)) echo $mensaje; ?>

    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Productos</h5>
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
                    <?php if (!empty($productos)): ?>
                        <?php foreach ($productos as $pro): ?>
                            <tr>
                                <td><?= htmlspecialchars($pro['id_producto']) ?></td>
                                <td><?= htmlspecialchars($pro['nombre']) ?></td>
                                <td><?= htmlspecialchars($pro['descripcion']) ?></td>
                                <td><?= htmlspecialchars($pro['cantidad']) ?></td>
                                <td>
                                    <?php if (!empty($pro['imagen'])): ?>
                                        <a href="../../<?= htmlspecialchars($pro['imagen']) ?>" target="_blank">
                                            <img src="../../<?= htmlspecialchars($pro['imagen']) ?>" alt="imagen producto" width="80" height="80">
                                        </a>
                                    <?php else: ?>
                                        <span>Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($pro['id_vendedor']) ?></td>
                                <td><?= htmlspecialchars($pro['estado']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No hay productos registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAgregarProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data" action="index.php?opcion=producto">
        <input type="hidden" name="_action" value="agregar">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input class="form-control" type="text" name="nombre" placeholder="Ingrese el nombre del producto" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" placeholder="Ingrese la descripción del producto" required></textarea>
          </div>
          <div class="mb-2">
            <label class="form-label">Cantidad</label>
            <input class="form-control" type="number" name="cantidad" placeholder="Ingrese la cantidad disponible" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Imagen</label>
            <input class="form-control" type="file" name="imagen" accept="image/*" required>
          </div>
          <div class="mb-2">
            <label class="form-label">ID Vendedor</label>
            <input class="form-control" type="number" name="id_vendedor" placeholder="Ingrese el ID del vendedor" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado" required>
                <option value="">Seleccione un estado</option>
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


<div class="modal fade" id="modalActualizarProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data" action="index.php?opcion=producto">
        <input type="hidden" name="_action" value="actualizar">
        <div class="modal-header">
          <h5 class="modal-title">Actualizar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">ID Producto</label>
            <input class="form-control" type="number" name="id_producto" placeholder="Ingrese el ID del producto" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input class="form-control" type="text" name="nombre" placeholder="Ingrese el nombre del producto" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" placeholder="Ingrese la descripción del producto" required></textarea>
          </div>
          <div class="mb-2">
            <label class="form-label">Cantidad</label>
            <input class="form-control" type="number" name="cantidad" placeholder="Ingrese la cantidad disponible" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Imagen</label>
            <input class="form-control" type="file" name="imagen" accept="image/*">
            <small class="text-muted">Si no selecciona nada, se mantendrá la actual.</small>
          </div>
          <div class="mb-2">
            <label class="form-label">ID Vendedor</label>
            <input class="form-control" type="number" name="id_vendedor" placeholder="Ingrese el ID del vendedor" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado" required>
                <option value="">Seleccione un estado</option>
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


<div class="modal fade" id="modalEliminarProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="index.php?opcion=producto">
        <input type="hidden" name="_action" value="eliminar">
        <div class="modal-header">
          <h5 class="modal-title">Eliminar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">ID Producto</label>
            <input class="form-control" type="number" name="id_producto" placeholder="Ingrese el ID del producto" required>
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
