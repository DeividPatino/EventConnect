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
$dr = $_POST['cont'];

$query = "INSERT INTO registro(`nombre`, `apellido`, `correo`, `contraseña`)
VALUES('". mysql_real_escape_string($connection_obj, $ar)."', '". mysql_real_escape_string($connection_obj, $br) ."', '". mysql_real_escape_string($connection_obj, $cr) ."', '". mysql_real_escape_string($connection_obj, $dr) ."')"

$res = mysqli_query($connection_obj, $query);
if($res){
    echo "<h4>Datos insertados correctamente</h4>";
}else{
    echo "<h4>Error al insertar datos</h4>";
}

mysqli_close($connection_obj);

?>