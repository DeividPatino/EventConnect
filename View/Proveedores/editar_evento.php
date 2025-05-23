<?php
session_start();
require_once '../../Model/EventoModel.php';
require_once '../../Model/SolicitudesModel.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'proveedor') {
    header("Location: login.html");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID de evento no especificado.";
    exit;
}

$model = new EventoModel();
$evento = $model->obtenerEventoPorId($_GET['id']);
$solicitudesPendientes = (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'proveedor') ? (new SolicitudesModel())->contarSolicitudesPendientes($_SESSION['id_usuario']) : 0;

$model->cerrarConexion();

if (!$evento || $evento['id_proveedor'] != $_SESSION['id_usuario']) {
    echo "No tienes permiso para editar este evento.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evento</title>
    <link rel="stylesheet" href="../../CSS/Bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="stylesheet" href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <style>
    body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
        }


        .contenido-central {
            padding: 20px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

    .form-publicar-evento {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 900px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-publicar-evento h2 {
    text-align: center;
    margin-bottom: 10px;
    color: #333;
    font-size: 1.8rem;
}

.form-group-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
}

.form-group {
    flex: 1 1 45%;
    display: flex;
    flex-direction: column;
}

.form-group.full {
    flex: 1 1 100%;
}

.form-group label {
    margin-bottom: 6px;
    font-weight: 600;
    color: #555;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group input[type="file"],
.form-group select,
.form-group textarea {
    padding: 10px;
    border: 1.5px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
    font-family: inherit;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

input[type="submit"] {
    background-color: #4a90e2;
    border: none;
    color: white;
    padding: 12px;
    border-radius: 6px;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
}

input[type="submit"]:hover {
    background-color: #357ABD;
}

img {
    max-width: 150px;
    border-radius: 6px;
    margin-top: 6px;
}

/* Responsive */
@media (max-width: 768px) {
    .form-group {
        flex: 1 1 100%;
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

    <div class="contenido-central" >
    <form class="form-publicar-evento" method="post" enctype="multipart/form-data" action="../../Controller/Eventos/editareventoCtrl.php">
    <h2>Editar Evento</h2>

    <input type="hidden" name="id_evento" value="<?= $evento['id_evento'] ?>">

    <div class="form-group-container">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($evento['nombre']) ?>" required>
        </div>

        <div class="form-group">
            <label for="precio">Precio (COP):</label>
            <input type="number" id="precio" name="precio" value="<?= htmlspecialchars($evento['precio']) ?>" required>
        </div>

        <div class="form-group full">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required><?= htmlspecialchars($evento['descripcion']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="categoria">Categoría:</label>
            <select id="categoria" name="Categoria" required>
                <option value="">-- Selecciona una categoría --</option>
                <option value="Sociales" <?= $evento['categoria'] == 'Sociales' ? 'selected' : '' ?>>Sociales</option>
                <option value="Ferias" <?= $evento['categoria'] == 'Ferias' ? 'selected' : '' ?>>Ferias</option>
                <option value="Culturales" <?= $evento['categoria'] == 'Culturales' ? 'selected' : '' ?>>Culturales</option>
            </select>
        </div>

        <div class="form-group">
            <label for="ciudad">Ciudad:</label>
            <select id="ciudad" name="ciudad" required>
                <option value="">-- Selecciona una ciudad --</option>
                <option value="Barranquilla" <?= $evento['lugar'] == 'Barranquilla' ? 'selected' : '' ?>>Barranquilla</option>
                <option value="Cartagena" <?= $evento['lugar'] == 'Cartagena' ? 'selected' : '' ?>>Cartagena</option>
                <option value="Santa Marta" <?= $evento['lugar'] == 'Santa Marta' ? 'selected' : '' ?>>Santa Marta</option>
            </select>
        </div>

        <div class="form-group full">
            <label>Imagen actual:</label>
            <?php if (!empty($evento['imagen'])): ?>
                <img src="../../Uploads/<?= htmlspecialchars($evento['imagen']) ?>" alt="Imagen actual">
            <?php endif; ?>
        </div>

        <div class="form-group full">
            <label for="imagen">Nueva imagen (opcional):</label>
            <input type="file" id="imagen" name="imagen">
        </div>
    </div>

    <input type="submit" value="Guardar cambios">
    </form>
    </div>
</body>
</html>
