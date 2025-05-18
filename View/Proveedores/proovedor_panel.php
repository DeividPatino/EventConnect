<?php
session_start();
require_once '../../Model/EventoModel.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'proveedor') {
    header("Location: login.html");
    exit;
}

$model = new EventoModel();
$eventos = $model->obtenerEventosPorProveedor($_SESSION['id_usuario']);
$model->cerrarConexion();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Proveedor</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h2>
    <h3>Mis eventos publicados:</h3>

    <?php if (!empty($eventos)): ?>
        <div class="eventos-lista">
            <?php foreach ($eventos as $evento): ?>
                <div class="card-evento">
                    <h4><?php echo htmlspecialchars($evento['nombre']); ?></h4>
                    <p><strong>Categoría:</strong> <?php echo htmlspecialchars($evento['categoria']); ?></p>
                    <p><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($evento['descripcion'])); ?></p>
                    <p><strong>Ciudad:</strong> <?php echo htmlspecialchars($evento['lugar']); ?></p>
                    <p><strong>Precio:</strong> $<?php echo number_format($evento['precio'], 0, ',', '.'); ?> COP</p>

                    <?php if (!empty($evento['imagen'])): ?>
                        <img src="../../Uploads/<?php echo htmlspecialchars($evento['imagen']); ?>" alt="Imagen del evento" width="200">
                    <?php endif; ?>

                    <!-- Botones de acción -->
                    <form action="editar_evento.php" method="get" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $evento['id_evento']; ?>">
                        <input type="submit" value="Editar">
                    </form>
                    <form action="../../Controller/Eventos/eliminareventoCtrl.php" method="post" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este evento?');">
                        <input type="hidden" name="id" value="<?php echo $evento['id_evento']; ?>">
                        <input type="submit" value="Eliminar">
                    </form>
                </div>
                <hr>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No has publicado ningún evento aún.</p>
    <?php endif; ?>

    <a href="publicareventos.html">➕ Crear evento</a>
</body>
</html>