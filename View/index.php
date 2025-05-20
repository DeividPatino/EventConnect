<?php
session_start();
require_once '../Model/EventoModel.php';
$model = new EventoModel();
$eventos = $model->obtenerTodosLosEventos(); // asegúrate de tener este método en EventoModel
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
  <script src="utils.js" defer></script>
</head>
<body>
  
  <header class="navbar">
    <div class="logo">EventConnect</div>
    <input type="text" class="search-bar" placeholder="Buscar eventos" />
    
    <nav class="nav-links">
      <?php if (isset($_SESSION['id_usuario'])): ?>
        <?php if ($_SESSION['tipo'] === 'proveedor'): ?>
          <a href="../View/Proveedores/publicareventos.html" id="crear-evento">Crear eventos</a>
         <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'proveedor'): ?>
  <a href="../../../Controller/VerSolicitudes.php">Ver solicitudes</a>
<?php endif; ?>
        <?php endif; ?>
        <a href="../View/Proveedores/proovedor_panel.php">Centro de ayuda</a>
        <a href="#">Mis entradas</a>
        <a href="perfil.php">Editar perfil (<?= htmlspecialchars($_SESSION['nombre']) ?>)</a>
        <a href="../Controller/CerrarsesionCtrl.php">Cerrar sesión</a>
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
        <h1><span class="highlight">Haz lo que</span><br>te apasiona</h1>
      </div>
      <img src="https://www.svgrepo.com/show/379005/event.svg" alt="Evento destacado" class="hero-image" />
    </div>

    <section class="categories">
      <div class="category" data-filter="music">🎵 Música</div>
      <div class="category" data-filter="nightlife">🌃 Vida nocturna</div>
      <div class="category" data-filter="art">🎭 Artes</div>
      <div class="category" data-filter="vacation">✈️ Vacaciones</div>
      <div class="category" data-filter="hobbies">🎮 Aficiones</div>
      <div class="category" data-filter="business">💼 Negocios</div>
      <div class="category" data-filter="gastronomy">🍔 Gastronomía</div>
    </section>

    <section class="filters">
      <div class="location-selector">
        <label for="city">Mirando eventos en:</label>
        <select name="city" id="city">
          <option value="cartagena">Cartagena</option>
          <option value="bogota">Bogotá</option>
          <option value="medellin">Medellín</option>
          <option value="cali">Cali</option>
          <option value="barranquilla">Barranquilla</option>
          <option value="bucaramanga">Bucaramanga</option>
          <option value="manizales">Manizales</option>
          <option value="pereira">Pereira</option>
          <option value="cartago">Cartago</option>
          <option value="santa_marta">Santa Marta</option>
        </select>
      </div>

        <div class="container mt-4">
  <div class="row">
    <?php foreach ($eventos as $e): ?>
      <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
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
  <script src="script.js" defer></script>
</body>
</html>