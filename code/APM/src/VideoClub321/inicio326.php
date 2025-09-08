<?php
include "Cliente326.php";
include "CintaVideo.php";  // O cualquier otra clase que herede de Soporte

// Creación de una instancia de Cliente
$cliente1 = new Cliente("Arturo BC", 1);

// Crear soportes de ejemplo
$cinta1 = new CintaVideo("Los cazafantasmas", 22, 3.5, 107);
$cinta2 = new CintaVideo("Blade Runner", 23, 4.0, 117);

// Intentar alquilar soportes
$cliente1->alquilar($cinta1);  // Debería alquilar correctamente
$cliente1->alquilar($cinta1);  // Debería fallar porque ya está alquilado
$cliente1->alquilar($cinta2);  // Debería alquilar correctamente

// Mostrar el resumen del cliente
echo $cliente1->muestraResumen();
?>
