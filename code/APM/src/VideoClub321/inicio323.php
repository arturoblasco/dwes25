<?php
include "Dvd.php";

// Creación de una instancia de Dvd
$miDvd = new Dvd("Origen", 24, 15, ["es,en,fr"], "16:9"); 

// Imprimir detalles usando los métodos definidos
echo "<strong>" . $miDvd->titulo . "</strong>"; 
echo "<br>Precio: " . $miDvd->getPrecio() . " €"; 
echo "<br>Precio con IVA: " . $miDvd->getPrecioConIva() . " €";

// Mostrar resumen del Dvd
echo $miDvd->muestraResumen();
?>
