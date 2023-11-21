<?php
    // Defino clase para manejar la conexión y desconexión con la BBDD
    class BaseDeDatos{
        private $servidor;
        private $usuario;
        private $password;
        private $conexion;
        public function __construct($servidor = "localhost", $usuario = "root", $password = "")
        {
            $this->servidor = $servidor;
            $this->usuario = $usuario;
            $this->password = $password;
        }
        function conectar(){
            $this->conexion = mysqli_connect($this->servidor, $this->usuario, $this->password);
            // Lanzo excepción si la conexión no es exitosa
            if(!$this->conexion){
                throw new Exception("Conexión fallida: ".mysqli_connect_error());
            }
            // Si no da error retorna true
            return true;
        }
        function cerrar(){
            // Verifica si la conexión existe antes de cerrarla
            if ($this->conexion) {
                mysqli_close($this->conexion);
                echo '<p style="color: white; font-weight: bold;">Se ha cerrado la conexión</p>';
            }
        }
        function getConexion(){
            return $this->conexion;
        }
    }
?>
