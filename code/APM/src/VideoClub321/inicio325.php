<?php
include "Cliente325.php";
include "CintaVideo.php";  // O cualquier otra clase que herede de Soporte

// Creación de una instancia de Cliente
$cliente1 = new Cliente("Juan Pérez", 1);

// Crear soportes de ejemplo
$cinta1 = new CintaVideo("Los cazafantasmas", 22, 3.5, 107);
$cinta2 = new CintaVideo("Blade Runner", 23, 4.0, 117);

// Alquilar soportes
$cliente1->alquilarSoporte($cinta1);
$cliente1->alquilarSoporte($cinta2);

// Mostrar el resumen del cliente
echo $cliente1->muestraResumen();
?>
