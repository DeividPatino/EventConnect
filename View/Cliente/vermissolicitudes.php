<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Solicitudes</title>
  <link rel="stylesheet" href="../../CSS/style.css">
  <link rel="stylesheet" href="../../CSS/Bootstrap/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
  <h2>Mis Solicitudes</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Evento</th>
        <th>Mensaje</th>
        <th>Fecha</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($solicitudes as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s['nombre_evento']) ?></td>
          <td><?= htmlspecialchars($s['mensaje']) ?></td>
          <td><?= htmlspecialchars($s['fecha_solicitud']) ?></td>
          <td><?= htmlspecialchars($s['estado']) ?></td>
          <td>
            <?php if ($s['estado'] === 'pendiente'): ?>
              <form action="../../Controller/SolicitudesCliente/CancelarSolicitud.php" method="POST" style="display:inline;">
                <input type="hidden" name="id_solicitud" value="<?= $s['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
              </form>
            <?php else: ?>
              <span class="text-muted">No disponible</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>