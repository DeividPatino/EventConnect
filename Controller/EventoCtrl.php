<?php
session_start();
require_once '../Model/EventoModel.php';

$model = new EventoModel();

// Validar que sea un proveedor autenticado
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'proveedor') {
    echo "<h4>Acceso denegado</h4>";
    exit;
}

// Obtener datos del formulario
$nombre = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$categoria = $_POST['Categoria'] ?? '';
$lugar = $_POST['ciudad'] ?? '';
$precio = $_POST['precio'] ?? '';
$id_proveedor = $_SESSION['id_usuario'] ?? 0;

// Manejo de imagen
$imagen = null;
if (!empty($_FILES['imagen']['name'])) {
    $nombreImagen = basename($_FILES['imagen']['name']);
    $rutaDestino = "../uploads/" . $nombreImagen;

    if (!file_exists('../uploads')) {
        mkdir('../uploads', 0777, true);
    }

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
        $imagen = $nombreImagen;
    }
}

// Publicar el servicio
if ($model->publicarevento($nombre, $descripcion, $categoria, $lugar, $precio, $imagen, $id_proveedor)) {
    header("Location: ../View/proveedor_panel.php?mensaje=publicado");
} else {
    echo "<h4>Error al publicar el servicio</h4>";
}

$model->cerrarConexion();
?>
