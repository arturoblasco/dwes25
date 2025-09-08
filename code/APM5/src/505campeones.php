<link rel="stylesheet" href="css/estilos.css">
<?php
include 'config/database.inc.php';

$con = null;

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;", $usuario, $password); // Crear una nueva conexión
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener todos los campeones
    $sql = "SELECT * FROM campeon";

    $sentencia = $con->prepare($sql);
    $sentencia->execute();

    // Obtener resultados
    $campeones = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    // Mostrar resultados
    echo "<h3>Campeones de LOL</h3>";
    echo "<table>";
    echo "<tr><th></th><th></th><th>nombre</th><th>rol</th><th>dificultad</th><th>descripción</th></tr>";
    foreach ($campeones as $campeon) {
        echo "<tr>";

        // Botones de editar y borrar
        echo "<td><form style='display:inline;' method='GET' action='505editando.php'>";
        echo "<input type='hidden' name='id' value='" . 
                htmlspecialchars($campeon['id']) . "'>";
        echo "<button type='submit' class='boton'>Editar</button>";
        echo "</form></td>";

        //echo "<td><button class='boton'>";
        //echo "<a href='505editando.php?id=".$campeon['id']."'>Editar</a></button></td>";
        
        echo "<td>";
        echo "<form style='display:inline;' method='POST' onsubmit='return confirmDelete(" . 
                $campeon['nombre'] . " , " . $campeon['id'] . ")'>";
        echo "<button type='submit' class='boton'>Borrar</button>";
        echo "</form></td>";

        echo "<td>" . htmlspecialchars($campeon['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($campeon['rol']) . "</td>";
        echo "<td>" . htmlspecialchars($campeon['dificultad']) . "</td>";
        echo "<td>" . htmlspecialchars($campeon['descripcion']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<script>
        function confirmDelete(nombre, id) {
            if (confirm('¿Estás seguro de que quieres borrar a ' + nombre + '?')) {
                window.location.href = '505delete.php?id=' + id;
                return false; // Prevenir envío del formulario
            }
            return false;
        }
    </script>";
} catch (PDOException $e) {
    // Manejo de errores
    echo "Error al conectar a la base de datos: " . $e->getMessage();
}
?>
