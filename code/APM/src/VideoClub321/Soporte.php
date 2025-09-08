<?php
class Soporte {
    // Definimos la constante IVA como una propiedad privada y estática
    private static $IVA = 0.21;

    // Constructor con promoción de atributos
    public function __construct(public $titulo, protected $numero, private $precio) {}

    // Método para obtener el precio
    public function getPrecio() {
        return $this->precio;
    }

    // Método para obtener el precio con IVA
    public function getPrecioConIVA() {
        return $this->precio * (1 + self::$IVA);
    }

    // Método para obtener el número
    public function getNumero() {
        return $this->numero;
    }

    // Método para mostrar un resumen
    public function muestraResumen() {
        return "<br>{$this->numero} - <strong>{$this->titulo}</strong> <br> - {$this->precio}€ (IVA no incluido)";
    }
}
?>
