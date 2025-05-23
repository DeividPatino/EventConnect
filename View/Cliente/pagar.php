<?php
session_start();

if (!isset($_SESSION['pago_solicitud_id']) || !isset($_SESSION['pago_precio'])) {
    echo "No hay datos de pago.";
    exit;
}

$idSolicitud = $_SESSION['pago_solicitud_id'];
$precio = $_SESSION['pago_precio'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Confirmar Pago</title>
  <link rel="stylesheet" href="../CSS/Bootstrap/bootstrap.min.css">
</head>
<body class="p-5">
  <div class="container">
    <h2>Confirmar Pago</h2>
    <p>Total a pagar: <strong><?= number_format($precio, 0, ',', '.') ?> COP</strong></p>
    <form method="POST" action="../../Controller/SolicitudesCliente/pagarCtrl.php?id=<?= $idSolicitud ?>&precio=<?= $precio ?>">
      <button type="submit" class="btn btn-success">Confirmar pago</button>
      <a href="mis_solicitudes_cliente.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</body>
</html>