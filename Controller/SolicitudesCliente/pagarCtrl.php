<?php
session_start();
require_once '../../Model/SolicitudesModel.php';

if (!isset($_GET['id']) || !isset($_GET['precio'])) {
    echo "Datos incompletos.";
    exit;
}

$idSolicitud = $_GET['id'];
$precio = $_GET['precio'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = new SolicitudesModel();
    $model->marcarComoPagada($idSolicitud);
    $model->cerrarConexion();

    header("Location: versolicitudescliente.php?pagado=1");
    exit;
}

// Guardamos datos para la vista
$_SESSION['pago_solicitud_id'] = $idSolicitud;
$_SESSION['pago_precio'] = $precio;

// Redirige a la vista
header("Location: ../../View/Cliente/pagar.php");
exit;
?>