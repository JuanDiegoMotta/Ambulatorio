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
            }
        }
        function getConexion(){
            return $this->conexion;
        }
        // Cambio de contexto a la base indicada
        function seleccionarContexto($base){
            mysqli_select_db($this->conexion, $base) or die(mysqli_error($this->conexion));
        }
        // Comprueba si una consulta da resultados
        function comprobarExistencia($consulta){
            $result = mysqli_query($this->conexion, $consulta) or die(mysqli_error($this->conexion));
            return (mysqli_num_rows($result) > 0) ? true : false;
        }
    }
?>
