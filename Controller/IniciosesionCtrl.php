<?php
session_start();
require_once '../Model/RegistroModel.php';

$model = new RegistroModel();

$usuario = $_POST['u'];
$contrasena = $_POST['c'];

$row = $model->obtenerUsuarioPorCorreo($usuario);

if ($row && password_verify($contrasena, $row['contraseña'])) {
    $_SESSION['id_usuario'] = $row['id'];
    $_SESSION['tipo'] = $row['tipo'];
    $_SESSION['nombre'] = $row['nombre'];

    echo "";
    header("Location: ../View/index.php");
} else {
    echo "<h4>Error: Usuario o contraseña incorrectos.</h4>";
}

$model->cerrarConexion();
?>