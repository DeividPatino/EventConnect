<?php
session_start();
require_once '../Model/RegistroModel.php';
require_once '../Model/EventoModel.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.html");
    exit;
}

$registroModel = new RegistroModel();
$usuario = $registroModel->obtenerUsuarioPorId($_SESSION['id_usuario']);

$eventos = [];
if ($_SESSION['tipo'] === 'proveedor') {
    $eventoModel = new EventoModel();
    $eventos = $eventoModel->obtenerEventosPorProveedor($_SESSION['id_usuario']);
    $eventoModel->cerrarConexion();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perfil de Usuario</title>
  <link rel="stylesheet" href="../CSS/style.css">
  <link rel="stylesheet" href="../CSS/Bootstrap/bootstrap.min.css">
  <style>
    /* Contenedor principal */
.container {
  max-width: 1100px;
  margin: 40px auto;
  background: white;
  padding: 30px 40px;
  border-radius: 12px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

/* Títulos */
h2 {
  color: #222;
  font-weight: 700;
  margin-bottom: 25px;
}

h3 {
  color: #0d6efd;
  font-weight: 700;
  margin-bottom: 20px;
}

/* Navegación perfil */
.nav-perfil {
  display: flex;
  gap: 20px;
  margin-bottom: 30px;
  border-bottom: 2px solid #ddd;
  padding-bottom: 10px;
}

.nav-perfil a {
  text-decoration: none;
  color: #555;
  font-weight: 600;
  padding: 10px 18px;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease, color 0.3s ease;
  user-select: none;
}

.nav-perfil a.active,
.nav-perfil a:hover {
  background-color: #0d6efd;
  color: white;
  box-shadow: 0 4px 10px rgb(13 110 253 / 0.3);
}

/* Secciones */
.section {
  display: none;
  animation: fadeIn 0.3s ease forwards;
}

.section.active {
  display: block;
}

/* Animación simple */
@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity: 1;}
}

/* Formularios */
form {
  max-width: 500px;
}

form label {
  display: block;
  font-weight: 600;
  margin-bottom: 6px;
  color: #0d6efd;
}

form input[type="text"],
form input[type="email"],
form input[type="tel"],
form input[type="password"] {
  width: 100%;
  padding: 10px 14px;
  border-radius: 8px;
  border: 1.5px solid #ccc;
  font-size: 1rem;
  margin-bottom: 18px;
  transition: border-color 0.3s ease;
}

form input[type="text"]:focus,
form input[type="email"]:focus,
form input[type="tel"]:focus,
form input[type="password"]:focus {
  border-color: #0d6efd;
  outline: none;
  box-shadow: 0 0 8px rgb(13 110 253 / 0.4);
}

/* Botón guardar */
form button[type="submit"] {
  background-color: #0d6efd;
  color: white;
  font-weight: 700;
  padding: 12px 30px;
  border: none;
  border-radius: 30px;
  cursor: pointer;
  font-size: 1.1rem;
  transition: background-color 0.3s ease;
}

form button[type="submit"]:hover {
  background-color: #0b5ed7;
}

/* Tarjetas eventos */
.row {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.col-sm-6, .col-md-4, .col-lg-3 {
  flex: 1 1 calc(25% - 20px);
  box-sizing: border-box;
  min-width: 220px;
}

.card {
  background: #fff;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 5px 18px rgba(0,0,0,0.07);
  display: flex;
  flex-direction: column;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 8px 30px rgba(13,110,253,0.3);
}

.card-img-top {
  width: 100%;
  height: 150px;
  object-fit: cover;
}

.card-body {
  padding: 15px 18px;
  flex-grow: 1;
}

.card-title {
  font-weight: 700;
  font-size: 1.1rem;
  margin-bottom: 10px;
  color: #0d6efd;
}

.card-body p {
  margin: 4px 0;
  font-size: 0.95rem;
  color: #444;
}

.card-footer {
  padding: 10px 15px;
  background-color: #f9f9f9;
  display: flex;
  justify-content: space-between;
  gap: 10px;
}

.card-footer .btn {
  font-size: 0.9rem;
  padding: 6px 12px;
  border-radius: 8px;
  transition: background-color 0.3s ease;
}

/* Botones editar y eliminar */
.btn-outline-warning {
  color: #ffc107;
  border-color: #ffc107;
}

.btn-outline-warning:hover {
  background-color: #ffc107;
  color: #fff;
}

.btn-outline-danger {
  color: #dc3545;
  border-color: #dc3545;
}

.btn-outline-danger:hover {
  background-color: #dc3545;
  color: #fff;
}

/* Responsive */
@media (max-width: 991px) {
  .col-sm-6, .col-md-4, .col-lg-3 {
    flex: 1 1 calc(50% - 20px);
  }
}

@media (max-width: 575px) {
  .col-sm-6, .col-md-4, .col-lg-3 {
    flex: 1 1 100%;
  }

  .nav-perfil {
    flex-direction: column;
    gap: 12px;
  }
}
  </style>
</head>
<body class="p-4">
  <div class="container">
    <h2>Bienvenido, <?= htmlspecialchars($_SESSION['nombre']); ?></h2>

    <nav class="nav-perfil">
        <a class="nav-link active" data-seccion="info">Información</a>
        <a class="nav-link" data-seccion="editar">Editar Perfil</a>
        <?php if ($_SESSION['tipo'] === 'proveedor'): ?>
            <a class="nav-link" data-seccion="eventos">Mis Eventos</a>
        <?php endif; ?>
    </nav>

    <!-- Sección Información -->
    <div id="info" class="section active">
      <h3>Información de Usuario</h3>
      <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']) ?> <?= htmlspecialchars($usuario['apellido']) ?></p>
      <p><strong>Correo:</strong> <?= htmlspecialchars($usuario['correo']) ?></p>
      <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono']) ?></p>
      <p><strong>Tipo:</strong> <?= htmlspecialchars($_SESSION['tipo']) ?></p>
    </div>

    <!-- Sección Editar -->
    <div id="editar" class="section">
      <h3>Editar Perfil</h3>
      <form action="../Controller/editarperfilCtrl.php" method="POST">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
        <div class="mb-3">
          <label>Nombre:</label>
          <input type="text" name="ur" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>">
        </div>
        <div class="mb-3">
          <label>Apellido:</label>
          <input type="text" name="ar" class="form-control" value="<?= htmlspecialchars($usuario['apellido']) ?>">
        </div>
        <div class="mb-3">
          <label>Correo:</label>
          <input type="email" name="cor" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>">
        </div>
        <div class="mb-3">
          <label>Teléfono:</label>
          <input type="tel" name="tel" class="form-control" value="<?= htmlspecialchars($usuario['telefono']) ?>">
        </div>
        <div class="mb-3">
          <label>Contraseña nueva (opcional):</label>
          <input type="password" name="cont" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
      </form>
    </div>

    <!-- Sección Eventos -->
    <?php if ($_SESSION['tipo'] === 'proveedor'): ?>
    <div id="eventos" class="section">
      <h3 class="mt-4">Mis Eventos</h3>
      <?php if (count($eventos) > 0): ?>
        <div class="row mt-4">
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
                  <a href="../View/Proveedores/editar_evento.php?id=<?= $e['id_evento'] ?>" class="btn btn-sm btn-outline-warning">Editar</a>
                  <form action="../Controller/Eventos/eliminareventoCtrl.php" method="post" onsubmit="return confirm('¿Eliminar este evento?');">
                    <input type="hidden" name="id" value="<?= $e['id_evento'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>No tienes eventos publicados aún.</p>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.nav-perfil .nav-link');
    const sections = document.querySelectorAll('.section');

    navLinks.forEach(link => {
      link.addEventListener('click', () => {
        const targetId = link.getAttribute('data-seccion');

        // Quitar 'active' a todos
        navLinks.forEach(l => l.classList.remove('active'));
        sections.forEach(sec => sec.classList.remove('active'));

        // Activar sección correspondiente
        link.classList.add('active');
        const targetSection = document.getElementById(targetId);
        if (targetSection) {
          targetSection.classList.add('active');
        }
      });
    });
  });
</script>

</body>
</html>