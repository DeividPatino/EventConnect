<?php
require_once '../Model/EventoModel.php';
session_start();

if (!isset($_GET['id'])) {
    echo "Evento no especificado.";
    exit;
}

$model = new EventoModel();
$evento = $model->obtenerEventoPorId($_GET['id']);
$model->cerrarConexion();

if (!$evento) {
    echo "El evento no existe.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($evento['nombre']) ?> | EventConnect</title>
    <link rel="stylesheet" href="../CSS/style.css" />
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <style>
  /* Contenedor principal */
  main.evento-detalle {
    max-width: 900px;
    background: white;
    margin: 30px auto;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
  }

  /* Título */
  main.evento-detalle h1 {
    font-size: 2.4rem;
    margin-bottom: 20px;
    color: #0d6efd;
  }

  /* Imagen del evento */
  main.evento-detalle img {
    display: block;
    max-width: 100%;
    height: 490px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 6px 15px rgba(13,110,253,0.3);
  }

  /* Información */
  main.evento-detalle p {
    font-size: 1.1rem;
    margin-bottom: 14px;
  }

  main.evento-detalle strong {
    color: #0d6efd;
  }

  /* Descripción */
  main.evento-detalle h3 {
    margin-top: 35px;
    margin-bottom: 15px;
    font-weight: 700;
    color: #0d6efd;
  }

  /* Formulario de solicitud */
  .solicitud-form {
    margin-top: 30px;
    display: flex;
    flex-direction: column;
    gap: 15px;
  }

  .solicitud-form label {
    font-weight: 600;
    font-size: 1.1rem;
    color: #0d6efd;
  }

  .solicitud-form textarea {
    padding: 12px;
    border-radius: 8px;
    border: 1.5px solid #ccc;
    font-size: 1rem;
    resize: vertical;
    min-height: 100px;
    transition: border-color 0.3s ease;
  }

  .solicitud-form textarea:focus {
    border-color: #0d6efd;
    outline: none;
    box-shadow: 0 0 8px #0d6efd;
  }

  /* Botón */
  .solicitud-form button {
    align-self: flex-start;
    background-color: #0d6efd;
    color: white;
    font-weight: 700;
    padding: 12px 30px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    font-size: 1.1rem;
    transition: background-color 0.3s ease;
  }

  .solicitud-form button:hover {
    background-color: #0b5ed7;
  }

  /* Mensaje para usuarios no clientes */
  main.evento-detalle em {
    display: block;
    margin-top: 25px;
    font-style: italic;
    color: #888;
  }

  .detalle-container {
  display: flex;
  gap: 40px;
  flex-wrap: wrap;
}

/* Columnas */
.detalle-left {
  flex: 2;
}

.detalle-right {
  flex: 1;
  background-color: #f0f4ff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  min-width: 280px;
}

/* Formulario dentro de columna derecha */
.detalle-right .solicitud-form {
  margin-top: 0;
}

.perfil-proveedor {
  margin-top: 30px;
  padding-top: 15px;
  border-top: 1px solid #ccc;
}

.perfil-proveedor h3 {
  color: #0d6efd;
  margin-bottom: 10px;
}

.valoraciones {
  margin-top: 50px;
  border-top: 1px solid #ddd;
  padding-top: 30px;
}

.promedio-estrellas {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 1.2rem;
  margin-bottom: 20px;
}

.estrellas.promedio {
  color: #ffc107;
  font-size: 1.5rem;
}

.formulario-valoracion {
  display: flex;
  flex-direction: column;
  gap: 15px;
  margin-bottom: 40px;
}

.estrellas-input {
  display: flex;
  flex-direction: row-reverse;
  justify-content: flex-start;
}

.estrellas-input input {
  display: none;
}

.estrellas-input label {
  font-size: 2rem;
  color: #ccc;
  cursor: pointer;
  transition: color 0.2s;
}

.estrellas-input input:checked ~ label,
.estrellas-input label:hover,
.estrellas-input label:hover ~ label {
  color: #ffc107;
}

.formulario-valoracion textarea {
  padding: 12px;
  border-radius: 8px;
  border: 1.5px solid #ccc;
  resize: vertical;
  min-height: 100px;
  font-size: 1rem;
}

.formulario-valoracion button {
  align-self: flex-start;
  background-color: #0d6efd;
  color: white;
  font-weight: 700;
  padding: 10px 25px;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.3s ease;
}

.formulario-valoracion button:hover {
  background-color: #0b5ed7;
}

.comentarios-cargados h4 {
  margin-bottom: 15px;
  color: #0d6efd;
}

.comentario-placeholder {
  background-color: #f2f2f2;
  padding: 20px;
  border-radius: 12px;
  color: #555;
  font-style: italic;
}


/* Responsivo */
@media (max-width: 768px) {
  .detalle-container {
    flex-direction: column;
  }

  .detalle-left,
  .detalle-right {
    width: 100%;
  }
}

  /* Responsive */
  @media (max-width: 720px) {
    .navbar {
      flex-direction: column;
      align-items: flex-start;
    }

    .search-bar {
      margin: 10px 0;
      width: 100%;
    }

    .nav-links {
      width: 100%;
      justify-content: space-between;
    }

    main.evento-detalle {
      padding: 20px;
    }

    main.evento-detalle h1 {
      font-size: 1.8rem;
    }
  }
</style>

    </style>
</head>
<body>

<header class="navbar">
    <div class="logo">EventConnect</div>
    <input type="text" class="search-bar" placeholder="Buscar eventos" />
    
    <nav class="nav-links">
      <?php if (isset($_SESSION['id_usuario'])): ?>
        <?php if ($_SESSION['tipo'] === 'proveedor'): ?>
          <a href="../View/Proveedores/publicareventos.html" id="crear-evento">Crear eventos</a>
        <?php endif; ?>
        <a href="../View/Proveedores/proovedor_panel.php">Centro de ayuda</a>
        <a href="#">Mis entradas</a>
        <a href="perfil.php">Editar perfil (<?= htmlspecialchars($_SESSION['nombre']) ?>)</a>
        <a href="../Controller/CerrarsesionCtrl.php">Cerrar sesión</a>
      <?php else: ?>
        <a href="#">Centro de ayuda</a>
        <a href="login.html">Iniciar sesión</a>
        <a href="Registro.html">Registrarse</a>
      <?php endif; ?>
    </nav>
</header>

<main class="evento-detalle">
  <div class="detalle-container">
    <!-- COLUMNA IZQUIERDA -->
    <div class="detalle-left">
      <h1><?= htmlspecialchars($evento['nombre']) ?></h1>
      <img src="../Uploads/<?= htmlspecialchars($evento['imagen']) ?>" alt="Imagen del evento">
      
      <p><strong>Categoría:</strong> <?= htmlspecialchars($evento['categoria']) ?></p>
      <p><strong>Ciudad:</strong> <?= htmlspecialchars($evento['lugar']) ?></p>
      <p><strong>Precio:</strong> $<?= number_format($evento['precio'], 0, ',', '.') ?> COP</p>
      
      <h3>Descripción del servicio:</h3>
      <p><?= nl2br(htmlspecialchars($evento['descripcion'])) ?></p>
    </div>

    <!-- COLUMNA DERECHA -->
    <div class="detalle-right">
      <?php if (isset($_SESSION['id_usuario']) && $_SESSION['tipo'] === 'cliente'): ?>
        <form action="../Controller/Solicitudes/SolicitudesCtrl.php" method="POST" class="solicitud-form">
          <input type="hidden" name="id_evento" value="<?= $evento['id_evento'] ?>">
          <label for="mensaje">Mensaje para el proveedor:</label>
          <textarea name="mensaje" id="mensaje" required placeholder="Escribe tu solicitud o requerimientos especiales..."></textarea>
          <button type="submit">Solicitar servicio</button>
        </form>
        <section class="valoraciones">
  <h3>Valoraciones del evento</h3>

  <!-- Promedio de estrellas -->
  <div class="promedio-estrellas">
    <strong>Promedio:</strong>
    <span class="estrellas promedio">
      ★★★★☆
    </span>
    <small>(4.0 de 5)</small>
  </div>

  <!-- Formulario de puntuación -->
  <form class="formulario-valoracion">
  <label for="rating">Tu puntuación:</label>
  <div class="estrellas-input">
    <input type="radio" name="estrella" id="estrella5" value="5"><label for="estrella5"><i class="bi bi-star-fill" style="font-size: 1.5rem;"></i></label>
    <input type="radio" name="estrella" id="estrella4" value="4"><label for="estrella4"><i class="bi bi-star-fill" style="font-size: 1.5rem;"></i></label>
    <input type="radio" name="estrella" id="estrella3" value="3"><label for="estrella3"><i class="bi bi-star-fill" style="font-size: 1.5rem;"></i></label>
    <input type="radio" name="estrella" id="estrella2" value="2"><label for="estrella2"><i class="bi bi-star-fill" style="font-size: 1.5rem;"></i></label>
    <input type="radio" name="estrella" id="estrella1" value="1"><label for="estrella1"><i class="bi bi-star-fill" style="font-size: 1.5rem;"></i></label>
  </div>

  <label for="comentario">Tu comentario:</label>
  <textarea id="comentario" name="comentario" placeholder="Comparte tu experiencia..." required></textarea>

  <button type="submit">Enviar valoración</button>

  <!-- Zona para cargar los comentarios desde la base de datos -->
  <div class="comentarios-cargados">
    <h4>Comentarios de otros usuarios:</h4>
    <div class="comentario-placeholder">
      <!-- Aquí se cargarán los comentarios dinámicamente -->
    </div>
  </div>
</section>

      <?php else: ?>
        <p><em>Inicia sesión como cliente para solicitar este servicio.</em></p>
      <?php endif; ?>

      <div class="perfil-proveedor">
        <h3>Proveedor</h3>
        <p><strong>Nombre:</strong> <?= htmlspecialchars($evento['nombre_proveedor'] ?? 'No disponible') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($evento['email_proveedor'] ?? 'No disponible') ?></p>
        <!-- Puedes agregar más datos como teléfono, tipo de servicio, etc. -->
      </div>
    </div>
  </div>
</main>


</body>
</html>
