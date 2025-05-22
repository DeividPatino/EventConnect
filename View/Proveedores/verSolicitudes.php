<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Solicitudes recibidas</title>
  <link rel="stylesheet" href="../../CSS/style.css">
  <link rel="stylesheet" href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css">
  <style>
  .logo a {
    text-decoration: none;
    color: #ff5a5f;
    font-size: 24px;
    font-weight: bold;
  }
</style>
</head>
<body>
  <h1 class="section-title">Solicitudes Recibidas</h1>

  <?php if (count($solicitudes) > 0): ?>
    <table class="event-table">
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Evento</th>
          <th>Mensaje</th>
          <th>Fecha</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($solicitudes as $sol): ?>
          <tr>
            <td><?= htmlspecialchars($sol['nombre_cliente']) ?></td>
            <td><?= htmlspecialchars($sol['nombre_evento']) ?></td>
            <td><?= htmlspecialchars($sol['mensaje']) ?></td>
            <td><?= $sol['fecha_solicitud'] ?></td>
            <td><?= ucfirst($sol['estado']) ?></td>
            <td>
              <?php if ($sol['estado'] === 'pendiente'): ?>
                <form method="POST" action="../../Controller/Solicitudes/CambiarestadoCtrl.php" style="display:inline;">
                 <input type="hidden" name="id_solicitud" value="<?= $sol['id'] ?>">
                 <input type="hidden" name="nuevo_estado" value="aceptada">
                 <button type="submit" class="boton boton-aceptar" aria-label="Aceptar">
                    <i class="bi bi-check-square-fill" style="font-size: 16px;"></i>
                 </button>
                 
                </form>
                <form method="POST" action="../../Controller/Solicitudes/CambiarestadoCtrl.php" style="display:inline;">
                  <input type="hidden" name="id_solicitud" value="<?= $sol['id'] ?>">
                  <input type="hidden" name="nuevo_estado" value="rechazada">
                  <button type="submit" class="boton boton-rechazar" aria-label="Rechazar">
                    <i class="bi bi-check-square-fill" style="font-size: 16px;"></i>
                  </button>
                </form>

              <?php else: ?>
                <em>No disponible</em>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No tienes solicitudes por ahora.</p>
  <?php endif; ?>
</body>
</html>
