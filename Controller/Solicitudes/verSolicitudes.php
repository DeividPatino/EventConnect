<?php
session_start();
require_once '../../Model/SolicitudModel.php';

if (isset($_SESSION['id_usuario']) && $_SESSION['tipo'] === 'proveedor') {
  $modelo = new SolicitudModel();
  $solicitudes = $modelo->obtenerSolicitudesPorProveedor($_SESSION['id_usuario']);
  include '../../View/verSolicitudes.php';
} else {
  echo "Acceso no autorizado.";
}

?>