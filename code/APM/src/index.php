<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hola IES MRE</title>
    <style>
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 80%;
        }
    </style>
</head>
<body>
    <h1  style="text-align: center;">
        <?= "Hola alumn@s de DAW desde CataDAW"; ?>
    </h1>

    <img src="./img/iesmre_fp3.png" alt="IES MRE FP" class="center" style="width:20%;height:20%;">

    <h1  style="text-align: center;">
        <?php echo "servidor<br/>Apache + Php + MySql + phpMyAdmin"; ?>
    </h1>

    <p style="text-align: center;">
        <?= "Hoy estamos a " . date("d/m/Y");  ?>
    </p>
</body>
</html>
