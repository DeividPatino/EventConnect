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

  public function cerrarConexion() {
        mysqli_close($this->conn);
    }
}
