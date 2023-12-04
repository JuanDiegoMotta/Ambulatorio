<?php

// Creo función para tablificar la información del paciente
function tablificarInfo($id)
{

    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecutamos consulta para conseguir los datos del paciente
            $sql = "SELECT * FROM paciente WHERE id_paciente = '$id'";
            $result = mysqli_query($conexion, $sql);

            // Imprimimos la tabla HTML
            echo "<table>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>DNI</th>";
            echo "<th>Nombre y apellidos</th>";
            echo "<th>Género</th>";
            echo "<th>Fecha Nacimiento</th>";
            echo "</tr>";
            while ($fila = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $fila['id_paciente'] . "</td>";
                echo "<td>" . $fila['dni'] . "</td>";
                echo "<td>" . $fila['nombre_paciente'] . " " . $fila['apellidos_paciente'] . "</td>";
                echo "<td>" . $fila['genero'] . "</td>";
                echo "<td>" . $fila['fecha_nac'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// Función para tablificar las próximas citas de un paciente
function proximasCitas($id)
{
    // Me conecto a la BBDD
    $bd = new BaseDeDatos();
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Guardo en una variable la fecha actual
            $fechaActual = date('Y-m-d');

            // Ejecutamos consulta para conseguir los datos de consultas futuras
            $sql = "SELECT c.id_consulta, m.nombre_medico, m.apellidos_medico, c.fecha_consulta
            FROM CONSULTA c
            INNER JOIN MEDICO m ON c.id_medico = m.id_medico
            WHERE c.fecha_consulta > '$fechaActual' 
            AND c.id_paciente = '$id'";
            $result = mysqli_query($conexion, $sql);

            // Imprimimos la tabla HTML
            echo "<table>";
            echo "<tr>";
            echo "<th>ID_Cita</th>";
            echo "<th>Médico</th>";
            echo "<th>Fecha</th>";
            echo "</tr>";
            while ($fila = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $fila['id_consulta'] . "</td>";
                echo "<td>" . $fila['nombre_medico'] . " " . $fila['apellidos_medico'] . "</td>";
                echo "<td>" . $fila['fecha_consulta'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// Función para conseguir las recetas actuales de un paciente
function medActual($id)
{
    // Me conecto a BBDD
    $bd = new BaseDeDatos();
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            $fechaActual = date('Y-m-d');
            // Ejecutamos consulta para conseguir recetas de consultas cuyo id_paciente sea igual a $id y cuya fecha de fin sea posterior a la actual
            $sql =  "SELECT m.nombre_medicamento, r.posologia, r.fecha_fin
            FROM RECETA r
            INNER JOIN CONSULTA c ON r.id_consulta = c.id_consulta
            INNER JOIN MEDICAMENTO m ON r.id_medicamento = m.id_medicamento
            WHERE c.id_paciente = '$id'
            AND r.fecha_fin >= '$fechaActual'";

            $result = mysqli_query($conexion, $sql);

            // Imprimimos la tabla HTML
            echo "<table>";
            echo "<tr>";
            echo "<th>Medicamento</th>";
            echo "<th>Posología</th>";
            echo "<th>Fecha fin</th>";
            echo "</tr>";
            while ($fila = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $fila['nombre_medicamento'] . "</td>";
                echo "<td>" . $fila['posologia'] . "</td>";
                echo "<td>" . $fila['fecha_fin'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// Función para imprimir <option></option> con citas anteriores
function anterioresCitas($id)
{
    $bd = new BaseDeDatos();
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Guardo en una variable la fecha actual
            $fechaActual = date('Y-m-d');

            // Ejecutamos consulta para conseguir los datos de consultas futuras
            $sql = "SELECT c.id_consulta, c.fecha_consulta
            FROM CONSULTA c
            WHERE c.fecha_consulta < '$fechaActual' 
            AND c.id_paciente = '$id'";
            $result = mysqli_query($conexion, $sql);

            // Imprimimos los select correspondientes
            while ($fila = mysqli_fetch_assoc($result)) {
                $id_consulta = $fila['id_consulta'];
                $fecha_consulta = $fila['fecha_consulta'];
                echo "<option value='$id_consulta'>" . "ID: " . "$id_consulta" . " - Fecha: " . "$fecha_consulta</option>";
            }
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// Función que tablifica la información de una cita
function infoCita($id_consulta)
{
    $bd = new BaseDeDatos();
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecutamos consulta para conseguir los datos de consultas futuras
            $sql = "SELECT m.nombre_medico, m.apellidos_medico, c.diagnostico, c.sintomatologia, c.fecha_consulta
            FROM CONSULTA c
            INNER JOIN MEDICO m ON c.id_medico = m.id_medico
            WHERE c.id_consulta = '$id_consulta'";
            $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));

            $fila = mysqli_fetch_assoc($result);
            $nombre_medico = $fila['nombre_medico'] . " " . $fila['apellidos_medico'];
            $diagnostico = $fila['diagnostico'];
            $sintomatologia = $fila['sintomatologia'];
            $fecha_consulta = $fila['fecha_consulta'];

            echo "<h3>INFORMACIÓN CITA:</h3>";
            // Imprimimos la tabla HTML
            echo "<table>";
            echo "<tr>";
            echo "<th>ID_consulta</th>";
            echo "<th>Médico</th>";
            echo "<th>Diagnóstico</th>";
            echo "<th>Sintomatología</th>";
            echo "<th>Fecha Consulta</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>$id_consulta</td>";
            echo "<td>$nombre_medico</td>";
            echo "<td>$diagnostico</td>";
            echo "<td>$sintomatologia</td>";
            echo "<td>$fecha_consulta</td>";
            echo "</tr>";
            echo "</table>";
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// Función para imprimir los <option></option> de los médicos que atienden un paciente
function medicosPaciente($id)
{
    $bd = new BaseDeDatos();
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecutamos consulta para conseguir los datos de los médicos que atienden a un paciente
            $sql = "SELECT id_med FROM paciente WHERE id_paciente = '$id'";
            $result = mysqli_query($conexion, $sql) or die("Error consulta id_med paciente: " . mysqli_error($conexion));
            $fila = mysqli_fetch_assoc($result);
            $stringMedicos = $fila['id_med'];

            // Convertimos el string de médicos en un array
            $arrayMedicos = explode(',', $stringMedicos);

            foreach ($arrayMedicos as $id_medico) {
                // Creamos consulta que devuleva el nombre del médico y su especialidad
                $sql2 = "SELECT nombre_medico, apellidos_medico, especialidad FROM medico WHERE id_medico = '$id_medico'";
                $result = mysqli_query($conexion, $sql2) or die("Error consulta nombre y especialidad médico para option: " . mysqli_error($conexion));
                $fila = mysqli_fetch_assoc($result);
                $nombre = $fila['nombre_medico'] . " " . $fila['apellidos_medico'];
                $especialidad = $fila['especialidad'];

                // Imprimimos las <option></option> correspondientes
                if ($especialidad == 'Familia') {
                    echo "<option value='$id_medico' selected>" . $nombre . " - " . $especialidad . "</option>";
                } else {
                    echo "<option value='$id_medico'>" . $nombre . " - " . $especialidad . "</option>";
                }
            }
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// Función para registrar una cita
function registrarCita($id_medico, $id_paciente, $fecha_consulta, $sintomatologia)
{
    // Creo flag que se retornará en función del éxito o fracaso de la consulta
    $flag = false;

    // Me conecto a la BBDD
    $bd = new BaseDeDatos();
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecuto INSERT para agregar los datos a la tabla CONSULTA
            $sql = "
            INSERT INTO CONSULTA (id_medico, id_paciente, fecha_consulta, sintomatologia) VALUES
            ('$id_medico', '$id_paciente', '$fecha_consulta', '$sintomatologia');
            ";

            if (mysqli_query($conexion, $sql)) {
                // Si se ejecuta correctamente
                $flag = true;
            } else {
                echo "Error al agendar la cita: " . mysqli_error($conexion);
            }
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    return $flag;
}