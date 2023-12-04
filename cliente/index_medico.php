<?php
require_once '../BBDD/conecta.php';

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
            AND c.fecha_consulta BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY);
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

function consultasDeHoy($id)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecutamos consulta para conseguir las citas de los próximos 7 días del médico
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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table,
        th,
        td {
            border-collapse: collapse;
            border: 1px solid black;
        }
    </style>
    <title>Médico</title>
</head>

<body>
    <div class="infoMedico">
        <h2>Información Médico</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_medico = $_POST['id'];
            tablificarInfo($id_medico);
        }
        ?>
    </div>
    <div class="proximasConsultas">
        <h2>Próximas consultas:</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_medico = $_POST['id'];
            proximasCitas($id_medico);
        }
        ?>
    </div>
    <div class="citasHoy">
        <h2>Citas de hoy:</h2>
        <form action="editar_consulta.php" method="post">
            <label for="id_consulta">Citas para hoy:</label>
            <select name="id_consulta" id="id_consulta" required>
                <option value="" selected></option>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id_medico = $_POST['id'];
                    consultasDeHoy($id_medico);
                }
                ?>
            </select>
            <button type="submit" name="seleccionar">Seleccionar cita</button>
        </form>
    </div>
</body>

</html>