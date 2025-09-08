<?php
use Dwes\ProyectoVideoclub\Videoclub;

include_once "Videoclub.php";

$vc = new Videoclub("Severo 8A");

// Incluir soportes de prueba y crear algunos socios con encadenamiento de métodos
$vc->incluirJuego("God of War", 19.99, "PS4", 1, 1)
   ->incluirJuego("The Last of Us Part II", 49.99, "PS4", 1, 1)
   ->incluirDvd("Torrente", 4.5, ["es"], "16:9")
   ->incluirDvd("Origen", 4.5, ["es", "en", "fr"], "16:9")
   ->incluirDvd("El Imperio Contraataca", 3, ["es", "en"], "16:9")
   ->incluirCintaVideo("Los cazafantasmas", 3.5, 107)
   ->incluirCintaVideo("El nombre de la Rosa", 1.5, 140)
   ->listarProductos() // Listar productos después de añadirlos
   ->incluirSocio("Amancio Ortega") 
   ->incluirSocio("Pablo Picasso", 2)
   ->alquilarSocioProducto(1, 2)
   ->alquilarSocioProducto(1, 3)
   ->alquilarSocioProducto(1, 2) // Intento de alquilar un soporte ya alquilado
   ->alquilarSocioProducto(2, 6) // Intento de alquilar más de lo permitido
   ->listarSocios(); // Listar socios después de realizar las operaciones de alquiler
?>