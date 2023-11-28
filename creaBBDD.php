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
                id_medico INT PRIMARY KEY AUTO_INCREMENT,
                nombre_medico VARCHAR(50),
                apellidos_medico VARCHAR(50),
                especialidad VARCHAR(50)
            );
        
            CREATE TABLE PACIENTE (
                id_paciente INT PRIMARY KEY AUTO_INCREMENT,
                dni VARCHAR(15),
                nombre_paciente VARCHAR(50),
                apellidos_paciente VARCHAR(50),
                genero CHAR(1),
                fecha_nac DATE,
                id_med VARCHAR(50),
            );
        
            CREATE TABLE MEDICAMENTO (
                id_medicamento INT PRIMARY KEY AUTO_INCREMENT,
                nombre_medicamento VARCHAR(50)
            );
        
            CREATE TABLE CONSULTA (
                id_consulta INT PRIMARY KEY AUTO_INCREMENT,
                id_medico INT,
                id_paciente INT,
                fecha_consulta DATE,
                diagnostico VARCHAR(255),
                sintomatologia VARCHAR(255),
                pdf VARCHAR(50) DEFAULT '',
                FOREIGN KEY (id_medico) REFERENCES MEDICO(id_medico),
                FOREIGN KEY (id_paciente) REFERENCES PACIENTE(id_paciente)
            );
        
            CREATE TABLE RECETA (
                id_receta INT PRIMARY KEY AUTO_INCREMENT,
                id_medicamento INT,
                id_consulta INT,
                posologia VARCHAR(255),
                fecha_fin DATE,
                FOREIGN KEY (id_medicamento) REFERENCES MEDICAMENTO(id_medicamento),
                FOREIGN KEY (id_consulta) REFERENCES CONSULTA(id_consulta)
            );
            ";
            // Ejecutar las consultas de creación de BBDD y tablas
            if (mysqli_multi_query($conexion, $sql)) {
                echo "Tablas creadas exitosamente.";
            } else {
                echo "Error al crear las tablas: " . mysqli_error($conexion);
            }

            // Después insertamos al menos 4 datos en cada tabla
            $sql_insert = "
            INSERT INTO MÉDICO (nombre_medico, apellidos_medico, especialidad) VALUES 
                ('Dr. Juan', 'Gómez', 'Cardiología'),
                ('Dra. María', 'López', 'Pediatría'),
                ('Dr. Carlos', 'Martínez', 'Dermatología'),
                ('Dra. Laura', 'Rodríguez', 'Ginecología');
        
            INSERT INTO PACIENTE (dni, nombre_paciente, apellidos_paciente, genero, fecha_nac, id_med) VALUES 
                ('78901234D', 'Pedro', 'Gómez', 'M', '1978-11-25', 1);
                ('12345678A', 'Ana', 'Sánchez', 'F', '2010-05-15', 2),
                ('98765432B', 'Juan', 'Pérez', 'M', '1985-08-20', 2),
                ('45678901C', 'María', 'González', 'F', '2000-02-10', 3),
        
            INSERT INTO MEDICAMENTO (nombre_medicamento) VALUES 
                ('Enalapril'),
                ('Amoxicilina'),
                ('Loratadina'),
                ('Aspirina');
        
            INSERT INTO CONSULTA (id_medico, id_paciente, fecha_consulta, diagnostico, sintomatologia) VALUES 
                (1, 1, '2022-01-10', 'Hipertensión arterial', 'Dolor de cabeza, mareos'),
                (2, 2, '2022-02-15', 'Infección respiratoria', 'Fiebre, tos'),
                (3, 3, '2022-03-20', 'Dermatitis', 'Picazón, enrojecimiento'),
                (4, 4, '2022-04-25', 'Control prenatal', 'Embarazo normal');
        
            INSERT INTO RECETA (id_medicamento, id_consulta, posologia, fecha_fin) VALUES 
                (1, 1, '1cap/8h-7d', '2022-01-17'),
                (2, 2, '2cap/mañana-2m', '2022-04-15'),
                (3, 3, '1cap/12h-10d', '2022-03-30'),
                (4, 4, '1cap/noche-5d', '2022-04-30');
            ";
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
