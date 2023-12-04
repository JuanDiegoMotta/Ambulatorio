<?php
// Anexamos conecta.php y funciones_paciente.php
require_once '../../BBDD/conecta.php';
require_once 'funciones_paciente.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="paciente.css">
    <link rel="shortcut icon" href="../../favicon/paciente.png" type="image/x-icon">
    <title>Paciente</title>
</head>

<body>
    <!-- Información del paciente -->
    <div class="infoPaciente">
        <h2>Información paciente</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Muestro una tabla con la información del paciente, pasando por parámetro el id del paciente
            tablificarInfo($_POST['id']);
        }

        ?>
    </div>
    <?php
    // Compruebo si se ha pulsado el botón de agendar cita
    if (isset($_POST['agendar'])) {
        // Guardo en variables los datos necesarios para hacer un INSERT de una cita
        $id_medico = $_POST['id_medico'];
        $id_paciente = $_POST['id'];
        $fecha_consulta = $_POST['date'];
        // Guardo en sintomatología el texto del text area, si no se envió, un empty string
        $sintomatologia = (isset($_POST['sintomatologia'])) ? $_POST['sintomatologia'] : "";
        $flag = registrarCita($id_medico, $id_paciente, $fecha_consulta, $sintomatologia);
    }
    ?>
    <!-- Info próximas citas -->
    <div class="proximasCitas">
        <h2>Próximas citas</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ejecuto método que muestra las citas cuya fecha es mayor a la actual
            proximasCitas($_POST['id']);
        }
        ?>
    </div>
    <!-- Info medicación actual -->
    <div class="medicacionActual">
        <h2>Medicación Actual</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ejecuto método que muestra la medicación que está tomando el paciente actualmente
            medActual($_POST['id']);
        }
        ?>
    </div>
    <!-- Info citas pasadas -->
    <div class="citasPasadas">
        <h2>Selecciona una cita pasada para ver su info</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="cita">Citas pasadas:</label>
            <!-- Select para seleccionar la cita pasada cuya información se quiere ver -->
            <select name="cita" id="cita" required>
                <option value="" selected></option>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Imprimo los <option></option> correspondientes con el método anterioresCitas($id)
                    anterioresCitas($_POST['id']);
                }
                ?>
            </select>
            <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
            <button type="submit" name="verInfo">Ver información</button>
        </form>
        <?php
        // Si se clica el botón de ver info
        if (isset($_POST['verInfo'])) {
            // Muestro la información de la cita con el método infoCita()
            infoCita($_POST['cita']);
        }
        ?>
    </div>
    <!-- Formulario para pedir una cita -->
    <div class="pedirCita">
        <h2>Agenda un cita:</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="formCita" onsubmit="return validacion()">
            <label for="id_medico">Médicos asignados:</label>
            <select name="id_medico" id="medicos">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Añado los options correspondientes a los médicos asociados del paciente
                    medicosPaciente($_POST['id']);
                }
                ?>
            </select>
            <label for="date">Elije una fecha:</label>
            <input type="date" name="date" id="date">
            <label for="sintomatologia">Introduce tus síntomas (opcional):</label>
            <textarea name="sintomatologia" id="" cols="30" rows="5" maxlength="250"></textarea>
            <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
            <button type="submit" name="agendar">Agendar</button>
            <?php
            // Compruebo si se ha pulsado el botón de agendar cita
            if (isset($_POST['agendar'])) {
                // Si se ha agendado correctamente
                if ($flag) {
                    // Muestro mensaje de éxito
                    echo "<p class='mensajeExito'>Cita agendada correctamente</p>";
                } else {
                    echo "<p class='mensajeError'>Error al agendar la cita</p>";
                }
            }
            ?>
        </form>
    </div>
    <script src="index_paciente.js"></script>
</body>

</html>