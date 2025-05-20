<?php
session_start();
require_once '../../Model/SolicitudesModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_usuario'])) {
  $idCliente = $_SESSION['id_usuario'];
  $idEvento = $_POST['id_evento'];
  $mensaje = $_POST['mensaje'];

  $modelo = new SolicitudesModel();
  $resultado = $modelo->crearSolicitud($idCliente, $idEvento, $mensaje);

  if ($resultado) {
    header('Location: ../View/detalleseventos.php?');
    exit();
  } else {
    echo "Error al enviar la solicitud.";
  }
} else {
  echo "Acceso no autorizado.";
}
