<?php
session_start();
require_once '../../Model/SolicitudesModel.php';

$solicitudModel = new SolicitudesModel();
$id_proveedor = $_SESSION['id_usuario']; 

$solicitudesPendientes = $solicitudModel->contarSolicitudesPendientes($id_proveedor);

if (isset($_SESSION['id_usuario']) && $_SESSION['tipo'] === 'proveedor') {
  $modelo = new SolicitudesModel();
  $solicitudes = $modelo->obtenerSolicitudesPorProveedor($_SESSION['id_usuario']);
  include '../../View/Proveedores/verSolicitudes.php';
} else {
  echo "Acceso no autorizado.";
}

?>