<?php
// incluímos la clase BaseDeDatos mediante un require a 'conecta.php'
require_once 'conecta.php';

// Funcion comprobar si existe
function existeAmbulatorio()
{
    // Creo instancia conexión bbdd
    $bd = new BaseDeDatos();
    $flag = false;
    try {
        if ($bd->conectar()) {
            $conexion = $bd->getConexion();
            // Consulta para verificar si la BBDD existe
            $sql = "SHOW DATABASES LIKE 'Ambulatorio'";
            $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
            if (mysqli_num_rows($result) != 0) {
               $flag = true;
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    $bd->cerrar();
    return $flag;
}
// Función crearTablas
function crearTablas()
{
    // Creamos una instancia
    $bd = new BaseDeDatos();
    try {
        // Nos conectamos
        if ($bd->conectar()) {
            $conexion = $bd->getConexion();
            /**
             * Se crean las tablas teniendo en cuenta la integridad referencial, es decir, cuál debe ser el orden adecuado
             * para crear las tablas de tal forma que no se cree primero una tabla que referencie a otra. Por otro lado la estructura
             * de las tablas es la acordada en la presentación del proyecto, con pequeñas modificaciones para hacer los nombres
             * más representativos y añadiendo el campo "pdf" a la tabla consulta.
             */
            /**
             * La tabla LOGIN se crea para almacenar información de inicio de sesión. Esta tabla se utiliza para autenticar a médicos y 
             * pacientes en la aplicación. Los campos incluyen id_usuario, nombre_usuario, contrasena, tipo_usuario e id_tabla_original.
             */

            $sql = "
                CREATE DATABASE Ambulatorio;

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
                    id_med VARCHAR(50)
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

                CREATE TABLE LOGIN (
                    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
                    nombre_usuario VARCHAR(50),
                    contrasena VARCHAR(50),
                    tipo_usuario CHAR(1),
                    id_tabla_original INT
                );
                ";
            // Ejecutar las consultas de creación de BBDD y tablas
            if (mysqli_multi_query($conexion, $sql)) {
                echo "Tablas creadas exitosamente.";
            } else {
                echo "Error al crear las tablas: " . mysqli_error($conexion);
            }

            $bd->cerrar();
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
// Función insertarDatos
function insertarDatos()
{
    // Creamos una instancia
    $bd = new BaseDeDatos();
    try {
        // Nos conectamos
        if ($bd->conectar()) {
            $conexion = $bd->getConexion();

            $sql_insert = "
                USE Ambulatorio;

                INSERT INTO MEDICO (nombre_medico, apellidos_medico, especialidad) VALUES 
                ('Dr. Juan', 'Gómez', 'Cardiología'),
                ('Dra. María', 'López', 'Pediatría'),
                ('Dr. Carlos', 'Martínez', 'Dermatología'),
                ('Dra. Laura', 'Rodríguez', 'Ginecología'),
                ('Dr. Pablo', 'Motos', 'Familia'),
                ('Dra. Lucía', 'Pozo', 'Familia'),
                ('Dr. Lucca', 'Chancleta', 'Neurología'),
                ('Dr. Juan', 'Motta', 'Cirujía plástica'),
                ('Dra. Paula', 'Cabello', 'Oftalmología');
                
                INSERT INTO PACIENTE (dni, nombre_paciente, apellidos_paciente, genero, fecha_nac, id_med) VALUES 
                ('78901234D', 'Pedro', 'Gómez', 'M', '1978-11-25', '1,5,7'),
                ('12345678A', 'Ana', 'Sánchez', 'F', '2010-05-15', '2,5,8'),
                ('98765432B', 'Juan', 'Pérez', 'M', '1985-08-20', '3,6,9'),
                ('45678901C', 'María', 'González', 'F', '2000-02-10', '4,6,7');
                
                INSERT INTO MEDICAMENTO (nombre_medicamento) VALUES 
                ('Enalapril'),
                ('Amoxicilina'),
                ('Loratadina'),
                ('Doxilamina-piridoxina'),
                ('Antrax'),
                ('Benzoilmetilecgonina'),
                ('Desoxiefedrina'),
                ('Aspirina');
                
                INSERT INTO CONSULTA (id_medico, id_paciente, fecha_consulta, diagnostico, sintomatologia) VALUES 
                (1, 1, '2023-12-1', 'Hipertensión arterial', 'Dolor de cabeza, mareos'),
                (5, 1, '2023-12-4', '','Dolor de barriga'),
                (7, 1, '2023-12-8', '','Tartamudeo incesante'),
                (2, 2, '2023-11-15', 'Infección respiratoria', 'Fiebre, tos'),
                (5, 2, '2023-12-4', '','Picazón en la garganta'),
                (8, 2, '2023-12-7', '','Insomnio'),
                (3, 3, '2023-11-26', 'Dermatitis', 'Picazón, enrojecimiento'),
                (6, 3, '2023-12-4', '','Dolor muscular'),
                (9, 3, '2023-12-7', '','Alergia'),
                (4, 4, '2023-11-29', 'hiperémesis gravídica', 'Náuseas y vómitos debido al embarazo'),
                (6, 4, '2023-12-4', '','Debilidad y cansancio'),
                (7, 4, '2023-12-8', '', 'Alzeimer temporal');
                
                INSERT INTO RECETA (id_medicamento, id_consulta, posologia, fecha_fin) VALUES 
                (1, 1, '1cap/noche-7dias', '2023-12-8'),
                (5, 1, '1cap/mañanas', '2023-12-8'),
                (2, 4, '2cap/despuesdecomer', '2024-01-15'),
                (6, 4, '2sobres/desayuno', '2024-01-7'),
                (3, 7, '1cap/12h-10d', '2023-12-23'),
                (7, 7, 'pomada/12h-1m', '2023-12-26'),
                (4, 10, '1cap/noche-6d', '2023-12-14'),
                (8, 10, '1cap/dia-6d', '2023-12-10');
                
                
                INSERT INTO LOGIN (nombre_usuario, contrasena, tipo_usuario, id_tabla_original) VALUES
                ('gomezjuan', 'WW2JZf%D', 'm', 1),
                ('lopezmaria','%97FuZ9p', 'm', 2),
                ('martinezcarlos', '%ZVnM8n6', 'm', 3),
                ('rodriguezlaura', '5y!m79TN', 'm', 4),
                ('motospablo', '12345', 'm', 5),
                ('pozolucia', 'asdfjk', 'm',  6),
                ('gomezpedro', 'sJPv7L&^', 'p', 1),
                ('sanchezana', 'MTpdg3^J', 'p', 2),
                ('perezjuan', 'Zj#S%Nv3', 'p', 3),
                ('gonzalezmaria', '!R4CyhzT', 'p', 4),
                ('chancletalucca', 'jsklmn1', 'm', 7),
                ('mottajuan', 'goat23', 'm', 8),
                ('cabellopaula', 'p4bl0j4j4', 'm', 9);
                
                ";
            // Ejecutar las consultas de inserción de datos
            if (mysqli_multi_query($conexion, $sql_insert)) {
                echo "Datos insertados correctamente";
            } else {
                echo "Error al insertar los datos:" . mysqli_error($conexion);
            }

            $bd->cerrar();
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
// Crea tablas e inserta datos solo si no existe Ambulatorio
if(!existeAmbulatorio()){   
    crearTablas();
    insertarDatos();
}
