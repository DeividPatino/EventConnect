<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Solicitudes</title>
  <link rel="stylesheet" href="../../CSS/style.css">
  <link rel="stylesheet" href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f8f9fa;
  color: #212529;
  margin: 0;
  padding: 0;
}

h2 {
  font-size: 2rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #333;
}

.container {
  max-width: 1100px;
  background-color: #ffffff;
  padding: 30px;
  margin: auto;
  border-radius: 12px;
  box-shadow: 0 0 10px rgba(0,0,0,0.05);
}

.table {
  border-collapse: collapse;
  width: 100%;
  margin-top: 10px;
}

.table thead {
  background-color: #343a40;
  color: white;
}

.table th,
.table td {
  padding: 14px 16px;
  vertical-align: middle;
  text-align: left;
}

.table tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}

.table tbody tr:hover {
  background-color: #e9ecef;
}

.btn {
  font-size: 0.95rem;
  padding: 8px 12px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-success {
  background-color: #28a745;
  border-color: #28a745;
  color: white;
  transition: background-color 0.3s;
}

.btn-success:hover {
  background-color: #218838;
}

.text-muted {
  font-style: italic;
}

.text-success {
  font-weight: bold;
  color: #28a745;
}
  </style>
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
        <th>Precio</th>
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
          <td><?= number_format($s['precio'], 0, ',', '.') ?> COP</td>
          <td><?= htmlspecialchars($s['estado']) ?></td>
          <td>
            <?php if ($s['estado'] === 'aceptada' && !$s['pagada']): ?>
            <a href="../../Controller/SolicitudesCliente/pagarCtrl.php?id=<?= $s['id'] ?>&precio=<?= $s['precio'] ?>" class="btn btn-success btn-sm"> <i class="bi bi-currency-dollar" style="font-size: 16px;"></i></a>
          <?php elseif ($s['pagada']): ?>
            <span class="text-success">Pagada</span>
          <?php else: ?>
            <span class="text-muted">En espera</span>
          <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>