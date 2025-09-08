<?php
include_once "Soporte.php";

class Dvd extends Soporte {

    public function __construct($titulo, $numero, $precio, private $idiomas, private $formatoPantalla) {
        // Llamamos al constructor de la clase padre
        parent::__construct($titulo, $numero, $precio);
    }

    // Sobrescribimos el método muestraResumen para incluir los nuevos atributos
    public function muestraResumen() {
        // Convertimos el array en una cadena
        $idiomasVarios = implode(", ", $this->idiomas);  

        // Llamamos al método muestraResumen del padre y añadimos idiomas y formatoPantalla
        return parent::muestraResumen() . 
               "<br> - Idiomas: {$idiomasVarios} <br> - Formato de pantalla: {$this->formatoPantalla}";
    }
}
?>
