<?php
// Nombre del archivo
$filename = "509loremIpsum.txt";

// Verificar si el archivo existe
if (file_exists($filename)) {
    // Leer el contenido del archivo
    $contenido = file_get_contents($filename);

    // Obtener el tamaño del archivo en Kilobytes (1 KB = 1024 bytes)
    $tamañoKB = filesize($filename) / 1024;

    // Obtener la fecha de última modificación
    $fechaModificacion = date("d-m-Y H:i:s", filemtime($filename));

    // Obtener el ID del usuario que creó el archivo
    $fileOwner = fileowner($filename);

    // Mostrar la información
    echo "<h3>Información del Archivo: $filename</h3>";
    echo "<p><strong>Contenido:</strong></p>";
    echo "<pre>$contenido</pre>";
    echo "<p><strong>Tamaño:</strong> " . round($tamañoKB, 2) . " KB</p>";
    echo "<p><strong>Última modificación:</strong> $fechaModificacion</p>";
    echo "<p><strong>ID del usuario que creó el archivo:</strong> $fileOwner</p>";
} else {
    echo "El archivo $filename no existe.";
}
?>
