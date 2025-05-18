<?php
session_start();
require_once '../../Model/EventoModel.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'proveedor') {
    header("Location: login.html");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID de evento no especificado.";
    exit;
}

$model = new EventoModel();
$evento = $model->obtenerEventoPorId($_GET['id']);
$model->cerrarConexion();

if (!$evento || $evento['id_proveedor'] != $_SESSION['id_usuario']) {
    echo "No tienes permiso para editar este evento.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evento</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <form action="../../Controller/Eventos/editareventoCtrl.php" method="post" class="form-publicar-evento" enctype="multipart/form-data">
        <h2>Editar Evento</h2>

        <input type="hidden" name="id_evento" value="<?= $evento['id_evento'] ?>">

        Título:<br>
        <input type="text" name="titulo" value="<?= htmlspecialchars($evento['nombre']) ?>" required><br><br>

        Descripción:<br>
        <textarea name="descripcion" rows="5" required><?= htmlspecialchars($evento['descripcion']) ?></textarea><br><br>

        Categoría:<br>
        <select name="Categoria" required>
            <option value="">-- Selecciona una categoría --</option>
            <option value="Sociales" <?= $evento['categoria'] == 'Sociales' ? 'selected' : '' ?>>Sociales</option>
            <option value="Ferias" <?= $evento['categoria'] == 'Ferias' ? 'selected' : '' ?>>Ferias</option>
            <option value="Culturales" <?= $evento['categoria'] == 'Culturales' ? 'selected' : '' ?>>Culturales</option>
        </select><br><br>

        Ciudad:<br>
        <select name="Ciudad" required>
            <option value="">-- Selecciona una ciudad --</option>
            <option value="Barranquilla" <?= $evento['lugar'] == 'Barranquilla' ? 'selected' : '' ?>>Barranquilla</option>
            <option value="Cartagena" <?= $evento['lugar'] == 'Cartagena' ? 'selected' : '' ?>>Cartagena</option>
            <option value="Santa Marta" <?= $evento['lugar'] == 'Santa Marta' ? 'selected' : '' ?>>Santa Marta</option>
        </select><br><br>

        Precio (COP):<br>
        <input type="number" name="precio" value="<?= htmlspecialchars($evento['precio']) ?>" required><br><br>

        Imagen actual:<br>
        <?php if (!empty($evento['imagen'])): ?>
            <img src="../../Uploads/<?= htmlspecialchars($evento['imagen']) ?>" width="150"><br>
        <?php endif; ?>

        Nueva imagen (opcional):<br>
        <input type="file" name="imagen"><br><br>

        <input type="submit" value="Guardar cambios">
    </form>
</body>
</html>
