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

            // Ejecutamos consulta para conseguir los datos del paciente
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
</body>

</html>