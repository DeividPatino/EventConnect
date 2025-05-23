<?php
require_once '../Model/RegistroModel.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$model = new RegistroModel();

if (
    empty($_POST['ur']) ||
    empty($_POST['ar']) ||
    empty($_POST['cor']) ||
    empty($_POST['tel']) ||
    empty($_POST['cont']) ||
    empty($_POST['tipo']) 
) {
    echo "<h4>Error: Todos los campos son obligatorios.</h4>";
    exit;
}

// Asignar valores
$ar = $_POST['ur'];  // Nombre
$br = $_POST['ar'];  // Apellido
$cr = $_POST['cor']; // Correo
$dr = $_POST['tel']; // Teléfono
$fr = password_hash($_POST['cont'], PASSWORD_DEFAULT); // Contraseña hasheada
$er = $_POST['tipo']; // Tipo usuario

// Verificar si el correo ya existe
if ($model->verificarCorreo($cr)) {
    echo "<h4>Error: El correo ya está registrado.</h4>";
} else {
    if ($model->registrarUsuario($ar, $br, $cr, $dr, $fr, $er)) {
        // Enviar correo de bienvenida
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'patinodeivid@gmail.com';
            $mail->Password = 'uusmnlmkphjowdwj'; // App password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('patinodeivid@gmail.com', 'EventConnect');
            $mail->addAddress($cr, $ar . ' ' . $br);

            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido a EventConnect';
            $mail->Body = "
                <h1>¡Hola $ar!</h1>
                <p>Gracias por registrarte en <b>EventConnect</b>.</p>
                <p>Esperamos que disfrutes usando nuestra plataforma.</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            echo "<p>Registro exitoso, pero no se pudo enviar el correo de bienvenida.</p>";
            echo "<p>Error: " . $mail->ErrorInfo . "</p>";
        }

        // Redirección después de 4 segundos
        header("refresh:4;url=../View/login.html");
    } else {
        echo "<h4>Error al insertar datos</h4>";
    }
}

$model->cerrarConexion();
?>