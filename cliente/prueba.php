<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        @import url("js/calendar-blue.css");
    </style>
    <script type="text/javascript" src="js/calendar.js"></script>
    <script type="text/javascript" src="js/calendar-es.js"></script>
    <script type="text/javascript" src="js/calendar-setup.js"></script>
    <title>Document</title>
</head>

<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

        <input type="date" name="date" id="date">
        <button type="submit">Enviar</button>
    </form>
    <?php
    $fechaActual = new DateTime(); // Obtener la fecha actual
    $numeroDias = '365'; // Número de días que deseas sumar

    $fechaResultante = $fechaActual->modify("+$numeroDias days");
    
    $fechaFinal = $fechaResultante->format('Y-m-d'); // Formatear y mostrar la fecha resultante
    echo $fechaFinal;
    ?>
</body>

</html>