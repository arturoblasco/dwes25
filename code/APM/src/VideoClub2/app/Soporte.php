<?php
namespace Dwes\ProyectoVideoclub;

abstract class Soporte implements Resumible {
    public $titulo;
    protected $numero;
    private $precio;

    // Constructor
    public function __construct($titulo, $numero, $precio) {
        $this->titulo = $titulo;
        $this->numero = $numero;
        $this->precio = $precio;
    }

    // Métodos
    public function getPrecio() {
        return $this->precio;
    }

    public function getPrecioConIva() {
        return $this->precio * 1.21;
    }

    public function getNumero() {
        return $this->numero;
    }

    // Declaramos muestraResumen como abstracto para forzar que las clases hijas lo implementen
    abstract public function muestraResumen();
}
?>
