<?php
class ValoracionModel {
  private $conn;

  public function __construct() {
        $this->conn = mysqli_connect("localhost", "root", "", "eventconnect");
        if (!$this->conn) {
            die("Error no: " . mysqli_connect_errno() . " - " . mysqli_connect_error());
        }
    }

    public function guardarValoracion($idEvento, $idUsuario, $puntuacion, $comentario) {
        $stmt = $this->conn->prepare("INSERT INTO valoraciones (id_evento, id_usuario, puntuacion, comentario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $idEvento, $idUsuario, $puntuacion, $comentario);
        return $stmt->execute();
    }

    public function obtenerValoracionesPorEvento($idEvento) {
        $stmt = $this->conn->prepare("SELECT v.puntuacion, v.comentario, v.fecha, r.nombre 
                                      FROM valoraciones v 
                                      JOIN registro r ON v.id_usuario = r.id 
                                      WHERE v.id_evento = ?
                                      ORDER BY v.fecha DESC");
        $stmt->bind_param("i", $idEvento);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPromedioPorEvento($idEvento) {
        $stmt = $this->conn->prepare("SELECT ROUND(AVG(puntuacion), 1) as promedio FROM valoraciones WHERE id_evento = ?");
        $stmt->bind_param("i", $idEvento);
        $stmt->execute();
        $stmt->bind_result($promedio);
        $stmt->fetch();
        return $promedio ?: 0;
    }

    public function cerrarConexion() {
        $this->conn->close();
    }
}

?>