<?php
namespace Dwes\ProyectoVideoclub;


include_once "Soporte.php";

class Juego extends Soporte{

    public function __construct(string $titulo, float $precio,
                                private string $consola,
                                private int $minNumJugadores,
                                private int $maxNumJugadores){
        parent::__construct($titulo, $precio);
    }


    public function muestraJugadoresPosibles(){
        if ($this->maxNumJugadores==1){
            return "Para 1 jugador";
        } else if ($this->minNumJugadores == $this->maxNumJugadores){
            return "Para {$this->minNumJugadores} jugadores";
        } else {
            return "De {$this->minNumJugadores} a {$this->maxNumJugadores} jugadores";
        }
    }

    public function muestraResumen(){
        return parent::muestraResumen()."<br> - consola: {$this->consola}".
                                        "<br> - jugadores: {$this->muestraJugadoresPosibles()}"
        ;
    }

    
}