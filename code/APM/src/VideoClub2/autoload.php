<?php
spl_autoload_register(function ($class) {
    // Reemplazar el namespace base por la carpeta 'app'
    $prefix = 'Dwes\\ProyectoVideoclub\\';
    $base_dir = __DIR__ . '/app/Dwes/ProyectoVideoclub/';

    // Verificar si la clase usa el namespace base
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // Si no está en el namespace, no lo cargamos
        return;
    }

    // Obtener el nombre relativo de la clase
    $relative_class = substr($class, $len);

    // Reemplazar los separadores de namespace por separadores de directorios y agregar la extensión .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Si el archivo existe, lo incluimos
    if (file_exists($file)) {
        require $file;
    }
});