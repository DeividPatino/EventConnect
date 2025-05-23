<?php
session_start();
require_once '../Model/RegistroModel.php';

$model = new RegistroModel();

// Validar que los campos no estén vacíos
if (empty($_POST['u']) || empty($_POST['c'])) {
    echo "<h4>Error: Usuario y contraseña son obligatorios.</h4>";
    exit;
}

$usuario = $_POST['u'];
$contrasena = $_POST['c'];

$row = $model->obtenerUsuarioPorCorreo($usuario);

// Validar que exista el usuario y que la contraseña coincida
if ($row && password_verify($contrasena, $row['contraseña'])) {
    $_SESSION['id_usuario'] = $row['id'];
    $_SESSION['tipo'] = $row['tipo'];
    $_SESSION['nombre'] = $row['nombre'];
    $_SESSION['correo'] = $row['correo'];

    header("Location: ../View/index.php");
    exit;
} else {
    echo "<h4>Error: Usuario o contraseña incorrectos.</h4>";
}

$model->cerrarConexion();
?>