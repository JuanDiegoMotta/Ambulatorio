<?php
// Creo función para tablificar la información del médico
function tablificarInfo($id)
{

    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecutamos consulta para conseguir los datos del médico
            $sql = "SELECT * FROM medico WHERE id_medico = '$id'";
            $result = mysqli_query($conexion, $sql);

            // Imprimimos la tabla HTML
            echo "<table>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Nombre y apellidos</th>";
            echo "<th>Especialidad</th>";
            echo "</tr>";
            while ($fila = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $fila['id_medico'] . "</td>";
                echo "<td>" . $fila['nombre_medico'] . " " . $fila['apellidos_medico'] . "</td>";
                echo "<td>" . $fila['especialidad'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// Función para tablificar las citas de los próximos 7 días de un médico
function proximasCitas($id)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecutamos consulta para conseguir las citas de los próximos 7 días del médico
            $sql = "SELECT c.id_consulta, p.nombre_paciente, p.apellidos_paciente, c.fecha_consulta, c.sintomatologia
            FROM CONSULTA c
            JOIN PACIENTE p ON c.id_paciente = p.id_paciente
            WHERE c.id_medico = '$id'
            AND c.fecha_consulta BETWEEN CURDATE() + INTERVAL 1 DAY AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
            ";
            $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
            echo "Citas para la próxima semana: " . mysqli_num_rows($result);

            // Imprimimos la tabla HTML
            echo "<table>";
            echo "<tr>";
            echo "<th>ID_consulta</th>";
            echo "<th>Paciente</th>";
            echo "<th>Fecha</th>";
            echo "<th>Sintomatología</th>";
            echo "</tr>";
            while ($fila = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $fila['id_consulta'] . "</td>";
                echo "<td>" . $fila['nombre_paciente'] . " " . $fila['apellidos_paciente'] . "</td>";
                echo "<td>" . $fila['fecha_consulta'] . "</td>";
                echo "<td>" . $fila['sintomatologia'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// Función para imprimir los <option></option> correspondientes a las consultas de la fecha
function consultasDeHoy($id)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecutamos consulta para conseguir las citas de hoy
            $sql = "SELECT c.id_consulta, p.nombre_paciente, p.apellidos_paciente, c.sintomatologia
                FROM CONSULTA c
                JOIN PACIENTE p ON c.id_paciente = p.id_paciente
                WHERE c.id_medico = '$id'
                AND c.fecha_consulta = CURDATE();
                ";
            $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
            
            // Imprimo <option></option> por cada cita de hoy
            while ($fila = mysqli_fetch_assoc($result)) {
                $id_consulta = $fila['id_consulta'];
                $nombrePaciente = $fila['nombre_paciente'] . " " . $fila['apellidos_paciente'];
                $sintomatologia = $fila['sintomatologia'];
                echo "<option value='$id_consulta'>ID: $id_consulta - Paciente: $nombrePaciente - Síntomas: $sintomatologia</option>";
            }

        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}