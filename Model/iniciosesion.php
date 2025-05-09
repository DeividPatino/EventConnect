<?php
session_start();
$connection_obj = mysqli_connect("localhost", "root", "", "eventconnect");
if(!$connection_obj) {
    die("Error de conexión: " . mysqli_connect_error());
}

$usuario = $_POST['u'];
$contrasena = $_POST['c'];

$query = "SELECT * FROM registro WHERE correo='$usuario'";
$result = mysqli_query($connection_obj, $query);
$row = mysqli_fetch_assoc($result);

if ($row && password_verify($contrasena, $row['contraseña'])) {
    $_SESSION['id_usuario'] = $row['id'];
    $_SESSION['tipo'] = $row['tipo'];
    $_SESSION['nombre'] = $row['nombre'];
    echo "Login exitoso. Bienvenido, ".$row['nombre'];
    // Redirigir a la página de chat o dashboard
    header("Location: chat.php");
} else {
    echo "Usuario o contraseña incorrectos.";
}

mysqli_close($connection_obj);
?>
