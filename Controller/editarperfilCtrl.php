<?php
session_start();
require_once '../Model/RegistroModel.php';

if (!isset($_POST['id'])) {
    header("Location: ../View/login.html");
    exit;
}

$model = new RegistroModel();
$nombre = $_POST['ur'];
$apellido = $_POST['ar'];
$correo = $_POST['cor'];
$telefono = $_POST['tel'];
$contrasena = !empty($_POST['cont']) ? password_hash($_POST['cont'], PASSWORD_DEFAULT) : null;

$model->actualizarPerfil($_POST['id'], $nombre, $apellido, $correo, $telefono, $contrasena);
$model->cerrarConexion();

header("Location: ../View/editarperfil.php");
