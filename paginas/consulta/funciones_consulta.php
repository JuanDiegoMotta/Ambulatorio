<?php
// Función que muestra la información de la consulta en una tabla
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

// Función que imprime el <textarea></textarea> de la sintomatología
function sintomasConsulta($id)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecutamos consulta para conseguir la sintomatología de la cita
            $sql = "SELECT c.sintomatologia
            FROM CONSULTA c
            WHERE c.id_consulta = '$id';
            ";
            $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
            $fila = mysqli_fetch_assoc($result);
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

// Función que imprime el <textarea></textarea> del diagnostico de la consulta
function diagnosticoConsulta($id)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();

    // Intentamos conectarnos
    try {
        if ($bd->conectar()) {
            $bd->seleccionarContexto('Ambulatorio');
            $conexion = $bd->getConexion();

            // Ejecutamos consulta para conseguir el diagnóstico de la cita
            $sql = "SELECT c.diagnostico
            FROM CONSULTA c
            WHERE c.id_consulta = '$id';
            ";
            $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
            $fila = mysqli_fetch_assoc($result);
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

// Función que imprime los <option></option> con los medicamentos disponibles
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

// Función que devuelve el id de un medicamento dado su nombre
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

// Función que añade una receta y devuelve un booleano
function anadirReceta($id_consulta, $array)
{
    // Creamos una instancia de BBDD
    $bd = new BaseDeDatos();

    // Flag que se devolverá en función del éxito de la consulta
    $flag = false;

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
                $flag = true;
            } else {
                echo "<p>Error al insertar la receta: " . mysqli_error($conexion) . "</p>";
            }
        }
        $bd->cerrar();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    return $flag;
}

// Función que devuelve el nombre de un medicamento dado su id
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

// función que tablifica el array de medicamentos añadidos por el médico
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

// Función que imprime los <option></option> de los médicos que atienden al paciente
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

// Función que devuelve el id del paciente dado un id de consulta
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

// Función que devuelve el id de un médico dado el id de consulta
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

// Función que registra una cita
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

// Función que actualiza la sintomatología y el diagnóstico de la consulta devuelve un bool
function actualizarConsulta($id_consulta, $sintomatologia, $diagnostico){
    $bd = new BaseDeDatos();
    $flag = false;
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