<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EventConnect</title>
  <link rel="stylesheet" href="../CSS/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
  <script src="auth.js" defer></script>
  <script src="utils.js" defer></script>
</head>
<body>
  
  <header class="navbar">
    <div class="logo">EventConnect</div>
    <input type="text" class="search-bar" placeholder="Buscar eventos" />
    
    <nav class="nav-links">
      <?php if (isset($_SESSION['id_usuario'])): ?>
        <?php if ($_SESSION['tipo'] === 'proveedor'): ?>
          <a href="publicareventos.html" id="crear-evento">Crear eventos</a>
        <?php endif; ?>
        <a href="#">Centro de ayuda</a>
        <a href="#">Mis entradas</a>
        <a href="perfil.php">Editar perfil (<?= htmlspecialchars($_SESSION['nombre']) ?>)</a>
        <a href="../Controller/CerrarsesionCtrl.php">Cerrar sesión</a>
      <?php else: ?>
        <a href="publicareventos.html" id="crear-evento">Crear eventos</a>
        <a href="#">Centro de ayuda</a>
        <a href="#">Mis entradas</a>
        <a href="#">Editar perfil</a>
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
      <div class="filter-buttons">
        <button data-filter="all">Todos</button>
        <button data-filter="for-you">Para ti</button>
        <button data-filter="today">Hoy</button>
        <button data-filter="weekend">Este fin de semana</button>
        <button data-filter="free">Gratis</button>
      </div>
    </section>

    <section id="event-container" class="event-container">
      <!-- Aquí se cargarán los eventos dinámicamente con JavaScript -->
    </section>
  </main>

  <footer class="footer">
    <p>&copy; 2025 EventConnect. Todos los derechos reservados.</p>
  </footer>

  <script src="script.js" defer></script>
</body>
</html>