<?php
require_once '../Model/EventoModel.php';
require_once '../Model/ValoracionModel.php';
require_once '../Model/SolicitudesModel.php';
session_start();

if (!isset($_GET['id'])) {
    echo "Evento no especificado.";
    exit;
}

$id_evento = intval($_GET['id']); // <- FIJA EL ID

$model = new EventoModel();
$evento = $model->obtenerEventoPorId($id_evento);

$vModel = new ValoracionModel();
$valoraciones = $vModel->obtenerValoracionesPorEvento($id_evento);
$promedio = $vModel->obtenerPromedioPorEvento($id_evento);

$solicitudesPendientes = (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'proveedor') ? (new SolicitudesModel())->contarSolicitudesPendientes($_SESSION['id_usuario']) : 0;

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
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    margin: 30px auto;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    color: #f5f6f8;
    text-shadow: 0 0 5px rgba(0,0,0,0.4);
  }

  /* Título */
  main.evento-detalle h1 {
    font-size: 2.4rem;
    margin-bottom: 20px;
    color: #ff5a5f;
    text-shadow: 0 0 6px rgba(255, 90, 95, 0.7);
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
    color: #e0e6ea;
    text-shadow: 0 0 3px rgba(0,0,0,0.5);
  }

  main.evento-detalle strong {
    color: #35c3c1;
    text-shadow: 0 0 3px rgba(0,0,0,0.5);
  }

  /* Descripción */
  main.evento-detalle h3 {
    margin-top: 35px;
    margin-bottom: 15px;
    font-weight: 700;
    color: #ff5a5f;
    text-shadow: 0 0 5px rgba(255, 90, 95, 0.7);
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
    color: #35c3c1;
    text-shadow: 0 0 3px rgba(0,0,0,0.6);
  }

  .solicitud-form textarea {
    padding: 12px;
    border-radius: 8px;
    border: 1.5px solid #ccc;
    font-size: 1rem;
    resize: vertical;
    min-height: 100px;
    transition: border-color 0.3s ease;
    background: rgba(255, 255, 255, 0.2);
    color: #f5f6f8;
    text-shadow: 0 0 3px rgba(0,0,0,0.7);
  }

  .solicitud-form textarea::placeholder {
    color: #d0e0e3;
  }

  .solicitud-form textarea:focus {
    border-color: #35c3c1;
    outline: none;
    box-shadow: 0 0 8px #35c3c1;
    background: rgba(255, 255, 255, 0.3);
  }

  /* Botón */
  .solicitud-form button {
    align-self: flex-start;
    background-color: #35c3c1;
    color: white;
    font-weight: 700;
    padding: 12px 30px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    font-size: 1.1rem;
    transition: background-color 0.3s ease;
    text-shadow: 0 0 2px rgba(0,0,0,0.6);
  }

  .solicitud-form button:hover {
    background-color: #2aa9a7;
  }

  /* Mensaje para usuarios no clientes */
  main.evento-detalle em {
    display: block;
    margin-top: 25px;
    font-style: italic;
    color: #bbb;
    text-shadow: 0 0 2px rgba(0,0,0,0.5);
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
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    min-width: 280px;
    color: #f5f6f8;
    text-shadow: 0 0 5px rgba(0,0,0,0.6);
  }

  /* Formulario dentro de columna derecha */
  .detalle-right .solicitud-form {
    margin-top: 0;
  }

  .perfil-proveedor {
    margin-top: 30px;
    padding-top: 15px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
  }

  .perfil-proveedor h3 {
    color: #ff5a5f;
    margin-bottom: 10px;
    text-shadow: 0 0 6px rgba(255, 90, 95, 0.7);
  }

  .valoraciones {
    margin-top: 50px;
    border-top: 1px solid rgba(255, 255, 255, 0.15);
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
    text-shadow: 0 0 3px rgba(0,0,0,0.7);
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
    text-shadow: 0 0 3px rgba(0,0,0,0.7);
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
    background: rgba(255, 255, 255, 0.2);
    color: #f5f6f8;
    text-shadow: 0 0 3px rgba(0,0,0,0.7);
  }

  .formulario-valoracion textarea::placeholder {
    color: #d0e0e3;
  }

  .formulario-valoracion button {
    align-self: flex-start;
    background-color: #35c3c1;
    color: white;
    font-weight: 700;
    padding: 10px 25px;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
    text-shadow: 0 0 2px rgba(0,0,0,0.6);
  }

  .formulario-valoracion button:hover {
    background-color: #2aa9a7;
  }

  .comentarios-cargados {
  margin-top: 30px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.comentarios-cargados h4 {
  color: #ff5a5f;
  text-shadow: 0 0 6px rgba(255, 90, 95, 0.7);
  margin-bottom: 15px;
}

/* Estilo general para cada comentario */
.comentario {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border-radius: 15px;
  padding: 20px 25px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
  color: #f5f6f8;
  text-shadow: 0 0 4px rgba(0, 0, 0, 0.6);
  line-height: 1.5;
  border: 1px solid rgba(255, 255, 255, 0.25);
}

/* Texto del comentario */
.comentario p {
  margin: 0;
  font-size: 1rem;
}

/* Nombre o autor del comentario, si lo tienes */
.comentario .autor {
  margin-top: 12px;
  font-weight: 700;
  font-size: 0.9rem;
  color: #ff5a5f;
  text-shadow: 0 0 5px rgba(255, 90, 95, 0.8);
  text-align: right;
}

/* Comentario placeholder si no hay comentarios */
.comentario-placeholder {
  background-color: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  padding: 20px 25px;
  border-radius: 15px;
  color: #bbb;
  font-style: italic;
  text-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
  border: 1px dashed rgba(255, 255, 255, 0.2);
}


  .btn-whatsapp {
    display: inline-block;
    padding: 10px 15px;
    background-color: #25D366;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    text-shadow: 0 0 3px rgba(0,0,0,0.6);
  }

  .btn-whatsapp:hover {
    background-color: #1DA851;
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


</head>
<body>

<header class="navbar">
    <div class="logo">EventConnect</div>
    <input type="text" class="search-bar" id="busqueda-evento" placeholder="Buscar eventos" />
    
    <nav class="nav-links">
      <!-- Proveedor -->
      <?php if (isset($_SESSION['id_usuario'])): ?>
        <?php if ($_SESSION['tipo'] === 'proveedor'): ?>
          <a href="../Proveedores/publicareventos.html" id="crear-evento" class="btn btn-outline-secondary position-relative">
            <i class="bi bi-plus-circle" style="font-size: 20px;"></i>Crear eventos</a>
         <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'proveedor'): ?>
          <a href="../Controller/Solicitudes/VerSolicitudes.php" class="btn btn-outline-secondary position-relative">
            <i class="bi bi-card-list" style="font-size: 20px;"></i> Solicitudes
              <?php if ($solicitudesPendientes > 0): ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= $solicitudesPendientes ?>
              </span>
              <?php endif; ?>
          </a>
         <?php endif; ?>
        <?php endif; ?>
        
        <!-- Cliente -->
        <?php if (isset($_SESSION['id_usuario'])): ?>
        <?php if ($_SESSION['tipo'] === 'cliente'): ?>
          <a href="../Controller/SolicitudesCliente/versolicitudescliente.php" class="btn btn-outline-secondary position-relative">
            <i class="bi bi-card-list" style="font-size: 20px;"></i> Mis Solicitudes
          </a>
        <?php endif; ?>
        <?php endif; ?>
        
        <!-- Todo -->
        <a href="../Proveedores/proovedor_panel.php" class="btn btn-outline-secondary position-relative">
          <i class="bi bi-question-circle" style="font-size: 20px;"></i> Centro de ayuda</a>
        <a href="editarperfil.php" class="btn btn-outline-secondary position-relative">
          <i class="bi bi-person-circle" style="font-size: 20px;"></i> <?php echo htmlspecialchars($_SESSION['nombre']); ?></a>
        <a href="../Controller/CerrarsesionCtrl.php" class="btn btn-outline-secondary position-relative">
          <i class="bi bi-door-open" style="font-size: 20px;"></i>Cerrar sesion</a>
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
      <?php else: ?>
        <p><em>Inicia sesión como cliente para solicitar este servicio.</em></p>
      <?php endif; ?>

      <section class="valoraciones">
        <h3>Valoraciones del evento</h3>

        <!-- Promedio de estrellas -->
        <div class="promedio-estrellas">
          <strong>Promedio:</strong>
          <span class="estrellas promedio">
            <?= str_repeat('★', floor($promedio)) . str_repeat('☆', 5 - floor($promedio)) ?>
          </span>
          <small>(<?= number_format($promedio, 1) ?> de 5)</small>
        </div>

        <!-- Formulario para valorar SOLO clientes logueados -->
        <?php if (isset($_SESSION['id_usuario']) && $_SESSION['tipo'] === 'cliente'): ?>
          <form class="formulario-valoracion" action="../Controller/ValoracionEvento/ValoracionCtrl.php" method="POST">
            <input type="hidden" name="id_evento" value="<?= $id_evento ?>">

            <label for="rating">Tu puntuación:</label>
            <div class="estrellas-input">
              <input type="radio" name="estrella" id="estrella5" value="5" required><label for="estrella5"><i class="bi bi-star-fill" style="font-size: 1.5rem;"></i></label>
              <input type="radio" name="estrella" id="estrella4" value="4"><label for="estrella4"><i class="bi bi-star-fill" style="font-size: 1.5rem;"></i></label>
              <input type="radio" name="estrella" id="estrella3" value="3"><label for="estrella3"><i class="bi bi-star-fill" style="font-size: 1.5rem;"></i></label>
              <input type="radio" name="estrella" id="estrella2" value="2"><label for="estrella2"><i class="bi bi-star-fill" style="font-size: 1.5rem;"></i></label>
              <input type="radio" name="estrella" id="estrella1" value="1"><label for="estrella1"><i class="bi bi-star-fill" style="font-size: 1.5rem;"></i></label>
            </div>

            <label for="comentario">Tu comentario:</label>
            <textarea id="comentario" name="comentario" placeholder="Comparte tu experiencia..." required></textarea>

            <button type="submit">Enviar valoración</button>
          </form>
        <?php elseif (!isset($_SESSION['id_usuario'])): ?>
          <p><em>Inicia sesión para valorar este evento.</em></p>
        <?php else: ?>
          <p><em>Solo los clientes pueden valorar este evento.</em></p>
        <?php endif; ?>

        <!-- Mostrar comentarios SIEMPRE -->
        <div class="comentarios-cargados">
          <h4>Comentarios de otros usuarios:</h4>
          <?php if (count($valoraciones) === 0): ?>
            <p>No hay valoraciones todavía. ¡Sé el primero en comentar!</p>
          <?php else: ?>
            <?php foreach ($valoraciones as $v): ?>
              <div class="comentario">
                <strong><?= htmlspecialchars($v['nombre']) ?>:</strong>
                <span><?= str_repeat('★', $v['puntuacion']) . str_repeat('☆', 5 - $v['puntuacion']) ?></span><br>
                <small><?= htmlspecialchars($v['comentario']) ?> (<?= $v['fecha'] ?>)</small>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>

    <div class="perfil-proveedor">
      <h3>Proveedor</h3>
        <p><strong>Nombre:</strong> <?= htmlspecialchars(($evento['proveedor_nombre'] ?? '') . ' ' . ($evento['proveedor_apellido'] ?? '')) ?></p> <!-- nombre del proveedor -->
        <p><strong>Email:</strong> <?= htmlspecialchars($evento['correo'] ?? 'No disponible') ?></p>
      
      <?php
      // Limpiar teléfono: solo números
      $telefonoWhatsapp = isset($evento['telefono_proveedor']) ? preg_replace('/\D+/', '', $evento['telefono_proveedor']) : null;

      // Preparar mensaje codificado para URL
      $mensaje = "Hola, estoy interesado en el evento '" . $evento['nombre'] . "'. ¿Podría obtener más información?";
      $mensajeCodificado = urlencode($mensaje);
      ?>

      <!-- Botón WhatsApp -->
      <?php if (!empty($telefonoWhatsapp)): ?>
        <a href="https://wa.me/<?= $telefonoWhatsapp ?>?text=<?= urlencode($mensaje) ?>" target="_blank" class="btn-whatsapp">
          <i class="bi bi-whatsapp" style="font-size: 20px;"></i> Contactar por WhatsApp
        </a>
      <?php else: ?>
        <p>Teléfono de contacto no disponible</p>
      <?php endif; ?>
      </div>
    </div>
  </div>
</main>

</body>
</html>