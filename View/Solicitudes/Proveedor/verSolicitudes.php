<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitudes Recibidas</title>
 </head>
<body>
  <h1>Solicitudes Recibidas</h1>

  <?php if (count($solicitudes) > 0): ?>
    <table>
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