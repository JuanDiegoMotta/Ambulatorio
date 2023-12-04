<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-in</title>
</head>

<body>
    <!-- Formulario que recoge los datos del login -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validacion()">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario">
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" id="contrasena">
        <?php
        // Anexo el archivo conecta.php
        require_once '../BBDD/conecta.php';

        // Variables que almacenarán el usuario y la contrasena y el id en caso de ser correctos
        $usuario;
        $contrasena;
        $id;
        // Flag que marcará si el nombre y usuario introducidos son correctos
        $flag = false;

        // Variable que guardará si los datos introducidos son de un paciente o de un médico
        $tipoUsuario;

        // Compruebo si se ha clicado el botón enviar y $_POST contiene valores (a pesar de que esto ya lo compruebo en el js, pero no está de más prevenir)
        if (isset($_POST['comprobar']) & isset($_POST['usuario']) & isset($_POST['contrasena'])) {
            
            // Creo instancia BBDD
            $bd = new BaseDeDatos();
            
            // Intento conectar con la BBDD
            try{
                if($bd->conectar()){
                    $conexion = $bd->getConexion();
                    
                    // Cambio al contexto de la BBDD Ambulatorio
                    $bd->seleccionarContexto('Ambulatorio');
                    
                    // Guardo los valores del formulario en variables
                    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
                    $contrasena = mysqli_real_escape_string($conexion, $_POST['contrasena']);

                    // Compruebo si existe el usuario y la contraseña
                    $sql = "SELECT * FROM login WHERE nombre_usuario = '$usuario' AND contrasena = '$contrasena'";
                    $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));

                    if(mysqli_num_rows($result)>0){
                        $flag = true;

                        // Guardamos el tipo de usuario en la variable correspondiente
                        $fila = mysqli_fetch_assoc($result);
                        $tipoUsuario = $fila['tipo_usuario'];
                        $id = $fila['id_tabla_original'];
                        echo "<p>El usuario y contraseña son correctos.</p>";
                        
                    } else{
                        echo "<p>El usuario y contraseña introducidos no se encuentra.</p>";
                    }
                    
                    
                }
                $bd->cerrar();
            }catch(Exception $e){
                echo "Problema al conectar con la BBDD: ".$e->getMessage(); 
            }
        };
        ?>
        <button type="submit" name="comprobar" <?php echo ($flag)?"disabled":"";?>>Comprobar</button>
    </form>
    <!-- Formulario que se encarga de redirigir a la página correspondiente en función de si el tipoUsuario es paciente o médico -->
    <form action="<?php if($flag){echo ($tipoUsuario == 'm')? 'medico/index_medico.php': 'paciente/index_paciente.php';}?>" method="post">
        <?php
            // Si el usuario y la contraseña son correctos
            if($flag){
                echo "<input type='hidden' value='$id' name='id'>";
            }
        ?>
        <button type="submit" name="enviar" <?php echo ($flag)?:"disabled";?>>Entrar</button>
    </form>
    <script src="login.js"></script>
</body>

</html>