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

$checkQuery = "SELECT id FROM registro WHERE correo = '". mysqli_real_escape_string($connection_obj, $cr) ."'";
$checkResult = mysqli_query($connection_obj, $checkQuery);

if(mysqli_num_rows($checkResult) > 0) {
    // Si el correo ya existe, muestra un mensaje de error
    echo "<h4>Error: El correo ya está registrado.</h4>";
} else {
    // Si el correo no existe, proceder con la inserción
    $query = "INSERT INTO registro(`nombre`, `apellido`, `correo`, `contraseña`, `tipo`)
    VALUES('". mysqli_real_escape_string($connection_obj, $ar)."', '". mysqli_real_escape_string($connection_obj, $br) ."', '". mysqli_real_escape_string($connection_obj, $cr) ."', '". mysqli_real_escape_string($connection_obj, $dr) ."', '". mysqli_real_escape_string($connection_obj, $er) ."')";
    
    $res = mysqli_query($connection_obj, $query);
    if($res){
        echo "<h4>Datos insertados correctamente</h4>";
    }else{
        echo "<h4>Error al insertar datos</h4>";
    }
}
?>