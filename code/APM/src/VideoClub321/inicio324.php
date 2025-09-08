<?php
include "Juego.php";

// Creación de una instancia de Juego
$miJuego = new Juego("The Last of Us Part II", 26, 49.99, "PS4", 1, 1); 

// Imprimir detalles usando los métodos definidos
echo "<strong>" . $miJuego->titulo . "</strong>"; 
echo "<br>Precio: " . $miJuego->getPrecio() . " €"; 
echo "<br>Precio con IVA: " . $miJuego->getPrecioConIva() . " €";

// Mostrar resumen del juego
echo $miJuego->muestraResumen();
?>
