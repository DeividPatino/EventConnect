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
        $query = "
            SELECT e.*, r.nombre AS proveedor_nombre, r.apellido AS proveedor_apellido, r.correo, r.telefono AS telefono_proveedor
            FROM eventos e
            JOIN registro r ON e.id_proveedor = r.id
            WHERE e.id_evento = '$id_evento'
        ";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }



    public function eliminarEvento($id_evento) {
        $id_evento = mysqli_real_escape_string($this->conn, $id_evento);

        // Eliminar primero las valoraciones
        $query_valoraciones = "DELETE FROM valoraciones WHERE id_evento = '$id_evento'";
        mysqli_query($this->conn, $query_valoraciones);

        // Luego eliminar las solicitudes
        $query_solicitudes = "DELETE FROM solicitudes WHERE id_evento = '$id_evento'";
        mysqli_query($this->conn, $query_solicitudes);

        // Finalmente, eliminar el evento
        $query_evento = "DELETE FROM eventos WHERE id_evento = '$id_evento'";
        return mysqli_query($this->conn, $query_evento);
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
        $query = "SELECT * FROM eventos";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function obtenerEventosPorCiudad($ciudad) {
        $query = "SELECT * FROM eventos WHERE lugar = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $ciudad);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function obtenerEventosPorCategoria($categoria) {
        $query = "SELECT * FROM eventos WHERE LOWER(categoria) = LOWER(?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $categoria);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function obtenerEventosPorCiudadYCategoria($ciudad, $categoria) {
        $query = "SELECT * FROM eventos WHERE LOWER(lugar) = LOWER(?) AND LOWER(categoria) = LOWER(?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $ciudad, $categoria);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }


    public function cerrarConexion() {
        mysqli_close($this->conn);
    }
}
?>