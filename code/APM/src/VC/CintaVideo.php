<?php
    include_once "Soporte.php";

class CintaVideo extends Soporte{

    public function __construct(string $titulo, float $precio,
                                private int $duracion){
        parent::__construct($titulo, $precio);
    }

    public function muestraResumen(){
        return parent::muestraResumen().
               "<br> - duración: {$this->duracion} minutos";
    }
    
}