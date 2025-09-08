<?php
include_once "Soporte.php";

class Dvd extends Soporte{

    public function __construct(string $titulo, float $precio,
                                private array $idiomas, 
                                private string $formatoPantalla){
        parent::__construct($titulo, $precio);
    }    

    public function muestraResumen(){
        $idiomasStr = implode(",", $this->idiomas);
        return parent::muestraResumen()."<br> - idiomas: {$idiomasStr} <br> - formato: {$this->formatoPantalla}";
    }    

}