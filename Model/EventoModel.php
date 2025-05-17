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

    public function cerrarConexion() {
        mysqli_close($this->conn);
    }
}
?>