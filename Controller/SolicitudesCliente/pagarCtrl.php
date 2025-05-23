<?php
session_start();
require '../../vendor/autoload.php';
require_once '../../Model/SolicitudesModel.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_GET['id']) || !isset($_GET['precio'])) {
    echo "Datos incompletos.";
    exit;
}

$idSolicitud = intval($_GET['id']);
$precio = $_GET['precio'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = new SolicitudesModel();

    // 1. Marcar la solicitud como pagada
    $model->marcarComoPagada($idSolicitud);

    // 2. Obtener datos de la solicitud para generar factura
    $solicitud = $model->obtenerSolicitudPorId($idSolicitud);

    // 2.1 Obtener datos del cliente desde la DB
    $datosCliente = $model->obtenerDatosClientePorSolicitud($idSolicitud);
    $emailCliente = $datosCliente['correo'] ?? null;
    $nombreCliente = $datosCliente['nombre'] ?? 'Cliente';

    if (!$emailCliente || !filter_var($emailCliente, FILTER_VALIDATE_EMAIL)) {
        echo "Correo del cliente no válido o no disponible.";
        exit;
    }

    // 3. Generar PDF factura
    $html = "
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; padding: 40px; }
        h1 { text-align: center; color: #ff5a5f; margin-bottom: 40px; border-bottom: 2px solid #ff5a5f; padding-bottom: 10px; }
        .section { margin-bottom: 20px; }
        .label { font-weight: bold; color: #555; }
    </style>
    <h1>Factura de Solicitud</h1>
    <div class='section'><span class='label'>Evento:</span> {$solicitud['nombre_evento']}</div>
    <div class='section'><span class='label'>Descripción:</span> {$solicitud['mensaje']}</div>
    <div class='section'><span class='label'>Precio:</span> " . number_format($solicitud['precio'], 0, ',', '.') . " COP</div>
    <div class='section'><span class='label'>Fecha de Solicitud:</span> {$solicitud['fecha_solicitud']}</div>
    <div class='section'><span class='label'>Estado de la solicitud:</span> {$solicitud['estado']}</div>
    <div class='section'><span class='label'>Estado de pago:</span> " . ($solicitud['pagada'] ? 'Pagada' : 'No pagada') . "</div>
    ";

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $pdfOutput = $dompdf->output();

    // 4. Preparar correo
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'patinodeivid@gmail.com';         // Cambia por tu email SMTP
        $mail->Password = 'uusmnlmkphjowdwj';               // Cambia por tu password SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('patinodeivid@gmail.com', 'EventConnect');

        // Agregar destinatario usando datos obtenidos de DB
        $mail->addAddress($emailCliente, $nombreCliente);

        $mail->isHTML(true);
        $mail->Subject = 'Factura de su pago - EventConnect';
        $mail->Body = 'Adjuntamos su factura en PDF. Gracias por su compra.';

        $mail->addStringAttachment($pdfOutput, 'factura_evento_' . $idSolicitud . '.pdf');

        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar correo: " . $e->getMessage() . "<br>";
        echo "Detalles PHPMailer: " . $mail->ErrorInfo;
        exit;
    }

    $model->cerrarConexion();

    header("Location: ../../View/index.php?pagado=1");
    exit;
}

// Guardamos datos para la vista (solo si no es POST)
$_SESSION['pago_solicitud_id'] = $idSolicitud;
$_SESSION['pago_precio'] = $precio;

// Redirige a la vista de pago
header("Location: ../../View/Cliente/pagar.php");
exit;
