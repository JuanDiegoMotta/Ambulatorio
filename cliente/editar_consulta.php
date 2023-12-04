<?php
require_once '../BBDD/conecta.php';
function infoConsulta($id)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecutamos consulta para conseguir los datos de la cita
            $sql = "SELECT c.id_consulta, m.nombre_medico, m.apellidos_medico, p.nombre_paciente, p.apellidos_paciente, c.fecha_consulta
            FROM CONSULTA c
            JOIN MEDICO m ON c.id_medico = m.id_medico
            JOIN PACIENTE p ON c.id_paciente = p.id_paciente
            WHERE c.id_consulta = '$id';
            ";
            $result = mysqli_query($conexion, $sql);
            $fila = mysqli_fetch_assoc($result);
            $id_consulta = $fila['id_consulta'];
            $nombre_medico = $fila['nombre_medico'] . " " . $fila['apellidos_medico'];
            $nombre_paciente = $fila['nombre_paciente'] . " " . $fila['apellidos_paciente'];
            $fecha_consulta = $fila['fecha_consulta'];

            // Imprimimos la tabla HTML
            echo "<table>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Médico</th>";
            echo "<th>Paciente</th>";
            echo "<th>Fecha</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>$id_consulta</td>";
            echo "<td>$nombre_medico</td>";
            echo "<td>$nombre_paciente</td>";
            echo "<td>$fecha_consulta</td>";
            echo "</tr>";
            echo "</table>";
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
function sintomasConsulta($id)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecutamos consulta para conseguir los datos de la cita
            $sql = "SELECT c.sintomatologia
            FROM CONSULTA c
            WHERE c.id_consulta = '$id';
            ";
            $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
            $fila = mysqli_fetch_assoc($result);
            echo $fila['sintomatologia'];
            if ($fila['sintomatologia'] == null || $fila['sintomatologia'] == "") {
                echo "<textarea name='sintomatologia' id='' cols='30' rows='5' maxlength='250'> </textarea>";
            } else {
                echo "<textarea name='sintomatologia' id='' cols='30' rows='5' maxlength='250'>" . $fila['sintomatologia'] . "</textarea>";
            }
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
function diagnosticoConsulta($id)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecutamos consulta para conseguir los datos de la cita
            $sql = "SELECT c.diagnostico
            FROM CONSULTA c
            WHERE c.id_consulta = '$id';
            ";
            $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
            $fila = mysqli_fetch_assoc($result);
            echo $fila['diagnostico'];
            if ($fila['diagnostico'] == null || $fila['diagnostico'] == "") {
                echo "<textarea name='diagnostico' id='' cols='30' rows='5' maxlength='250'> </textarea>";
            } else {
                echo "<textarea name='diagnostico' id='' cols='30' rows='5' maxlength='250'>" . $fila['diagnostico'] . "</textarea>";
            }
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
function opcionesMedicacion()
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecutamos consulta para conseguir los datos de los medicamentos
            $sql = "SELECT * FROM medicamento";
            $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));

            // Imprimo <option></option> correspondientes
            while ($fila = mysqli_fetch_assoc($result)) {
                $id_medicamento = $fila['id_medicamento'];
                $nombre_medicamento = $fila['nombre_medicamento'];
                echo "<option value='$nombre_medicamento'> ID: " . $id_medicamento . " Nombre: $nombre_medicamento" . "</option>";
            }
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
function conseguir_id_medicamento($nombre_medicamento)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();
    $id_medicamento = "";

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecuto consulta para conseguir id_medicamento
            $sql = "
                SELECT id_medicamento FROM medicamento WHERE nombre_medicamento = '$nombre_medicamento';
                ";
            $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
            $fila = mysqli_fetch_assoc($result);
            $id_medicamento = $fila['id_medicamento'];
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    return $id_medicamento;
}
function anadirReceta($id_consulta, $array)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Guardo en variables los datos necesarios para realizar el INSERT
            $id_medicamento = $array['id_medicamento'];
            $posologia = $array['cantidad'] . "/" . $array['frecuencia'];
            // Para conseguir la fecha_fin
            $fechaActual = new DateTime(); // Obtener la fecha actual
            $duracion = $array['duracion'];
            $fechaResultante = $fechaActual->modify("+$duracion days");
            $fecha_fin = $fechaResultante->format('Y-m-d');

            // Ejecuto consulta insert en la tabla receta
            $sql = "
            INSERT INTO receta (id_medicamento, id_consulta, posologia, fecha_fin) VALUES
            ('$id_medicamento', '$id_consulta', '$posologia', '$fecha_fin');
            ";
            if (mysqli_query($conexion, $sql)) {
                echo "<p>Receta insertada correctamente</p>";
            } else {
                echo "<p>Error al insertar la receta: " . mysqli_error($conexion) . "</p>";
            }
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
function conseguir_nombre_medicamento($id)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();
    $nombre_medicamento = "";

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecuto consulta para conseguir id_medicamento
            $sql = "
                    SELECT nombre_medicamento FROM medicamento WHERE id_medicamento = '$id';
                    ";
            $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
            $fila = mysqli_fetch_assoc($result);
            $nombre_medicamento = $fila['nombre_medicamento'];
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    return $nombre_medicamento;
}
function tablificarArray($arrayMedicacion)
{
    echo "<table>";
    echo "<tr>";
    echo "<th>Nombre_medicamento</th>";
    echo "<th>Cantidad</th>";
    echo "<th>Frecuencia</th>";
    echo "<th>Duración</th>";
    echo "</tr>";
    foreach ($arrayMedicacion as $medicacion) {
        echo "<tr>";
        foreach ($medicacion as $clave => $valor) {
            if ($clave == 'id_medicamento') {
                echo "<td>" . conseguir_nombre_medicamento($valor) . "</td>";
            } else {
                echo "<td>$valor</td>";
            }
        }
        echo "</tr>";
    }
    echo "</table>";
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

function conseguir_id_paciente($id_consulta)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();
    $id_paciente = "";

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecuto consulta para conseguir id_medicamento
            $sql = "
                    SELECT id_paciente FROM consulta WHERE id_consulta = '$id_consulta';
                    ";
            $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
            $fila = mysqli_fetch_assoc($result);
            $id_paciente = $fila['id_paciente'];
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    return $id_paciente;
}
function conseguir_id_medico($id_consulta)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();
    $id_medico = "";

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecuto consulta para conseguir id_medico
            $sql = "
                    SELECT id_medico FROM consulta WHERE id_consulta = '$id_consulta';
                    ";
            $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
            $fila = mysqli_fetch_assoc($result);
            $id_medico = $fila['id_medico'];
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    return $id_medico;
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
function actualizarConsulta($id_consulta, $sintomatologia, $diagnostico){
    $bd = new BaseDeDatos();
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecuto INSERT para agregar los datos a la tabla CONSULTA
            $sql = "
            UPDATE CONSULTA SET sintomatologia = '$sintomatologia', diagnostico = '$diagnostico'
            WHERE id_consulta = '$id_consulta';
            ";

            if (mysqli_query($conexion, $sql)) {
                echo "<p>Cita actualizada correctamente</p>";
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
    <title>Consulta</title>
</head>

<body>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['anadirMedicacion'])) {
    if (isset($_POST['arrayMedicacion'])) {
        // Guardo en variables los datos necesarios para realizar la inserción en receta
        $id = conseguir_id_medicamento($_POST['medicamento']);
        // --------------------------------------------------------------
        echo "<p>El medicamento que se va a añadir tiene por id: $id</p>";
        // ---------------------------------------------------------------
        $cantidad = $_POST['cantidad'];
        $frecuencia = $_POST['frecuencia'];
        $duracion  = (isset($_POST['cronica'])) ? '365' : $_POST['duracion'];
        // Creo un array con dichos datos
        $medicacion = array(
            'id_medicamento' => $id,
            'cantidad' => $cantidad,
            'frecuencia' => $frecuencia,
            'duracion' => $duracion
        );

        // Inserto los datos en receta
        anadirReceta($_POST['id_consulta'], $medicacion);

        // Añado los datos a $arrayMedicacion
        $arrayMedicacion = unserialize($_POST['arrayMedicacion']);
        array_push($arrayMedicacion, $medicacion);
        $arrayMedicacion = serialize($arrayMedicacion);
    } else {
        // La primera vez que se clique el botón anadirMedicacion el array $medicacion no estará creado
        $id = conseguir_id_medicamento($_POST['medicamento']);
        // --------------------------------------------------------------
        echo "<p>El medicamento que se va a añadir tiene por id: $id</p>";
        // ---------------------------------------------------------------
        $cantidad = $_POST['cantidad'];
        $frecuencia = $_POST['frecuencia'];
        $duracion  = (isset($_POST['cronica'])) ? '365' : $_POST['duracion'];

        // Guardo los datos de la medicación en un array asociativo
        $medicacion = array(
            'id_medicamento' => $id,
            'cantidad' => $cantidad,
            'frecuencia' => $frecuencia,
            'duracion' => $duracion
        );
        // inserto la receta
        anadirReceta($_POST['id_consulta'], $medicacion);
        // Creo $arrayMedicacion
        $arrayMedicacion = array($medicacion);
        $arrayMedicacion = serialize($arrayMedicacion);
    }
}
?>
    <div class="infoConsulta">
        <h2>Información consulta:</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_consulta = $_POST['id_consulta'];
            infoConsulta($id_consulta);
        }
        ?>
    </div>
    <?php
        // Si se ha clicado el botón actualizarConsulta
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizarConsulta'])){
            // Recojo las variables para hacer update de la consulta
            $id_consulta = $_POST['id_consulta'];
            $sintomatologia = $_POST['sintomatologia'];
            $diagnostico = $_POST['diagnostico'];
            echo "<p>$id_consulta</p>";
            echo "<p>$sintomatologia</p>";
            echo "<p>$diagnostico</p>";
            actualizarConsulta($id_consulta, $sintomatologia, $diagnostico);

        }
    ?>
    <div class="infoEditable">
        <h2>Información editable:</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="sintomatologia">Síntomas:</label>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id_consulta = $_POST['id_consulta'];
                sintomasConsulta($id_consulta);
            }
            ?>
            <label for="diagnostico">Diagnóstico:</label>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id_consulta = $_POST['id_consulta'];
                diagnosticoConsulta($id_consulta);
            }
            ?>
            <input type="hidden" name="id_consulta" value="<?php echo $_POST['id_consulta']; ?>">
            <?php
            if (isset($arrayMedicacion) || isset($_POST['arrayMedicacion'])) {

                $arrayMedicacion = (isset($arrayMedicacion)) ? $arrayMedicacion : $_POST['arrayMedicacion'];
                echo "<p>Se comprueba que existe arrayMedicacion y se pasa por input:hidden</p>";
                // si existe un array con las medicaciones, lo paso a string y lo envío por un input:hidden
                echo "<input type='hidden' name='arrayMedicacion' value='$arrayMedicacion'>";
            } //AÑADIR input:hidden con $arrayMedicacion si se clica el botón agendar
            ?>
            <button type="submit" name="actualizarConsulta">Actualizar consulta</button>
        </form>
    </div>
    <div class="medicacion">
        <h2>Medicación:</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validacion()">
            <label for="medicamento">Medicamentos disponibles:</label>
            <select name="medicamento" id="medicamento_v">
                <option value="" selected></option>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    opcionesMedicacion();
                }
                ?>
            </select>
            <label for="cantidad">Cantidad:</label>
            <input type="text" name="cantidad" id="cantidad_v">
            <label for="frecuencia">Frecuencia:</label>
            <input type="text" name="frecuencia" id="frecuencia_v">
            <label for="duracion">Duración (Días):</label>
            <input type="text" name="duracion" id="duracion_v">
            <label for="cronica">Crónica:</label>
            <input type="checkbox" name="cronica" id="cronica_v">
            <input type="hidden" name="id_consulta" value="<?php echo $_POST['id_consulta']; ?>">
            <?php
            if (isset($arrayMedicacion) || isset($_POST['arrayMedicacion'])) {

                $arrayMedicacion = (isset($arrayMedicacion)) ? $arrayMedicacion : $_POST['arrayMedicacion'];
                echo "<p>Se comprueba que existe arrayMedicacion y se pasa por input:hidden</p>";
                // si existe un array con las medicaciones, lo paso a string y lo envío por un input:hidden
                echo "<input type='hidden' name='arrayMedicacion' value='$arrayMedicacion'>";
            } //AÑADIR input:hidden con $arrayMedicacion si se clica el botón agendar
            ?>
            <button type="submit" name="anadirMedicacion">Añadir medicación</button>
        </form>
        <?php
        if (isset($arrayMedicacion) || isset($_POST['arrayMedicacion'])) {
            echo "<p>TABLIFICAR ARRAY AL CLICAR</p>";
            $arrayMedicacion = (isset($arrayMedicacion)) ? unserialize($arrayMedicacion) : unserialize($_POST['arrayMedicacion']);
            tablificarArray($arrayMedicacion);
        }
        ?>
    </div>
    <div class="derivarEspecialista">
        <h2>Derivar a especialista</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="formCita" onsubmit="return validacion2()">
            <label for="id_medico">Médicos asignados:</label>
            <select name="id_medico" id="medicos">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id_consulta = $_POST['id_consulta'];
                    medicosPaciente(conseguir_id_paciente($id_consulta));
                }
                ?>
            </select>
            <label for="date">Elije una fecha:</label>
            <input type="date" name="date" id="date">
            <label for="sintomatologia">Introduce los síntomas (opcional):</label>
            <textarea name="sintomatologia" id="" cols="30" rows="5" maxlength="250"></textarea>
            <?php
            if (isset($arrayMedicacion) || isset($_POST['arrayMedicacion'])) {
                echo "<p>Se comprueba que existe arrayMedicacion y se pasa por input:hidden</p>";
                // si existe un array con las medicaciones, lo paso a string y lo envío por un input:hidden
                $arrayMedicacion = serialize($arrayMedicacion);
                echo "<input type='hidden' name='arrayMedicacion' value='$arrayMedicacion'>";
            }
            ?>
            <input type="hidden" name="id_consulta" value="<?php echo $_POST['id_consulta']; ?>">
            <button type="submit" name="agendar">Agendar</button>
        </form>
        <?php
        if (isset($_POST['agendar'])) {
            $id_consulta = $_POST['id_consulta'];
            $id_medico = conseguir_id_medico($id_consulta);
            $id_paciente = conseguir_id_paciente($id_consulta);
            $fecha_consulta = $_POST['date'];
            // Guardo en sintomatología el texto del text area, si no se envió, un empty string
            $sintomatologia = (isset($_POST['sintomatologia'])) ? $_POST['sintomatologia'] : "";
            registrarCita($id_medico, $id_paciente, $fecha_consulta, $sintomatologia);
        }
        ?>
    </div>
    <script src="editar_consulta.js"></script>
</body>

</html>