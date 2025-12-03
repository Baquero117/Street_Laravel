<?php

$detalles = $detalles ?? [];
$mensaje = $mensaje ?? "";
?>

<div class="container">
    <?php if (!empty($mensaje)) echo $mensaje; ?>

    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Detalles de Productos</h5>
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
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID Detalle</th>
                        <th>Talla</th>
                        <th>Color</th>
                        <th>Imagen</th>
                        <th>ID Producto</th>
                        <th>ID Categoría</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($detalles)): ?>
                        <?php foreach ($detalles as $deta): ?>
                            <tr>
                                <td><?= htmlspecialchars($deta['id_detalle_producto']) ?></td>
                                <td><?= htmlspecialchars($deta['talla']) ?></td>
                                <td><?= htmlspecialchars($deta['color']) ?></td>
                                <td>
                                    <?php if (!empty($deta['imagen'])): ?>
                                        <a href="../../<?= htmlspecialchars($deta['imagen']) ?>" target="_blank">
                                            <img src="../../<?= htmlspecialchars($deta['imagen']) ?>" width="80" height="80" alt="imagen detalle">
                                        </a>
                                    <?php else: ?>
                                        <span>Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($deta['id_producto']) ?></td>
                                <td><?= htmlspecialchars($deta['id_categoria']) ?></td>
                                <td>$<?= number_format($deta['precio'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No se encontraron detalles de productos.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAgregarDetalle" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data" action="index.php?opcion=detalle_producto">
        <input type="hidden" name="_action" value="agregar">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Detalle de Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Talla</label>
            <input class="form-control" type="text" name="talla" placeholder="Ingrese la talla del producto" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Color</label>
            <input class="form-control" type="text" name="color" placeholder="Ingrese el color del producto" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Imagen</label>
            <input class="form-control" type="file" name="imagen" accept="image/*" required>
          </div>
          <div class="mb-2">
            <label class="form-label">ID Producto</label>
            <input class="form-control" type="number" name="id_producto" placeholder="Ingrese el ID del producto" required>
          </div>
          <div class="mb-2">
            <label class="form-label">ID Categoría</label>
            <input class="form-control" type="number" name="id_categoria" placeholder="Ingrese el ID de la categoría" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Precio</label>
            <input class="form-control" type="number" step="0.01" name="precio" placeholder="Ingrese el precio" required>
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


<div class="modal fade" id="modalActualizarDetalle" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data" action="index.php?opcion=detalle_producto">
        <input type="hidden" name="_action" value="actualizar">
        <div class="modal-header">
          <h5 class="modal-title">Actualizar Detalle de Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">ID Detalle</label>
            <input class="form-control" type="number" name="id_detalle_producto" placeholder="Ingrese el ID del detalle" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Talla</label>
            <input class="form-control" type="text" name="talla" placeholder="Ingrese la talla del producto" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Color</label>
            <input class="form-control" type="text" name="color" placeholder="Ingrese el color del producto" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Imagen</label>
            <input class="form-control" type="file" name="imagen" accept="image/*">
            <small class="text-muted">Si no selecciona nada, se mantendrá la imagen actual.</small>
          </div>
          <div class="mb-2">
            <label class="form-label">ID Producto</label>
            <input class="form-control" type="number" name="id_producto" placeholder="Ingrese el ID del producto" required>
          </div>
          <div class="mb-2">
            <label class="form-label">ID Categoría</label>
            <input class="form-control" type="number" name="id_categoria" placeholder="Ingrese el ID de la categoría" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Precio</label>
            <input class="form-control" type="number" step="0.01" name="precio" placeholder="Ingrese el precio" required>
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


<div class="modal fade" id="modalEliminarDetalle" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="index.php?opcion=detalle_producto">
        <input type="hidden" name="_action" value="eliminar">
        <div class="modal-header">
          <h5 class="modal-title">Eliminar Detalle de Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">ID Detalle</label>
            <input class="form-control" type="number" name="id_detalle_producto" placeholder="Ingrese el ID del detalle" required>
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
