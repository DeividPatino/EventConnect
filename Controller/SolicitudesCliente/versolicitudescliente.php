<?php
session_start();
require_once '../../Model/SolicitudesModel.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: ../login.html");
    exit();
}

$model = new SolicitudesModel();
$solicitudes = $model->obtenerSolicitudesPorCliente($_SESSION['id_usuario']);
$model->cerrarConexion();

// Llamamos a la vista
include '../../View/Cliente/vermissolicitudes.php';
?>
