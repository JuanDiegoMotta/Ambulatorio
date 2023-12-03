<?php
require_once '../BBDD/conecta.php';

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
function proximasCitas($id)
{
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
function medActual($id)
{
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
function registrarCita($id_medico, $id_paciente, $fecha_consulta, $sintomatologia)
{
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
                echo "<p>Cita agendada correctamente</p>";
            } else {
                echo "Error al agendar la cita: " . mysqli_error($conexion);
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
    <title>Paciente</title>
</head>

<body>
    <div class="info">
        <h2>Información paciente</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            tablificarInfo($_POST['id']);
        }

        ?>
    </div>
    <?php
    if (isset($_POST['agendar'])) {
        $id_medico = $_POST['id_medico'];
        $id_paciente = $_POST['id'];
        $fecha_consulta = $_POST['date'];
        // Guardo en sintomatología el texto del text area, si no se envió, un empty string
        $sintomatologia = (isset($_POST['sintomatologia'])) ? $_POST['sintomatologia'] : "";
        registrarCita($id_medico, $id_paciente, $fecha_consulta, $sintomatologia);
    }
    ?>
    <div class="proximasCitas">
        <h2>Próximas citas</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            proximasCitas($_POST['id']);
        }
        ?>
    </div>
    <div class="medicacionActual">
        <h2>Medicación Actual</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            medActual($_POST['id']);
        }
        ?>
    </div>
    <div class="citasPasadas">
        <h2>Selecciona una cita pasada para ver su info</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="cita">Citas pasadas:</label>
            <select name="cita" id="cita" required>
                <option value="" selected></option>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    anterioresCitas($_POST['id']);
                }
                ?>
            </select>
            <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
            <button type="submit" name="verInfo">Ver información</button>
        </form>
        <?php
        if (isset($_POST['verInfo'])) {
            infoCita($_POST['cita']);
        }
        ?>
    </div>
    <div class="pedirCita">
        <h2>Agenda un cita:</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="formCita" onsubmit="return validacion()">
            <label for="id_medico">Médicos asignados:</label>
            <select name="id_medico" id="medicos">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    medicosPaciente($_POST['id']);
                }
                ?>
            </select>
            <label for="date">Elije una fecha:</label>
            <input type="date" name="date" id="date">
            <label for="sintomatologia">Íntroduce tus síntomas (opcional):</label>
            <textarea name="sintomatologia" id="" cols="30" rows="5" maxlength="250"></textarea>
            <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
            <button type="submit" name="agendar">Agendar</button>
        </form>
    </div>
    <script src="index_paciente.js"></script>
</body>

</html>