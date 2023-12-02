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
                echo "<td>".$fila['dni']."</td>";
                echo "<td>" . $fila['nombre_paciente'] . " " . $fila['apellidos_paciente'] . "</td>";
                echo "<td>" . $fila['genero'] . "</td>";
                echo "<td>" . $fila['fecha_nac'] . "</td>";
                echo "<td>" . $fila['id_med'] . "</td>";
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
        table, th, td{
            border-collapse: collapse;
            border: 1px solid black;
        }
    </style>
    <title>Paciente</title>
</head>

<body>
    <div class="info">
        <?php
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            tablificarInfo($_POST['id']);
        }
        ?>

    </div>
</body>

</html>