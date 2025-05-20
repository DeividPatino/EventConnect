<?php
session_start();
require_once '../../Model/SolicitudesModel.php';

if (isset($_SESSION['id_usuario']) && $_SESSION['tipo'] === 'proveedor') {
  $modelo = new SolicitudesModel();
  $solicitudes = $modelo->obtenerSolicitudesPorProveedor($_SESSION['id_usuario']);
  include '../../View/Solicitudes/Proveedor/verSolicitudes.php';
} else {
  echo "Acceso no autorizado.";
}

?>