<?php
class SolicitudesModel {
  private $conn;

  public function __construct() {
        $this->conn = mysqli_connect("localhost", "root", "", "eventconnect");
        if (!$this->conn) {
            die("Error no: " . mysqli_connect_errno() . " - " . mysqli_connect_error());
        }
    }

  public function crearSolicitud($idCliente, $idEvento, $mensaje) {
    $sql = "INSERT INTO solicitudes (id_cliente, id_evento, mensaje) VALUES (?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$idCliente, $idEvento, $mensaje]);
  }

  public function obtenerSolicitudesPorProveedor($idProveedor) {
  $sql = "SELECT 
            s.id,
            r.nombre AS nombre_cliente,
            e.nombre AS nombre_evento,
            s.mensaje,
            s.fecha_solicitud,
            s.estado
          FROM solicitudes s
          JOIN registro r ON s.id_cliente = r.id
          JOIN eventos e ON s.id_evento = e.id_evento
          WHERE e.id_proveedor = ?";
  
  $stmt = $this->conn->prepare($sql);
  $stmt->bind_param("i", $idProveedor);
  $stmt->execute();
  $resultado = $stmt->get_result();
  return $resultado->fetch_all(MYSQLI_ASSOC);
}


  public function cerrarConexion() {
        mysqli_close($this->conn);
    }
}
