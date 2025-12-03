<?php
// protección para evitar warnings si variables no están definidas
$promociones = $promociones ?? [];
$mensaje = $mensaje ?? "";
?>

<div class="container mt-4">
    <?php if (!empty($mensaje)) echo $mensaje; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Promociones</h5>
            <div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarPromocion">
                    <i class="fas fa-plus"></i> Agregar
                </button>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalActualizarPromocion">
                    <i class="fas fa-edit"></i> Actualizar
                </button>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarPromocion">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <?php if (!empty($promociones)): ?>
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Descuento (%)</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>ID Producto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promociones as $promo): ?>
                            <tr>
                                <td><?= htmlspecialchars($promo['id_promocion']) ?></td>
                                <td><?= htmlspecialchars($promo['descripcion']) ?></td>
                                <td><?= htmlspecialchars($promo['descuento']) ?></td>
                                <td><?= htmlspecialchars($promo['fecha_inicio']) ?></td>
                                <td><?= htmlspecialchars($promo['fecha_fin']) ?></td>
                                <td><?= htmlspecialchars($promo['id_producto']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-danger mt-3">No se encontraron promociones.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAgregarPromocion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="index.php?opcion=promocion">
        <input type="hidden" name="_action" value="agregar">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Promoción</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Descripción</label>
            <input class="form-control" type="text" name="descripcion" placeholder="Ej: 2x1 en camisetas" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Descuento (%)</label>
            <input class="form-control" type="number" step="0.01" name="descuento" placeholder="Ej: 20.5" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Fecha Inicio</label>
            <input class="form-control" type="date" name="fecha_inicio" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Fecha Fin</label>
            <input class="form-control" type="date" name="fecha_fin" required>
          </div>
          <div class="mb-2">
            <label class="form-label">ID Producto</label>
            <input class="form-control" type="number" name="id_producto" placeholder="Ej: 15" required>
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


<div class="modal fade" id="modalActualizarPromocion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="index.php?opcion=promocion">
        <input type="hidden" name="_action" value="actualizar">
        <div class="modal-header">
          <h5 class="modal-title">Actualizar Promoción</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">ID Promoción</label>
            <input class="form-control" type="number" name="id_promocion" placeholder="Ej: 8" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Descripción</label>
            <input class="form-control" type="text" name="descripcion" placeholder="Ej: Descuento en jeans" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Descuento (%)</label>
            <input class="form-control" type="number" step="0.01" name="descuento" placeholder="Ej: 15" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Fecha Inicio</label>
            <input class="form-control" type="date" name="fecha_inicio" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Fecha Fin</label>
            <input class="form-control" type="date" name="fecha_fin" required>
          </div>
          <div class="mb-2">
            <label class="form-label">ID Producto</label>
            <input class="form-control" type="number" name="id_producto" placeholder="Ej: 22" required>
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


<div class="modal fade" id="modalEliminarPromocion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="index.php?opcion=promocion">
        <input type="hidden" name="_action" value="eliminar">
        <div class="modal-header">
          <h5 class="modal-title">Eliminar Promoción</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">ID Promoción</label>
            <input class="form-control" type="number" name="id_promocion" placeholder="Ej: 5" required>
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
