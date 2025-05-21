<?php
session_start();
require_once '../../Model/SolicitudesModel.php';

if (isset($_SESSION['id_usuario']) && $_SESSION['tipo'] === 'proveedor') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_solicitud'], $_POST['nuevo_estado'])) {
        $idSolicitud = $_POST['id_solicitud'];
        $nuevoEstado = $_POST['nuevo_estado'];

        // Validar estado permitido
        $estadosPermitidos = ['aceptada', 'rechazada'];
        if (!in_array($nuevoEstado, $estadosPermitidos)) {
            echo "Estado inválido.";
            exit;
        }

        $modelo = new SolicitudesModel();

        // Validar que la solicitud pertenece al proveedor
        if ($modelo->verificarPropiedadSolicitud($idSolicitud, $_SESSION['id_usuario'])) {
            if ($modelo->cambiarEstadoSolicitud($idSolicitud, $nuevoEstado)) {
                header("Location: verSolicitudesCtrl.php");
                exit;
            } else {
                echo "Error al actualizar el estado.";
            }
        } else {
            echo "No tienes permiso para modificar esta solicitud.";
        }
    } else {
        echo "Datos incompletos.";
    }
} else {
    echo "Acceso no autorizado.";
}
