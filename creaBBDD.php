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
        if (mysqli_num_rows($result) == 0) {
            $sql = "
                -- Query 0: Crear BBDD Ambulatorio
                CREATE DATABASE Ambulatorio;

                -- Cambio al contexto de la nueva BBDD
                USE Ambulatorio;

                -- Query 1: Crear tabla Medico
                CREATE TABLE Medico (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    Nombre VARCHAR(255),
                    Apellidos VARCHAR(255),
                    Especialidad VARCHAR(255)
                );
            
                -- Query 2: Crear tabla Paciente
                CREATE TABLE Paciente (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    DNI VARCHAR(10) UNIQUE,
                    Nombre VARCHAR(255),
                    Apellidos VARCHAR(255),
                    Genero CHAR(1),
                    Fecha_nac DATE,
                    id_med VARCHAR(255)
                );
            
                -- Query 3: Crear tabla Medicamento
                CREATE TABLE Medicamento (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    Medicamento VARCHAR(255)
                );
            
                -- Query 4: Crear tabla Consulta
                CREATE TABLE Consulta (
                    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
                    id_medico INT,
                    id_paciente INT,
                    Fecha_consulta DATE,
                    Diagnostico VARCHAR(255),
                    Sintomatologia TEXT,
                    FOREIGN KEY (id_medico) REFERENCES Medico(id),
                    FOREIGN KEY (id_paciente) REFERENCES Paciente(id)
                );
            
                -- Query 5: Crear tabla Receta
                CREATE TABLE Receta (
                    id_medicamento INT,
                    id_consulta INT,
                    Posologia VARCHAR(255),
                    Fecha_fin DATE,
                    FOREIGN KEY (id_medicamento) REFERENCES Medicamento(id),
                    FOREIGN KEY (id_consulta) REFERENCES Consulta(id_consulta)
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
