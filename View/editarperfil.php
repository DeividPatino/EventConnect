<?php
session_start();
require_once '../Model/RegistroModel.php';
require_once '../Model/EventoModel.php';

// Validación de sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.html");
    exit;
}

// Obtener datos del usuario
$registroModel = new RegistroModel();
$usuario = $registroModel->obtenerUsuarioPorId($_SESSION['id_usuario']);

// Solo si es proveedor, obtener eventos
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
  <title>Editar Perfil</title>
  <link rel="stylesheet" href="../CSS/Bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="../CSS/style.css" />
</head>
<body class="p-4">
  <div class="container">
    <h2>Editar Perfil</h2>
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
        <label>Contraseña nueva (opcional):</label>
        <input type="password" name="cont" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </form>

    <?php if ($_SESSION['tipo'] === 'proveedor'): ?>
      <hr>
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
    <?php endif; ?>
  </div>
</body>
</html>