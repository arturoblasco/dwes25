<?php
    include ("Soporte.php");

    // Creación de una instancia de Soporte
    $soporte1 = new Soporte("Tenet", 22, 3); 

    // Imprimir detalles usando los métodos definidos
    echo "<strong>" . $soporte1->titulo . "</strong>"; 
    echo "<br>Precio: " . $soporte1->getPrecio() . " €"; 
    echo "<br>Precio con IVA: " . $soporte1->getPrecioConIVA() . " €";

    // Mostrar resumen del soporte
    echo $soporte1->muestraResumen();
?>
