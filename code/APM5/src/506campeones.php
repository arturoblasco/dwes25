<link rel="stylesheet" href="css/estilos.css">

<?php
include 'config/database.inc.php';

$con = null;

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;", $usuario, $password); // Crear una nueva conexión
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Parámetros de ordenamiento recogidos desde $_GET
    $ordenColumna = isset($_GET['columna']) ? $_GET['columna'] : 'id';
    $ordenDireccion = isset($_GET['direccion']) ? $_GET['direccion'] : 'ASC';

    // Validar columna y dirección para evitar inyecciones SQL
    $columnasValidas = ['id', 'nombre', 'rol', 'dificultad', 'descripcion'];
    $ordenDireccion = strtoupper($ordenDireccion) === 'DESC' ? 'DESC' : 'ASC';
    
    if (!in_array($ordenColumna, $columnasValidas)) {
        $ordenColumna = 'id';
    }

    // Consulta SQL para obtener todos los campeones
    // Cuando se usa bindParam, este es válido para valores de columnas, 
    // pero no funcionan para las partes de la consulta que son dinámicas, 
    // como las columnas en la cláusula ORDER BY. 
    // Así, le pasaremos dos variables (y habremos certificado que son confiables anteriormente).
    $sql = "SELECT * FROM campeon ORDER BY $ordenColumna $ordenDireccion";
    $sentencia = $con->prepare($sql);  
    $sentencia->execute();

    // Obtener resultados
    $campeones = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    // Mostrar resultados
    echo "<h3>Campeones de LOL</h3>";
    echo "<table>";
    echo "<tr>";
    echo "<th></th>";
    echo "<th></th>";
    echo "<th>ID <a href='?columna=id&direccion=ASC'>˅</a> <a href='?columna=id&direccion=DESC'>^</a></th>";
    echo "<th>Nombre <a href='?columna=nombre&direccion=ASC'>˅</a> <a href='?columna=nombre&direccion=DESC'>^</a></th>";
    echo "<th>Rol <a href='?columna=rol&direccion=ASC'>˅</a> <a href='?columna=rol&direccion=DESC'>^</a></th>";
    echo "<th>Dificultad <a href='?columna=dificultad&direccion=ASC'>˅</a> <a href='?columna=dificultad&direccion=DESC'>^</a></th>";
    echo "<th>Descripción <a href='?columna=descripcion&direccion=ASC'>˅</a> <a href='?columna=descripcion&direccion=DESC'>^</a></th>";
    echo "</tr>";
    foreach ($campeones as $elemento) {
        echo "<tr>";

        // Botones de editar y borrar
        echo "<td><form style='display:inline;' method='GET' action='505editando.php'>";
        echo "<input type='hidden' name='id' value='" . 
                htmlspecialchars($elemento['id']) . "'>";
        echo "<button type='submit' class='boton'>Editar</button>";
        echo "</form></td>";
        
        echo "<td><form style='display:inline;' method='POST' onsubmit='return confirmDelete(\"" . 
                addslashes($elemento['nombre']) . "\", " . $elemento['id'] . ")'>";
        echo "<button type='submit' class='boton'>Borrar</button>";
        echo "</form></td>";

        echo "<td>" . htmlspecialchars($elemento['id']) . "</td>";
        echo "<td>" . htmlspecialchars($elemento['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($elemento['rol']) . "</td>";
        echo "<td>" . htmlspecialchars($elemento['dificultad']) . "</td>";
        echo "<td>" . htmlspecialchars($elemento['descripcion']) . "</td>";

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
