<?php
include 'config/database.inc.php';

$con = null;

try {
    // Conexión a la base de datos usando PDO
    $con = new PDO("mysql:host=$host;dbname=$dbname;", $usuario, $password); // Crear una nueva conexión
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener todos los campeones
    $sql = "SELECT * FROM campeon";
    $sentencia = $con->prepare($sql); // Preparar la consulta
    $sentencia->execute();            // Ejecutar la consulta

    // Obtener resultados
    $campeones = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    // Mostrar resultados
    echo "<h1>Lista de Campeones de League of Legends</h1>";
    echo "<ul>";
    foreach ($campeones as $campeon) {
        echo "<li>";
        echo "<strong>Nombre:</strong> " . htmlspecialchars($campeon['nombre']) . "<br>"; // Evitar XSS
        echo "<strong>Rol:</strong> " . htmlspecialchars($campeon['rol']) . "<br>";
        echo "<strong>Dificultad:</strong> " . htmlspecialchars($campeon['dificultad']) . "<br>";
        echo "<strong>Descripción:</strong> " . htmlspecialchars($campeon['descripcion']);
        echo "</li><br>";
    }
    echo "</ul>";
} catch (PDOException $e) {
    // Manejo de errores
    echo "Error al conectar a la base de datos $dbname: " . $e->getMessage();
}
?>
