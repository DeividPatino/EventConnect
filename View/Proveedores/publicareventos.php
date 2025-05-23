<?php
session_start();
require_once '../../Model/EventoModel.php';
require_once '../../Model/SolicitudesModel.php';

$model = new EventoModel();
$eventos = $model->obtenerTodosLosEventos();


$solicitudesPendientes = (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'proveedor') ? (new SolicitudesModel())->contarSolicitudesPendientes($_SESSION['id_usuario']) : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Evento</title>
    <link rel="stylesheet" href="../../CSS/Bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="stylesheet" href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
  background-image: url('/EventConnect/Img/background.jpg');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  background-attachment: fixed;
  color: #f5f6f8;
  font-family: 'Inter', helvetica, arial, sans-serif;
  overflow-x: hidden;
}

h2 {
  text-align: center;
  font-size: 2.5rem;
  margin-bottom: 2rem;
  color: #ff5a5f;
  font-weight: 800;
  text-shadow: 0 0 4px rgba(255, 90, 95, 0.4);
}

.contenido-central {
  display: flex;
  justify-content: center;
  padding: 3rem 1rem;
}

.form-publicar-evento {
  width: 100%;
  max-width: 900px;
  background: #fff; /* fondo blanco sólido */
  padding: 2.5rem 2rem;
  border-radius: 8px; /* bordes redondeados */
  box-shadow: 0 0 12px rgba(0, 0, 0, 0.1); /* sombra suave */
  color: #333; /* texto oscuro para contraste */
}


.form-group-container {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.form-group {
  flex: 1 1 calc(50% - 1.5rem);
  display: flex;
  flex-direction: column;
}

.form-group.full {
  flex: 1 1 100%;
}

label {
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #fff;
}

label {
  color: #333; /* texto oscuro */
}

input[type="text"],
input[type="number"],
input[type="file"],
textarea,
select {
  background: #f9f9f9; /* fondo clarito para inputs */
  color: #333;
  box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  border: 1px solid #ccc;
}

input[type="text"]::placeholder,
textarea::placeholder {
  color: #999;
}


textarea {
  resize: vertical;
}

input[type="submit"] {
  background-color: #35c3c1;
  border: none;
  color: #fff;
  padding: 0.75rem 1.5rem;
  font-size: 1.1rem;
  font-weight: 700;
  margin-top: 2rem;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

input[type="submit"]:hover {
  background-color: #2ea9a6;
  box-shadow: 0 0 12px rgba(53, 195, 193, 0.7);
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
          <a href="publicareventos.php" id="crear-evento" class="btn btn-outline-secondary position-relative">
            <i class="bi bi-plus-circle" style="font-size: 20px;"></i> Crear eventos</a>
         <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'proveedor'): ?>
          <a href="../../Controller/Solicitudes/VerSolicitudes.php" class="btn btn-outline-secondary position-relative">
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
        <a href="#" class="btn btn-outline-secondary position-relative">
          <i class="bi bi-question-circle" style="font-size: 20px;"></i> Centro de ayuda</a>
        <a href="/EventConnect/View/editarperfil.php" class="btn btn-outline-secondary position-relative">
          <i class="bi bi-person-circle" style="font-size: 20px;"></i> <?php echo htmlspecialchars($_SESSION['nombre']); ?></a>
        <a href="../../Controller/CerrarsesionCtrl.php" class="btn btn-outline-secondary position-relative">
          <i class="bi bi-door-open" style="font-size: 20px;"></i>Cerrar sesion</a>
      <?php else: ?>
        <a href="#">Centro de ayuda</a>
        <a href="login.html">Iniciar sesión</a>
        <a href="Registro.html">Registrarse</a>
      <?php endif; ?>
    </nav>
  </header>

   <div class="contenido-central">
    <form action="../../Controller/Eventos/EventoCtrl.php" method="post" class="form-publicar-evento" enctype="multipart/form-data">
        <h2>Publicar Nuevo Evento</h2>

        <div class="form-group-container">
            <div class="form-group">
                <label for="titulo">Título del evento:</label>
                <input type="text" name="titulo" id="titulo" required>
            </div>

            <div class="form-group">
                <label for="precio">Precio (COP):</label>
                <input type="number" name="precio" id="precio" required>
            </div>

            <div class="form-group full">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" rows="5" required></textarea>
            </div>

            <div class="form-group">
                <label for="Categoria">Categoría:</label>
                <select name="Categoria" id="Categoria" required>
                    <option value="">-- Selecciona una categoria</option>
                    <option value="Sociales">Sociales</option>
                    <option value="Ferias">Ferias</option>
                    <option value="Culturales">Culturales</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ciudad">Ciudad:</label>
                <select id="ciudad" name="ciudad" required>
                    <option value="">-- Selecciona una ciudad --</option>
                    <option value="Barranquilla">Barranquilla</option>
                    <option value="Cartagena">Cartagena</option>
                    <option value="Santa Marta">Santa Marta</option>
                </select>
            </div>

            <div class="form-group full">
                <label for="imagen">Imagen del servicio:</label>
                <input type="file" name="imagen" id="imagen" required>
            </div>
        </div>

        <input type="submit" value="Publicar">
    </form>
    </div>
</body>
</html>