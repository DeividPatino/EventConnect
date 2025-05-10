<?php
session_start();
$connection_obj = mysqli_connect("localhost", "root", "", "eventconnect");
if (!$connection_obj) {
    echo "Error no: " . mysqli_connect_errno();
    echo "Error description: " . mysqli_connect_error();
    exit;
}

$usuario = $_POST['u'];
$contrasena = $_POST['c'];

$query = "SELECT * FROM registro WHERE correo = '" . mysqli_real_escape_string($connection_obj, $usuario) . "'";
$result = mysqli_query($connection_obj, $query);
$row = mysqli_fetch_assoc($result);

if ($row && password_verify($contrasena, $row['contraseña'])) {
    // Si login exitoso, guardar en sesión
    $_SESSION['id_usuario'] = $row['id'];
    $_SESSION['tipo'] = $row['tipo'];
    $_SESSION['nombre'] = $row['nombre'];

    echo "";
    header("Location: ../View/index.html");
} else {
    echo "<h4>Error: Usuario o contraseña incorrectos.</h4>";
}

mysqli_close($connection_obj);
?>
