<?php
class EventoModel {
    private $conn;

    public function __construct(){
        $this->conn = mysqli_connect("localhost", "root", "", "eventconnect");
        if(!$this->conn) {
            die("Error  no: " . mysqli_connect_errno() . " - " . mysqli_connect_error());
        }
    }

    public function publicarevento($nombre, $descripcion, $categoria, $lugar, $precio, $imagen, $id_proveedor){
        $query = "INSERT INTO eventos (nombre, descripcion, categoria, lugar, precio, imagen, id_proveedor) 
                  VALUES('" . mysqli_real_escape_string($this->conn, $nombre) ."',   
                            '" . mysqli_real_escape_string($this->conn, $descripcion) ."',
                            '". mysqli_real_escape_string($this->conn, $categoria) ."',
                            '". mysqli_real_escape_string($this->conn, $lugar) ."',
                            '". mysqli_real_escape_string($this->conn, $precio) ."',
                            '". mysqli_real_escape_string($this->conn, $imagen) ."',
                            '". mysqli_real_escape_string($this->conn, $id_proveedor) ."')";

        return mysqli_query($this->conn, $query);      
    }

    public function obtenerEventosPorProveedor($id_proveedor) {
        $query = "SELECT * FROM eventos WHERE id_proveedor = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_proveedor);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function obtenerEventoPorId($id_evento) {
        $id_evento = mysqli_real_escape_string($this->conn, $id_evento);
        $query = "SELECT * FROM eventos WHERE id_evento = '$id_evento'";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }

    public function eliminarEvento($id_evento) {
        $id_evento = mysqli_real_escape_string($this->conn, $id_evento);
        $query = "DELETE FROM eventos WHERE id_evento = '$id_evento'";
        return mysqli_query($this->conn, $query);
    }
    
    public function editarEvento($id_evento, $nombre, $descripcion, $categoria, $lugar, $precio, $imagen) {
        $query = "UPDATE eventos SET 
                    nombre = '" . mysqli_real_escape_string($this->conn, $nombre) . "',
                    descripcion = '" . mysqli_real_escape_string($this->conn, $descripcion) . "',
                    categoria = '" . mysqli_real_escape_string($this->conn, $categoria) . "',
                    lugar = '" . mysqli_real_escape_string($this->conn, $lugar) . "',
                    precio = '" . mysqli_real_escape_string($this->conn, $precio) . "',
                    imagen = '" . mysqli_real_escape_string($this->conn, $imagen) . "'
                WHERE id_evento = '" . mysqli_real_escape_string($this->conn, $id_evento) . "'";

        return mysqli_query($this->conn, $query);
    }

    public function obtenerTodosLosEventos() {
        $query = "SELECT * FROM eventos ORDER BY id_evento DESC";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function cerrarConexion() {
        mysqli_close($this->conn);
    }
}
?>