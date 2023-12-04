<?php
// Anexo conecta.php y funciones_medico.php
require_once '../../BBDD/conecta.php';
require_once 'funciones_medico.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="medico.css">
    <link rel="shortcut icon" href="../../favicon/medico.png" type="image/x-icon">
    <title>Médico</title>
</head>

<body>
    <!-- Información médico -->
    <div class="infoMedico">
        <h2>Información Médico</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_medico = $_POST['id'];
            // Ejecuto función para mostrar la información del médico
            tablificarInfo($id_medico);
        }
        ?>
    </div>
    <!-- Info próximas consultas -->
    <div class="proximasConsultas">
        <h2>Próximas consultas:</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_medico = $_POST['id'];
            // Ejecuto función para mostrar la información de las consultas del médico de los próximos 7 días 
            proximasCitas($id_medico);
        }
        ?>
    </div>
    <!-- Select para acceder a las citas de hoy -->
    <div class="citasHoy">
        <h2>Citas de hoy:</h2>
        <form action="../consulta/editar_consulta.php" method="post">
            <label for="id_consulta">Citas para hoy:</label>
            <select name="id_consulta" id="id_consulta" required>
                <option value="" selected></option>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id_medico = $_POST['id'];
                    // Imrimo los <option></option> de las citas de hoy
                    consultasDeHoy($id_medico);
                }
                ?>
            </select>
            <button type="submit" name="seleccionar">Seleccionar cita</button>
        </form>
    </div>
</body>

</html>