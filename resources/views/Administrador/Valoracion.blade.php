<?php

$valoraciones = $valoraciones ?? [];
$mensaje = $mensaje ?? "";
?>

<div class="container mt-4">
    <?php if (!empty($mensaje)) echo $mensaje; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Valoraciones</h5>
            <div>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarValoracion">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <?php if (!empty($valoraciones)): ?>
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>ID Cliente</th>
                            <th>ID Producto</th>
                            <th>Calificación</th>
                            <th>Comentario</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($valoraciones as $valora): ?>
                            <tr>
                                <td><?= htmlspecialchars($valora['id_valoracion']) ?></td>
                                <td><?= htmlspecialchars($valora['id_cliente']) ?></td>
                                <td><?= htmlspecialchars($valora['id_producto']) ?></td>
                                <td><?= htmlspecialchars($valora['calificacion']) ?></td>
                                <td><?= htmlspecialchars($valora['comentario']) ?></td>
                                <td><?= htmlspecialchars($valora['fecha_valoracion']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-danger mt-3">No se encontraron valoraciones.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEliminarValoracion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="index.php?opcion=valoracion">
        <input type="hidden" name="_action" value="eliminar">
        <div class="modal-header">
          <h5 class="modal-title">Eliminar Valoración</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">ID Valoración</label>
            <input class="form-control" type="number" name="id_valoracion" placeholder="Ingrese el ID de la valoración" required>
          </div>
          <p class="text-danger"> Esta acción no se puede deshacer.</p>
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
