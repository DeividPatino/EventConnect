<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Solicitudes recibidas</title>
  <link rel="stylesheet" href="../../CSS/style.css">
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
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No tienes solicitudes por ahora.</p>
  <?php endif; ?>
</body>
</html>
