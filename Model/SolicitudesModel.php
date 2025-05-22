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
            e.precio,
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

  public function verificarPropiedadSolicitud($idSolicitud, $idProveedor) {
    $sql = "SELECT COUNT(*) as total FROM solicitudes s
            JOIN eventos e ON s.id_evento = e.id_evento
            WHERE s.id = ? AND e.id_proveedor = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $idSolicitud, $idProveedor);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();
    return $fila['total'] > 0;
}


public function cambiarEstadoSolicitud($idSolicitud, $nuevoEstado) {
    $sql = "UPDATE solicitudes SET estado = ? WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$nuevoEstado, $idSolicitud]);
}

public function contarSolicitudesPendientes($id_proveedor) {
    $sql = "SELECT COUNT(*) AS total FROM solicitudes 
            INNER JOIN eventos ON solicitudes.id_evento = eventos.id_evento 
            WHERE eventos.id_proveedor = ? AND solicitudes.estado = 'pendiente'";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id_proveedor);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();
    return $fila['total'];
}

public function obtenerSolicitudesPorCliente($idCliente) {
    $sql = "SELECT s.id, s.estado, s.fecha_solicitud, s.pagada, e.nombre AS nombre_evento, s.mensaje, e.precio
            FROM solicitudes s
            JOIN eventos e ON s.id_evento = e.id_evento
            WHERE s.id_cliente = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

public function cancelarSolicitud($idSolicitud, $idCliente) {
    $sql = "UPDATE solicitudes SET estado = 'Cancelada' 
            WHERE id = ? AND id_cliente = ? AND estado = 'pendiente'";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $idSolicitud, $idCliente);
    return $stmt->execute();
}


  public function cerrarConexion() {
        mysqli_close($this->conn);
    }
}
?>