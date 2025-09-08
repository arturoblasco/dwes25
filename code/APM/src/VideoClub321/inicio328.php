<?php
include_once "VideoClub.php"; // No incluimos nada más

$vc = new Videoclub("Severo 8A"); 

// Incluir soportes de prueba 
$vc->incluirJuego("God of War", 19.99, "PS4", 1, 1); 
$vc->incluirJuego("The Last of Us Part II", 49.99, "PS4", 1, 1);
$vc->incluirDvd("Torrente", 4.5, ["es"], "16:9"); 
$vc->incluirDvd("Origen", 4.5, ["es","en","fr"], "16:9"); 
$vc->incluirDvd("El Imperio Contraataca", 3, ["es","en"],"16:9"); 
$vc->incluirCintaVideo("Los cazafantasmas", 3.5, 107); 
$vc->incluirCintaVideo("El nombre de la Rosa", 1.5, 140); 

// Listar productos 
$vc->listarProductos(); 

// Crear algunos socios 
$vc->incluirSocio("Amancio Ortega"); 
$vc->incluirSocio("Pablo Picasso", 2); 

// Alquileres de productos
$vc->alquilaSocioProducto(1, 2); 
$vc->alquilaSocioProducto(1, 3); 
// Intentar alquilar de nuevo el soporte 2 al socio 1 (no debería permitirlo) 
$vc->alquilaSocioProducto(1, 2); 
// Intentar alquilar el soporte 6 al socio 1 (no debería permitirlo por superar el límite)
$vc->alquilaSocioProducto(1, 6); 

// Listar socios 
$vc->listarSocios();
?>
