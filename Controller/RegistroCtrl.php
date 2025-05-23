<?php
require_once '../Model/RegistroModel.php';

$model = new RegistroModel();

$ar = $_POST['ur'];
$br = $_POST['ar'];
$cr = $_POST['cor'];
$dr = password_hash($_POST['cont'], PASSWORD_DEFAULT);
$er = $_POST['tipo'];
$fr = $_POST['tel'];

if ($model->verificarCorreo($cr)) {
    echo "<h4>Error: El correo ya está registrado.</h4>";
} else {
    if ($model->registrarUsuario($ar, $br, $cr, $dr, $er, $fr)) {
        echo "";
        header("refresh:2;url=../View/login.html");
    } else {
        echo "<h4>Error al insertar datos</h4>";
    }
}

$model->cerrarConexion();
?>