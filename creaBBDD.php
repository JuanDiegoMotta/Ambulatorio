<?php
// incluímos la clase BaseDeDatos mediante un require a 'conecta.php'
require_once 'conecta.php';
// Creamos una instancia
$bd = new BaseDeDatos();
try {
    // Nos conectamos
    if ($bd->conectar()) {
        $conexion = $bd->getConexion();

        // Consulta para verificar si la BBDD existe
        $sql = "SHOW DATABASES LIKE 'Ambulatorio'";
        $result = mysqli_query($conexion, $sql);

        // Comprobamos si existe, si no existe creamos la DATABASE con sus tablas.
        /**
         * Se crean las tablas teniendo en cuenta la integridad referencial, es decir, cuál debe ser el orden adecuado
         * para crear las tablas de tal forma que no se cree primero una tabla que referencie a otra. Por otro lado la estructura
         * de las tablas es la acordada en la presentación del proyecto, con pequeñas modificaciones para hacer los nombres
         * más representativos y añadiendo el campo "pdf" a la tabla consulta.
         */
        if (mysqli_num_rows($result) == 0) {
            $sql = "
            CREATE DATABASE 'Ambulatorio';
            
            USE Ambulatorio;
            
            CREATE TABLE MEDICO (
                id_medico INT PRIMARY KEY,
                nombre_medico VARCHAR(50),
                apellidos_medico VARCHAR(50),
                especialidad VARCHAR(50)
            );
        
            CREATE TABLE PACIENTE (
                id_paciente INT PRIMARY KEY,
                dni VARCHAR(15),
                nombre_paciente VARCHAR(50),
                apellidos_paciente VARCHAR(50),
                genero CHAR(1),
                fecha_nac DATE,
                id_med VARCHAR(50),
            );
        
            CREATE TABLE MEDICAMENTO (
                id_medicamento INT PRIMARY KEY,
                nombre_medicamento VARCHAR(50)
            );
        
            CREATE TABLE CONSULTA (
                id_consulta INT PRIMARY KEY,
                id_medico INT,
                id_paciente INT,
                fecha_consulta DATE,
                diagnostico VARCHAR(255),
                sintomatologia VARCHAR(255),
                pdf VARCHAR(50),
                FOREIGN KEY (id_medico) REFERENCES MEDICO(id_medico),
                FOREIGN KEY (id_paciente) REFERENCES PACIENTE(id_paciente)
            );
        
            CREATE TABLE RECETA (
                id_receta INT PRIMARY KEY,
                id_medicamento INT,
                id_consulta INT,
                posologia VARCHAR(255),
                fecha_fin DATE,
                FOREIGN KEY (id_medicamento) REFERENCES MEDICAMENTO(id_medicamento),
                FOREIGN KEY (id_consulta) REFERENCES CONSULTA(id_consulta)
            );
        ";
            // Ejecutar las consultas
            if (mysqli_multi_query($conexion, $sql)) {
                echo "Tablas creadas exitosamente.";
            } else {
                echo "Error al crear las tablas: " . mysqli_error($conexion);
            }
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
