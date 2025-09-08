<?php
include_once "Soporte.php";

class CintaVideo extends Soporte {

    public function __construct($titulo, $numero, $precio, private $duracion) {
        // Llamamos al constructor del padre para inicializar las propiedades heredadas
        parent::__construct($titulo, $numero, $precio);
    }

    // Sobreescribimos el método muestraResumen para incluir la duración
    public function muestraResumen() {
        // Llamamos al método muestraResumen del padre y añadimos la duración
        return parent::muestraResumen() . " <br> {$this->duracion} minutos";
    }
}
?>
