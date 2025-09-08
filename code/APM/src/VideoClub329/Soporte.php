<?php
abstract class Soporte {
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


    // Método abstracto
    // Prohibir la creación de objetos de Soporte: 
    // La clase Soporte se convierte en una plantilla que no puede ser instanciada directamente.
    abstract public function muestraResumen();
}
?>
