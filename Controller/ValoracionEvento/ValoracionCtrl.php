<?php
session_start();
require_once '../../Model/ValoracionModel.php';

$model = new ValoracionModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idEvento = $_POST['id_evento'];
    $idUsuario = $_SESSION['id_usuario'];
    $puntuacion = $_POST['estrella'];
    $comentario = $_POST['comentario'];

    $model->guardarValoracion($idEvento, $idUsuario, $puntuacion, $comentario);

    header("Location: ../../View/detalleseventos.php?id=$idEvento");
    exit;
}
?>