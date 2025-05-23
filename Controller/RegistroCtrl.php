<?php
require_once '../Model/RegistroModel.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ajusta la ruta según dónde tengas composer y vendor

$model = new RegistroModel();

$ar = $_POST['ur'];  // Nombre
$br = $_POST['ar'];  // Apellido
$cr = $_POST['cor']; // Correo
$dr = $_POST['tel']; // Teléfono
$fr = password_hash($_POST['cont'], PASSWORD_DEFAULT); // Contraseña hasheada
$er = $_POST['tipo']; // Tipo usuario

if ($model->verificarCorreo($cr)) {
    echo "<h4>Error: El correo ya está registrado.</h4>";
} else {
    if ($model->registrarUsuario($ar, $br, $cr, $dr, $fr, $er)) {
        // Enviar correo de bienvenida
        $mail = new PHPMailer(true);
        try {
            // Configuración SMTP (usa tu configuración real)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // o tu servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'patinodeivid@gmail.com'; // tu email
            $mail->Password = 'uusmnlmkphjowdwj'; // tu password (app password si gmail)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('patinodeivid@gmail.com', 'EventConnect');
            $mail->addAddress($cr, $ar . ' ' . $br); // correo y nombre usuario registrado

            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido a EventConnect';
            $mail->Body = "
                <h1>¡Hola $ar!</h1>
                <p>Gracias por registrarte en <b>EventConnect</b>.</p>
                <p>Esperamos que disfrutes usando nuestra plataforma.</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            // Si falla el correo, igual seguimos con el registro pero mostramos error email
            echo "<p>Registro exitoso, pero no se pudo enviar el correo de bienvenida.</p>";
            echo "<p>Error: " . $mail->ErrorInfo . "</p>";
        }

        header("refresh:4;url=../View/login.html");
    } else {
        echo "<h4>Error al insertar datos</h4>";
    }
}

$model->cerrarConexion();
?>