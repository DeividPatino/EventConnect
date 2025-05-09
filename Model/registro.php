<?php
$connection_obj = mysqli_connect("localhost", "root", "", "eventconnect");
if(!$connection_obj) {
    echo "Error no: " . mysqli_connect_errno();
    echo "Error description: " . mysqli_connect_error();
    exit;
}

$ar = $_POST['ur'];
$br = $_POST['ar'];
$cr = $_POST['cor'];
$dr = password_hash($_POST['cont'], PASSWORD_DEFAULT);
$er = $_POST['tipo'];

$query = "INSERT INTO registro(`nombre`, `apellido`, `correo`, `contraseña`, `tipo`)
VALUES('". mysqli_real_escape_string($connection_obj, $ar)."', '". mysqli_real_escape_string($connection_obj, $br) ."', '". mysqli_real_escape_string($connection_obj, $cr) ."', '". mysqli_real_escape_string($connection_obj, $dr) ."', '". mysqli_real_escape_string($connection_obj, $er) ."')";

$res = mysqli_query($connection_obj, $query);
if($res){
    echo "<h4>Datos insertados correctamente</h4>";
}else{
    echo "<h4>Error al insertar datos</h4>";
}

mysqli_close($connection_obj);

?>