<?php
require_once '../Model/EventoModel.php';
session_start();

if (!isset($_GET['id'])) {
    echo "Evento no especificado.";
    exit;
}

$model = new EventoModel();
$evento = $model->obtenerEventoPorId($_GET['id']);
$model->cerrarConexion();

if (!$evento) {
    echo "El evento no existe.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($evento['nombre']) ?> | EventConnect</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>

<header class="navbar">
    <div class="logo">EventConnect</div>
    <input type="text" class="search-bar" placeholder="Buscar eventos" />
    
    <nav class="nav-links">
      <?php if (isset($_SESSION['id_usuario'])): ?>
        <?php if ($_SESSION['tipo'] === 'proveedor'): ?>
          <a href="../View/Proveedores/publicareventos.html" id="crear-evento">Crear eventos</a>
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

<main class="evento-detalle">
    <h1><?= htmlspecialchars($evento['nombre']) ?></h1>
    <img src="../Uploads/<?= htmlspecialchars($evento['imagen']) ?>" alt="Imagen del evento" width="400">
    
    <p><strong>Categoría:</strong> <?= htmlspecialchars($evento['categoria']) ?></p>
    <p><strong>Ciudad:</strong> <?= htmlspecialchars($evento['lugar']) ?></p>
    <p><strong>Precio:</strong> $<?= number_format($evento['precio'], 0, ',', '.') ?> COP</p>
    
    <h3>Descripción del servicio:</h3>
    <p><?= nl2br(htmlspecialchars($evento['descripcion'])) ?></p>

    <?php if (isset($_SESSION['id_usuario']) && $_SESSION['tipo'] === 'cliente'): ?>
     <form action="../Controller/Solicitudes/SolicitudesCtrl.php" method="POST" class="solicitud-form">
       <input type="hidden" name="id_evento" value="<?= $evento['id_evento'] ?>">
       <label for="mensaje">Mensaje para el proveedor:</label>
       <textarea name="mensaje" id="mensaje" required placeholder="Escribe tu solicitud o requerimientos especiales..."></textarea>
       <button type="submit">Solicitar servicio</button>
     </form>
    <?php else: ?>
        <p><em>Inicia sesión como cliente para solicitar este servicio.</em></p>
    <?php endif; ?>

</main>

</body>
</html>
