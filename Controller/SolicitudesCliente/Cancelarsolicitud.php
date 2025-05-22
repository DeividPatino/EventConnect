<?php
session_start();
require_once '../../Model/SolicitudesModel.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_solicitud'])) {
    $model = new SolicitudesModel();
    $model->cancelarSolicitud($_POST['id_solicitud'], $_SESSION['id_usuario']);
    $model->cerrarConexion();
}

header("Location: vermissolicitudes.php");
exit();
