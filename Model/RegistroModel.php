<?php
class RegistroModel {
    private $conn;

    public function __construct() {
        $this->conn = mysqli_connect("localhost", "root", "", "eventconnect");
        if (!$this->conn) {
            die("Error no: " . mysqli_connect_errno() . " - " . mysqli_connect_error());
        }
    }

    public function verificarCorreo($correo) {
        $query = "SELECT id FROM registro WHERE correo = '" . mysqli_real_escape_string($this->conn, $correo) . "'";
        $result = mysqli_query($this->conn, $query);
        return mysqli_num_rows($result) > 0;
    }

    public function registrarUsuario($nombre, $apellido, $correo, $contraseña, $tipo) {
        $query = "INSERT INTO registro(nombre, apellido, correo, contraseña, tipo) 
                  VALUES('" . mysqli_real_escape_string($this->conn, $nombre) . "', 
                         '" . mysqli_real_escape_string($this->conn, $apellido) . "', 
                         '" . mysqli_real_escape_string($this->conn, $correo) . "', 
                         '" . mysqli_real_escape_string($this->conn, $contraseña) . "', 
                         '" . mysqli_real_escape_string($this->conn, $tipo) . "')";
        return mysqli_query($this->conn, $query);
    }

    public function obtenerUsuarioPorCorreo($correo) {
        $query = "SELECT * FROM registro WHERE correo = '" . mysqli_real_escape_string($this->conn, $correo) . "'";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }

    public function cerrarConexion() {
        mysqli_close($this->conn);
    }
}
?>
