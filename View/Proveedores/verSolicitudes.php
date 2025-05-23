<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Solicitudes recibidas</title>
  <link rel="stylesheet" href="../../CSS/Bootstrap/bootstrap.min.css">
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

  <header class="navbar">
    <div class="logo">EventConnect</div>
    <input type="text" class="search-bar" id="busqueda-evento" placeholder="Buscar eventos" />
    
    <nav class="nav-links">
      <!-- Proveedor -->
      <?php if (isset($_SESSION['id_usuario'])): ?>
        <?php if ($_SESSION['tipo'] === 'proveedor'): ?>
          <a href="/EventConnect/View/Proveedores/publicareventos.php" id="crear-evento" class="btn btn-outline-secondary position-relative">
            <i class="bi bi-plus-circle" style="font-size: 20px;"></i> Crear eventos</a>
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
          <a href="../Controller/SolicitudesCliente/versolicitudescliente.php" class="btn btn-outline-secondary position-relative">
            <i class="bi bi-card-list" style="font-size: 20px;"></i> Mis Solicitudes
          </a>
        <?php endif; ?>
        <?php endif; ?>
        
        <!-- Todo -->
        <a href="#" class="btn btn-outline-secondary position-relative">
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
  </header>

  <h1 class="section-title">Solicitudes Recibidas</h1>

  <?php if (count($solicitudes) > 0): ?>
    <table class="event-table">
      <thead>
        <tr>
          <th>Cliente</th>
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
            <td><?= htmlspecialchars($s['nombre_cliente']) ?></td>
            <td><?= htmlspecialchars($s['nombre_evento']) ?></td>
            <td><?= htmlspecialchars($s['mensaje']) ?></td>
            <td><?= htmlspecialchars($s['fecha_solicitud']) ?></td>
            <td><?= number_format($s['precio'], 0, ',', '.') ?> COP</td>
            <td><?= ucfirst($s['estado']) ?></td>
            <td>
              <?php if ($s['estado'] === 'pendiente'): ?>
                <form method="POST" action="../../Controller/Solicitudes/CambiarestadoCtrl.php" style="display:inline;">
                 <input type="hidden" name="id_solicitud" value="<?= $s['id'] ?>">
                 <input type="hidden" name="nuevo_estado" value="aceptada">
                 <button type="submit" class="boton boton-aceptar" aria-label="Aceptar">
                    <i class="bi bi-check-square-fill" style="font-size: 16px;"></i>
                 </button>
                 
                </form>
                <form method="POST" action="../../Controller/Solicitudes/CambiarestadoCtrl.php" style="display:inline;">
                  <input type="hidden" name="id_solicitud" value="<?= $s['id'] ?>">
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
