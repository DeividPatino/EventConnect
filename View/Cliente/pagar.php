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
  <link rel=""stylesheet" href="../../CSS/style.css">
  <link rel="stylesheet" href="../../CSS/Bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    /* RESET BÁSICO */
*,
*::before,
*::after {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* ESTILOS GLOBALES */
html,
body {
  height: 100%;
  margin: 0;
  padding: 0;
  font-family: 'Inter', helvetica, arial, sans-serif;
  color: #f5f6f8;
  background-color: #f0f0f0;
}

body {
  overflow-x: hidden;
}

/* PÁGINA DE CONFIRMAR PAGO */
.container {
  background: rgba(255, 255, 255, 0.9);
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
  max-width: 600px;
  margin: 3rem auto;
  text-align: center;
}

h2 {
  color: #ff5a5f;
  font-size: 2rem;
  font-weight: 800;
  margin-bottom: 1rem;
}

p {
  color: #555;
  font-size: 1.2rem;
  margin-bottom: 2rem;
}

strong {
  color: #35c3c1;
  font-size: 1.4rem;
}

/* BOTONES */
.btn {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  text-align: center;
  text-decoration: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.btn-success {
  background-color: #35c3c1;
  color: #fff;
  border: none;
}

.btn-success:hover {
  background-color: #2ea9a6;
  box-shadow: 0 0 10px rgba(53, 195, 193, 0.6);
}

.btn-secondary {
  background-color: #888;
  color: #fff;
  border: none;
  margin-left: 1rem;
}

.btn-secondary:hover {
  background-color: #666;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

a {
  text-decoration: none;
}

/* Efectos de Fondo */
body::before {
  content: "";
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: -1;
}

.container {
  backdrop-filter: blur(8px);
}

footer {
  text-align: center;
  padding: 1rem;
  background-color: rgba(0, 0, 0, 0.6);
  color: #ccc;
  backdrop-filter: blur(10px);
  position: fixed;
  bottom: 0;
  width: 100%;
}

/* Fondo de la página */
body {
  background-image: url('/EventConnect/Img/background.jpg');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  background-attachment: fixed;
}
  </style>
</head>
<body class="p-5">


  <div class="container">
    <h2>Confirmar Pago</h2>
    <p>Total a pagar: <strong><?= number_format($precio, 0, ',', '.') ?> COP</strong></p>
    <form method="POST" action="../../Controller/SolicitudesCliente/pagarCtrl.php?id=<?= $idSolicitud ?>&precio=<?= $precio ?>">
      <button type="submit" class="btn btn-success">Confirmar pago</button>
      <a href="vermissolicitudes.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</body>
</html>