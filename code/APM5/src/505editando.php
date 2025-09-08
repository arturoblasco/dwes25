<link rel="stylesheet" href="css/estilos.css">
<?php
include 'config/database.inc.php';

$con = null;

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;", $usuario, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el ID del campeón desde la URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Consultar los datos del campeón
        $sql = "SELECT * FROM campeon WHERE id = :id";
        $sentencia = $con->prepare($sql);
        $sentencia->bindParam(':id', $id);
        $sentencia->execute();

        $campeon = $sentencia->fetch(PDO::FETCH_ASSOC);

        // Si el campeón no existe
        if (!$campeon) {
            echo "El campeón con ID $id no existe.";
            exit;
        }
    } else {
        echo "No se proporcionó un ID válido.";
        exit;
    }

    // Guardar cambios en la base de datos
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $rol = $_POST['rol'];
        $dificultad = $_POST['dificultad'];
        $descripcion = $_POST['descripcion'];

        $sql = "UPDATE campeon 
                SET nombre = :nombre, rol = :rol, dificultad = :dificultad, descripcion = :descripcion 
                WHERE id = :id";
        $sentencia = $con->prepare($sql);
        $sentencia->bindParam(':nombre', $nombre);
        $sentencia->bindParam(':rol', $rol);
        $sentencia->bindParam(':dificultad', $dificultad);
        $sentencia->bindParam(':descripcion', $descripcion);
        $sentencia->bindParam(':id', $id, PDO::PARAM_INT);

        if ($sentencia->execute()) {
            header("Location: 505campeones.php"); // Redirige a la lista de campeones
            exit;
        } else {
            echo "Error al guardar los cambios.";
        }
    }
} catch (PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Campeón</title>
</head>
<body>
    <h3>Editar Campeón</h3>
    <form method="POST">
        <label for="nombre">Nombre:</label><br>
        <input type="text" size='40' id="nombre" name="nombre" 
                value="<?= htmlspecialchars($campeon['nombre']) ?>" required><br><br>
        
        <label for="rol">Rol:</label><br>
        <input type="text" size='40' id="rol" name="rol" 
                value="<?= htmlspecialchars($campeon['rol']) ?>" required><br><br>
        
        <label for="dificultad">Dificultad:</label><br>
        <input type="text" size='40' id="dificultad" name="dificultad" 
                value="<?= htmlspecialchars($campeon['dificultad']) ?>" required><br><br>
        
        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="descripcion" 
                rows="5" cols="39"><?= htmlspecialchars($campeon['descripcion']) ?>
        </textarea><br><br>
        
        <button type="submit" class="boton">Actualizar</button>
        <button type="button" class="boton" onclick="window.location.href='505campeones.php'">Cancelar</button>        </form>
</body>
</html>
