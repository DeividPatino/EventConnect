<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si la sesión está activa y si las variables necesarias existen
if (!isset($_SESSION['id_usuario'])) {
    echo "No estás logueado o tu sesión ha expirado.";
    exit;
}

// Incluir el modelo
require_once '../../Model/SolicitudesModel.php';

// Crear una instancia del modelo
$solicitudesModel = new SolicitudesModel();

// Obtener las solicitudes del cliente (suponiendo que tienes el id del cliente en la sesión)
$solicitudes = $solicitudesModel->obtenerSolicitudesPorCliente($_SESSION['id_usuario']);

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Solicitudes</title>
  <link rel="stylesheet" href="../../CSS/Bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="../../CSS/style.css">
  <link rel="stylesheet" href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    body {
  background-image: url('/EventConnect/Img/background.jpg');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  background-attachment: fixed;
  color: #f5f6f8;
  overflow-x: hidden;
  font-family: 'Inter', helvetica, arial, sans-serif;
  margin: 0;
  padding: 0;
}

h2 {
  text-align: center;
  font-size: 2.5rem;
  margin: 2rem 0 3rem 0;
  color: #ff5a5f;
  font-weight: 800;
  text-shadow: 0 0 4px rgba(255, 90, 95, 0.4);
}

.container {
  width: 90%;
  max-width: 1000px;
  margin: 0 auto 3rem auto;
  background: rgba(0, 0, 0, 0.4); /* Fondo oscuro semitransparente para toda la caja */
  backdrop-filter: blur(8px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
  border-radius: 12px;
  overflow: hidden;
  padding: 2rem 2.5rem;
}

.table {
  width: 100%;
  border-collapse: collapse;
  background: rgba(255, 255, 255, 0.1); /* Fondo muy translúcido */
  color: #f5f6f8;
  font-weight: 600;
  font-size: 1rem;
  border-radius: 12px;
  overflow: hidden;
}

.table thead tr {
  background-color: rgba(255, 90, 95, 0.7);
  color: #fff;
  font-weight: 700;
  box-shadow: inset 0 -4px 8px rgba(0, 0, 0, 0.1);
}

.table thead th,
.table tbody td {
  padding: 1rem 1.25rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.table tbody tr:hover {
  background-color: rgba(53, 195, 193, 0.15);
  cursor: default;
  transition: background-color 0.3s ease;
}


.text-success {
  color: #35c3c1;
  font-weight: 700;
}

.text-muted, em {
  color: #ccc;
  font-style: italic;
}

  </style>
</head>
<body>

<header class="navbar">
    <div class="logo">EventConnect</div>
    <input type="text" class="search-bar" id="busqueda-evento" placeholder="Buscar eventos" />
    
    <nav class="nav-links">
      <!-- Proveedor -->
      <?php if (isset($_SESSION['id_usuario'])): ?>
        <?php if ($_SESSION['tipo'] === 'proveedor'): ?>
          <a href="../Proveedores/publicareventos.html" id="crear-evento" class="btn btn-outline-secondary position-relative">
            <i class="bi bi-plus-circle" style="font-size: 20px;"></i>Crear eventos</a>
         <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'proveedor'): ?>
          <a href="../../Controller/Solicitudes/VerSolicitudes.php" class="btn btn-outline-secondary position-relative">
            <i class="bi bi-card-list" style="font-size: 20px;"></i> Solicitudes
              <?php if ($solicitudesPendientes > 0): ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= $solicitudesPendientes ?>
              </span>
              <?php endif; ?>
          </a>
         <?php endif; ?>
        <?php endif; ?>
        
        <!-- Cliente -->
        <?php if (isset($_SESSION['id_usuario'])): ?>
        <?php if ($_SESSION['tipo'] === 'cliente'): ?>
          <a href="../../Controller/SolicitudesCliente/versolicitudescliente.php" class="btn btn-outline-secondary position-relative">
            <i class="bi bi-card-list" style="font-size: 20px;"></i> Mis Solicitudes
          </a>
        <?php endif; ?>
        <?php endif; ?>
        
        <!-- Todo -->
        <a href="../Proveedores/proovedor_panel.php" class="btn btn-outline-secondary position-relative">
          <i class="bi bi-question-circle" style="font-size: 20px;"></i> Centro de ayuda</a>
        <a href="/EventConnect/View/editarperfil.php" class="btn btn-outline-secondary position-relative">
          <i class="bi bi-person-circle" style="font-size: 20px;"></i> <?php echo htmlspecialchars($_SESSION['nombre']); ?></a>
        <a href="../../Controller/CerrarsesionCtrl.php" class="btn btn-outline-secondary position-relative">
          <i class="bi bi-door-open" style="font-size: 20px;"></i>Cerrar sesion</a>
      <?php else: ?>
        <a href="#">Centro de ayuda</a>
        <a href="login.html">Iniciar sesión</a>
        <a href="Registro.html">Registrarse</a>
      <?php endif; ?>
    </nav>
  </header><br>

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
  <?php if (isset($solicitudes) && is_array($solicitudes)): ?>
    <?php foreach ($solicitudes as $s): ?>
      <tr>
        <td><?= htmlspecialchars($s['nombre_evento']) ?></td>
        <td><?= htmlspecialchars($s['mensaje']) ?></td>
        <td><?= htmlspecialchars($s['fecha_solicitud']) ?></td>
        <td><?= number_format($s['precio'], 0, ',', '.') ?> COP</td>
        <td><?= htmlspecialchars($s['estado']) ?></td>
        <td>
          <?php if ($s['estado'] === 'aceptada' && !$s['pagada']): ?>
            <a href="../../Controller/SolicitudesCliente/pagarCtrl.php?id=<?= $s['id'] ?>&precio=<?= $s['precio'] ?>" class="btn btn-success btn-sm">
              <i class="bi bi-currency-dollar" style="font-size: 16px;"></i>
            </a>
          <?php elseif ($s['pagada']): ?>
            <span class="text-success">Pagada</span>
          <?php else: ?>
            <span class="text-muted">En espera</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="6">No tienes solicitudes pendientes.</td></tr>
  <?php endif; ?>
</tbody>
  </table>
</div>
</body>
</html>