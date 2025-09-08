<?php
    include "CintaVideo.php";

    // Creación de una instancia de CintaVideo
    $miCinta = new CintaVideo("Los cazafantasmas", 23, 3.5, 107); 

    // Imprimir detalles usando los métodos definidos
    echo "<strong>" . $miCinta->titulo . "</strong>"; 
    echo "<br>Precio: " . $miCinta->getPrecio() . " €"; 
    echo "<br>Precio con IVA: " . $miCinta->getPrecioConIva() . " €";

    // Mostrar resumen de la cinta de video
    echo $miCinta->muestraResumen();
?>
