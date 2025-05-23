<?php

require_once '../../Model/EventoModel.php';
session_start();

$model = new EventoModel();

$id_evento = $_POST['id_evento'];
$nombre = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$categoria = $_POST['Categoria'];
$lugar = $_POST['ciudad'];
$precio = $_POST['precio'];

$imagen_nueva = '';
$evento_actual = $model->obtenerEventoPorId($id_evento);

// Verifica si se subió una nueva imagen
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
    $nombre_archivo = basename($_FILES['imagen']['name']);
    $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
    $permitidos = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($extension, $permitidos)) {
        $nombre_final = uniqid() . '.' . $extension;
        $ruta_destino = '../../Uploads/' . $nombre_final;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            // Elimina la imagen anterior si existe
            if (!empty($evento_actual['imagen'])) {
                $ruta_anterior = '../../Uploads/' . $evento_actual['imagen'];
                if (file_exists($ruta_anterior)) {
                    unlink($ruta_anterior);
                }
            }
            $imagen_nueva = $nombre_final;
        } else {
            echo "<h4>Error al guardar la imagen nueva.</h4>";
            exit;
        }
    } else {
        echo "<h4>Formato de imagen no permitido. Usa JPG, PNG o GIF.</h4>";
        exit;
    }
} else {
    // No se subió imagen nueva, se conserva la anterior
    $imagen_nueva = $evento_actual['imagen'];
}

if ($model->editarEvento($id_evento, $nombre, $descripcion, $categoria, $lugar, $precio, $imagen_nueva)) {
    header("Location: ../../View/editarperfil.php?actualizado=1");
    exit;
} else {
    echo "<h4>Error al actualizar el servicio.</h4>";
}
?>