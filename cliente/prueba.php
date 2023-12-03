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
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $fechaOriginal = $_POST['date'];
        echo $fechaOriginal;
    }
    ?>
</body>

</html>