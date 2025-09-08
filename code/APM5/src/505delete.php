<?php
include 'config/database.inc.php';

$con = null;

try {
    // Conexión a la base de datos usando PDO
    $con = new PDO("mysql:host=$host;dbname=$dbname;", $usuario, $password); // Crear una nueva conexión
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el ID del campeón
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];

        // Eliminar el campeón
        $sql = "DELETE FROM campeon WHERE id = :id";
        $sentencia = $con->prepare($sql);
        $sentencia->bindParam(':id', $id);
        $sentencia->execute();

        // Redirigir de nuevo al listado
        header("Location: 505campeones.php");
        //exit;
    } else {
        echo "ID inválido.";
    }
} catch (PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
}
?>
