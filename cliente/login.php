<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-in</title>
</head>

<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validacion()">
        <label for="usuario">Usuario (DNI):</label>
        <input type="text" name="usuario" id="usuario">
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" id="contrasena">
        <?php
        // Anexo el archivo conecta.php
        require_once '../BBDD/conecta.php';

        // Compruebo si se ha clicado el botón enviar y $_POST contiene los valores correspondientes (a pesar de que esto ya lo compruebo en el js, pero no está de más prevenir)
        if (isset($_POST['enviar']) & isset($_POST['usuario']) & isset($_POST['contrasena'])) {
            
            // Guardo los valores del formulario en variables
            $usuario = $_POST['usuario'];
            $contrasena = $_POST['contrasena'];

        };
        ?>
        <button type="submit" name="enviar">Entrar</button>
    </form>
    <script src="login.js"></script>
</body>

</html>