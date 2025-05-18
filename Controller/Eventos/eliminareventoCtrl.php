<?php
session_start();
require_once '../../Model/EventoModel.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'proveedor') {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_evento = intval($_POST['id']);
    $id_proveedor = $_SESSION['id_usuario'];

    $model = new EventoModel();

    // Obtener el servicio para verificar que pertenece al proveedor
    $evento = $model->obtenerEventoPorId($id_evento);

    if ($evento && $evento['id_proveedor'] == $id_proveedor) {
        // Eliminar imagen del servidor si existe
        if (!empty($evento['imagen'])) {
            $ruta_imagen = '../uploads/' . $evento['imagen'];
            if (file_exists($ruta_imagen)) {
                unlink($ruta_imagen);
            }
        }

        // Eliminar de la base de datos
        if ($model->eliminarEvento($id_evento)) {
            header("Location: ../View/Proveedores/proveedor_panel.php?eliminado=1");
            exit;
        } else {
            echo "Error al eliminar el servicio.";
        }
    } else {
        echo "No tienes permiso para eliminar este servicio.";
    }

    $model->cerrarConexion();
} else {
    echo "Acceso no válido.";
}
?>
