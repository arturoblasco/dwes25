<?php
require_once '../autoload.php';

use Dwes\ProyectoVideoclub\Videoclub;
use Dwes\ProyectoVideoclub\Util\SoporteYaAlquiladoException;
use Dwes\ProyectoVideoclub\Util\CupoSuperadoException;
use Dwes\ProyectoVideoclub\Util\ClienteNoEncontradoException;
use Dwes\ProyectoVideoclub\Util\SoporteNoEncontradoException;

$vc = new Videoclub("Severo 8A");

try {
    // Incluir soportes
    $vc->incluirJuego("God of War", 19.99, "PS4", 1, 1)
       ->incluirJuego("The Last of Us Part II", 49.99, "PS4", 1, 1)
       ->incluirDvd("Torrente", 4.5, ["es"], "16:9")
       ->incluirDvd("Origen", 4.5, ["es", "en", "fr"], "16:9")
       ->incluirDvd("El Imperio Contraataca", 3, ["es", "en"], "16:9")
       ->incluirCintaVideo("Los cazafantasmas", 3.5, 107)
       ->incluirCintaVideo("El nombre de la Rosa", 1.5, 140);

    // Listar productos
    $vc->listarProductos();

    // Crear socios
    $vc->incluirSocio("Amancio Ortega")
       ->incluirSocio("Pablo Picasso", 2);

    // Alquileres de productos
    $vc->alquilaSocioProducto(1, 2)
       ->alquilaSocioProducto(1, 3)
       ->alquilaSocioProducto(1, 2) // Intentar alquilar de nuevo el soporte 2
       ->alquilaSocioProducto(1, 6); // Intentar alquilar el soporte 6
} catch (SoporteYaAlquiladoException | CupoSuperadoException | ClienteNoEncontradoException | SoporteNoEncontradoException $e) {
    echo "Excepción atrapada: " . $e->getMessage() . "<br>";
}

// Listar socios
$vc->listarSocios();
?>
