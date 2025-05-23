<?php
session_start();
require_once '../Model/EventoModel.php';
require_once '../Model/SolicitudesModel.php';

$model = new EventoModel();
$eventos = $model->obtenerTodosLosEventos();


$solicitudesPendientes = (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'proveedor') ? (new SolicitudesModel())->contarSolicitudesPendientes($_SESSION['id_usuario']) : 0;

$ciudad = $_GET['ciudad'] ?? 'todas';
$categoria = $_GET['categoria'] ?? 'todas';

if ($ciudad === 'todas' && $categoria === 'todas') {
    $eventos = $model->obtenerTodosLosEventos();
} elseif ($ciudad !== 'todas' && $categoria === 'todas') {
    $eventos = $model->obtenerEventosPorCiudad($ciudad);
} elseif ($ciudad === 'todas' && $categoria !== 'todas') {
    $eventos = $model->obtenerEventosPorCategoria($categoria);
} elseif ($ciudad !== 'todas' && $categoria !== 'todas') {
    $eventos = $model->obtenerEventosPorCiudadYCategoria($ciudad, $categoria);
}

$model->cerrarConexion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EventConnect</title>
  <link rel="stylesheet" href="../CSS/Bootstrap/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../CSS/style.css" />
  <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css">
  <script src="../JS/utils.js" defer></script>
</head>
<body>
  
  <header class="navbar">
    <div class="logo">EventConnect</div>
    <input type="text" class="search-bar" id="busqueda-evento" placeholder="Buscar eventos" />
    
    <nav class="nav-links">
      <!-- Proveedor -->
      <?php if (isset($_SESSION['id_usuario'])): ?>
        <?php if ($_SESSION['tipo'] === 'proveedor'): ?>
          <a href="../View/Proveedores/publicareventos.php" id="crear-evento" class="btn btn-outline-secondary position-relative">
            <i class="bi bi-plus-circle" style="font-size: 20px;"></i>Crear eventos</a>
         <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'proveedor'): ?>
          <a href="../Controller/Solicitudes/VerSolicitudes.php" class="btn btn-outline-secondary position-relative">
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
        <a href="../View/Proveedores/proovedor_panel.php" class="btn btn-outline-secondary position-relative">
          <i class="bi bi-question-circle" style="font-size: 20px;"></i> Centro de ayuda</a>
        <a href="editarperfil.php" class="btn btn-outline-secondary position-relative">
          <i class="bi bi-person-circle" style="font-size: 20px;"></i> <?php echo htmlspecialchars($_SESSION['nombre']); ?></a>
        <a href="../Controller/CerrarsesionCtrl.php" class="btn btn-outline-secondary position-relative">
          <i class="bi bi-door-open" style="font-size: 20px;"></i>Cerrar sesion</a>
      <?php else: ?>
        <a href="#">Centro de ayuda</a>
        <a href="login.html">Iniciar sesión</a>
        <a href="Registro.html">Registrarse</a>
      <?php endif; ?>
    </nav>
  </header>

  <main class="main-section">
    <div class="hero">
      <div class="hero-text">
        <h1><span class="highlight">EL FUTURO DE LOS EVENTOS</span><br>COMIENZA AQUÍ</h1>
      </div>
    </div>

    <section class="categories">
      <div class="category" data-filter="todas">📋 Todas</div>
      <div class="category" data-filter="culturales">👥 Culturales</div>
      <div class="category" data-filter="sociales">🥂 Sociales</div>
      <div class="category" data-filter="ferias">🎡 Ferias</div>
    </section>

    <section class="filters">
      <div class="location-selector">
        <label for="city">Mirando en:</label>
        <?php $ciudadSeleccionada = $_GET['ciudad'] ?? ''; ?>
        <select name="city" id="city">
          <option value="todas" <?= $ciudadSeleccionada == 'todas' ? 'selected' : '' ?>>Todas las ciudades</option>
          <option value="Cartagena" <?= $ciudadSeleccionada == 'Cartagena' ? 'selected' : '' ?>>Cartagena</option>
          <option value="Barranquilla" <?= $ciudadSeleccionada == 'Barranquilla' ? 'selected' : '' ?>>Barranquilla</option>
          <option value="Santa Marta" <?= $ciudadSeleccionada == 'Santa Marta' ? 'selected' : '' ?>>Santa Marta</option>

          <!-- ... demás opciones ... -->
        </select>

      </div>
  </main>

   <main class="main-section">
    <div class="container mt-4">
    <div class="row" id="contenedor-eventos">
      <?php foreach ($eventos as $e): ?>
       <div class="col-sm-6 col-md-4 col-lg-3 mb-4 evento-item">
         <div class="card h-100 shadow-sm">
           <?php if (!empty($e['imagen'])): ?>
             <img src="../Uploads/<?= htmlspecialchars($e['imagen']) ?>" class="card-img-top" style="height: 150px; object-fit: cover;" alt="Imagen del evento">
            <?php endif; ?>
            <div class="card-body p-2">
              <h6 class="card-title"><?= htmlspecialchars($e['nombre']) ?></h6>
             <p class="mb-1"><strong>Ciudad:</strong> <?= htmlspecialchars($e['lugar']) ?></p>
              <p class="mb-1"><strong>Categoría:</strong> <?= htmlspecialchars($e['categoria']) ?></p>
             <p class="mb-1"><strong>Precio:</strong> <?= number_format($e['precio'], 0, ',', '.') ?> COP</p>
            </div>
            <div class="card-footer p-2 d-flex justify-content-between">
              <a href="detalleseventos.php?id=<?= $e['id_evento'] ?>" class="btn btn-sm btn-outline-primary">Ver</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    </div>
   </main>
        
  <footer class="footer">
    <p>&copy; 2025 EventConnect. Todos los derechos reservados.</p>
  </footer>
  <script src="../JS/Bootstrap/bootstrap.bundle.min.js"></script>
  <script src="../JS/script.js" defer></script>
</body>
</html>