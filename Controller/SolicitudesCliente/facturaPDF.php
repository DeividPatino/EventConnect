<?php
require '../../vendor/autoload.php';
require_once '../../Model/SolicitudesModel.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Validar ID de la solicitud
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de solicitud no especificado o inválido.";
    exit;
}

$idSolicitud = intval($_GET['id']);

// Obtener datos de la solicitud
$model = new SolicitudesModel();
$solicitud = $model->obtenerSolicitudPorId($idSolicitud); // Esta ya debe traer el precio desde eventos

if (!$solicitud) {
    echo "Solicitud no encontrada.";
    exit;
}

// Construir contenido HTML del PDF
$html = "
    <style>
  body {
    font-family: 'Helvetica', 'Arial', sans-serif;
    font-size: 14px;
    color: #333;
    padding: 40px;
  }

  h1 {
    text-align: center;
    color: #ff5a5f;
    margin-bottom: 40px;
    border-bottom: 2px solid #ff5a5f;
    padding-bottom: 10px;
  }

  .info-section {
    margin-bottom: 30px;
  }

  .info-section p {
    margin: 5px 0;
  }

  .label {
    font-weight: bold;
    color: #555;
  }

  .table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  .table th, .table td {
    padding: 12px;
    border: 1px solid #ccc;
    text-align: left;
  }

  .table th {
    background-color: #f2f2f2;
    color: #333;
  }

  .total {
    text-align: right;
    font-size: 16px;
    font-weight: bold;
    margin-top: 20px;
  }

  .footer {
    text-align: center;
    margin-top: 40px;
    font-size: 12px;
    color: #aaa;
  }
</style>

    <h1>Factura de Solicitud</h1>
    <div class='section'><span class='label'>Evento:</span> {$solicitud['nombre_evento']}</div>
    <div class='section'><span class='label'>Descripción:</span> {$solicitud['mensaje']}</div>
    <div class='section'><span class='label'>Precio:</span> " . number_format($solicitud['precio'], 0, ',', '.') . " COP</div>
    <div class='section'><span class='label'>Fecha de Solicitud:</span> {$solicitud['fecha_solicitud']}</div>
    <div class='section'><span class='label'>Estado de la solicitud:</span> {$solicitud['estado']}</div>
    <div class='section'><span class='label'>Estado de pago:</span> " . ($solicitud['pagada'] ? 'Pagada' : 'No pagada') . "</div>
";

// Generar PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Enviar PDF al navegador
$dompdf->stream("factura_evento_{$idSolicitud}.pdf", ["Attachment" => false]);