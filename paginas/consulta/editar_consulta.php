<?php
// anexamos conecta.php y funciones_consulta.php
require_once '../../BBDD/conecta.php';
require_once 'funciones_consulta.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="consulta.css">
    <link rel="shortcut icon" href="../../favicon/consulta.png" type="image/x-icon">
    <title>Consulta</title>
</head>

<body>
    <?php
    // Si se ha clicado el botón añadir Medicación
    if (isset($_POST['anadirMedicacion'])) {
        if (isset($_POST['arrayMedicacion'])) {
            // Guardo en variables los datos necesarios para realizar la inserción en receta
            $id = conseguir_id_medicamento($_POST['medicamento']);
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
            $flag = anadirReceta($_POST['id_consulta'], $medicacion);

            // Añado los datos a $arrayMedicacion
            $arrayMedicacion = unserialize($_POST['arrayMedicacion']);
            array_push($arrayMedicacion, $medicacion);
            // La paso a string
            $arrayMedicacion = serialize($arrayMedicacion);
        } else {
            // La primera vez que se clique el botón anadirMedicacion el array $medicacion no estará creado
            $id = conseguir_id_medicamento($_POST['medicamento']);
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
            $flag = anadirReceta($_POST['id_consulta'], $medicacion);
            // Creo $arrayMedicacion
            $arrayMedicacion = array($medicacion);
            // La paso a string
            $arrayMedicacion = serialize($arrayMedicacion);
        }
    }
    ?>
    <!-- Información consulta -->
    <div class="infoConsulta">
        <h2>Información consulta:</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_consulta = $_POST['id_consulta'];
            // Método que imprime la info de la consulta
            infoConsulta($id_consulta);
        }
        ?>
    </div>
    <?php
    // Si se ha clicado el botón actualizarConsulta
    if (isset($_POST['actualizarConsulta'])) {
        // Recojo las variables para hacer update de la consulta
        $id_consulta = $_POST['id_consulta'];
        $sintomatologia = $_POST['sintomatologia'];
        $diagnostico = $_POST['diagnostico'];
        // actualizo la consulta
        $flag = actualizarConsulta($id_consulta, $sintomatologia, $diagnostico);
    }
    ?>
    <!-- Información editable consulta: sintomatología y diagnóstico -->
    <div class="infoEditable">
        <h2>Información editable:</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="sintomatologia">Síntomas:</label>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id_consulta = $_POST['id_consulta'];
                // Función que imprime el <textarea></textarea> de la sintomatología
                sintomasConsulta($id_consulta);
            }
            ?>
            <label for="diagnostico">Diagnóstico:</label>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id_consulta = $_POST['id_consulta'];
                // Función que imprime el <textarea></textarea> del diagnostico de la consulta
                diagnosticoConsulta($id_consulta);
            }
            ?>
            <input type="hidden" name="id_consulta" value="<?php echo $_POST['id_consulta']; ?>">
            <?php
            // Si existe $arrayMedicacion o $_POST['arrayMedicacion]
            if (isset($arrayMedicacion) || isset($_POST['arrayMedicacion'])) {
                // Guardamos el $arrayMedicacion en función de cuál sea la variable que lo contenga
                $arrayMedicacion = (isset($arrayMedicacion)) ? $arrayMedicacion : $_POST['arrayMedicacion'];
                // si existe un array con las medicaciones, lo paso a string y lo envío por un input:hidden
                echo "<input type='hidden' name='arrayMedicacion' value='$arrayMedicacion'>";
            }
            ?>
            <?php
            // Si se ha clicado el botón actualizarConsulta, mostramos mensaje de éxito o fracaso
            if (isset($_POST['actualizarConsulta'])) {
                if ($flag) {
                    echo "<p class='mensajeExito'>Consulta actualizada correctamente.</p>";
                } else {
                    echo "<p>Error al actualizar consulta</p>";
                }
            }
            ?>
            <button type="submit" name="actualizarConsulta">Actualizar consulta</button>
        </form>
    </div>
    <!-- Sección para agregar receta -->
    <div class="medicacion">
        <h2>Medicación:</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validacion()">
            <label for="medicamento">Medicamentos disponibles:</label>
            <select name="medicamento" id="medicamento_v">
                <option value="" selected></option>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Función que imprime los <option></option> con los medicamentos disponibles
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
            // Si existe $arrayMedicacion o $_POST['arrayMedicacion]
            if (isset($arrayMedicacion) || isset($_POST['arrayMedicacion'])) {

                $arrayMedicacion = (isset($arrayMedicacion)) ? $arrayMedicacion : $_POST['arrayMedicacion'];
                // si existe un array con las medicaciones, lo paso a string y lo envío por un input:hidden
                echo "<input type='hidden' name='arrayMedicacion' value='$arrayMedicacion'>";
            } //AÑADIR input:hidden con $arrayMedicacion si se clica el botón agendar
            ?>
            <button type="submit" name="anadirMedicacion">Añadir medicación</button>
        </form>
        <?php
        // Si existe $_POST['anadirMedicacion']
        if (isset($_POST['anadirMedicacion'])) {
            if ($flag) {
                echo "<p class='mensajeExito'>Medicación añadida correctamente a RECETA.</p>";
            }
        }
        // Si existe $arrayMedicacion o $_POST['arrayMedicacion]
        if (isset($arrayMedicacion) || isset($_POST['arrayMedicacion'])) {
            // Guardamos el array en $arrayMedicacion
            $arrayMedicacion = (isset($arrayMedicacion)) ? unserialize($arrayMedicacion) : unserialize($_POST['arrayMedicacion']);
            // Tablificamos el array
            tablificarArray($arrayMedicacion);
        }
        ?>
    </div>
    <!-- Sección para agendar cita con otro médico -->
    <div class="derivarEspecialista">
        <h2>Derivar a especialista</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="formCita" onsubmit="return validacion2()">
            <label for="id_medico">Médicos asignados:</label>
            <select name="id_medico" id="medicos">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id_consulta = $_POST['id_consulta'];
                    // Función que imprime los <option></option> de los médicos que atienden al paciente
                    medicosPaciente(conseguir_id_paciente($id_consulta));
                }
                ?>
            </select>
            <label for="date">Elije una fecha:</label>
            <input type="date" name="date" id="date">
            <label for="sintomatologia">Introduce los síntomas (opcional):</label>
            <textarea name="sintomatologia" id="" cols="30" rows="5" maxlength="250"></textarea>
            <?php
            // Si existe $arrayMedicacion o $_POST['arrayMedicacion]
            if (isset($arrayMedicacion) || isset($_POST['arrayMedicacion'])) {
                // si existe un array con las medicaciones, lo paso a string y lo envío por un input:hidden
                $arrayMedicacion = serialize($arrayMedicacion);
                echo "<input type='hidden' name='arrayMedicacion' value='$arrayMedicacion'>";
            }
            ?>
            <input type="hidden" name="id_consulta" value="<?php echo $_POST['id_consulta']; ?>">
            <button type="submit" name="agendar">Agendar</button>
        </form>
        <?php
        // Si se ha clicado el botón de agendar
        if (isset($_POST['agendar'])) {
            // Recojo los datos necesarios para realizar la consulta
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