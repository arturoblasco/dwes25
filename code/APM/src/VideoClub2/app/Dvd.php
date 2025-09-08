<?php
namespace Dwes\ProyectoVideoclub;

class Dvd extends Soporte {
    private $idiomas;   // será un array
    private $formatoPantalla;

    // Constructor sobreescrito para inicializar idiomas y formatoPantalla
    public function __construct($titulo, $numero, $precio, $idiomas, $formatoPantalla) {
        // Llamamos al constructor de la clase padre
        parent::__construct($titulo, $numero, $precio);
        $this->idiomas = $idiomas;
        $this->formatoPantalla = $formatoPantalla;
    }

    // Sobrescribimos el método muestraResumen para incluir los nuevos atributos
    public function muestraResumen() {
        $idiomasVarios = implode(", ", $this->idiomas);  // Convertimos el array en una cadena
        // Llamamos al método muestraResumen del padre y añadimos idiomas y formatoPantalla
        return parent::muestraResumen() . 
               "<br>Idiomas: " . $idiomasVarios . 
               "<br>Formato de pantalla: " . $this->formatoPantalla;
    }
}
?>
