<?php
include_once "Soporte.php";

class Juego extends Soporte {

    public function __construct($titulo, $numero, $precio, 
                                private $consola, private $minNumJugadores, private $maxNumJugadores) {
        // Llamamos al constructor del padre para inicializar las propiedades heredadas
        parent::__construct($titulo, $numero, $precio);
    }

    // Método para mostrar el número de jugadores posibles
    public function muestraJugadoresPosibles() {
        if ($this->minNumJugadores == 1 && $this->maxNumJugadores == 1) {
            return "Para 1 jugador";
        } elseif ($this->minNumJugadores == $this->maxNumJugadores) {
            return "Para " . $this->minNumJugadores . " jugadores";
        } else {
            return "De " . $this->minNumJugadores . " a " . $this->maxNumJugadores . " jugadores";
        }
    }

    // Sobrescribimos el método muestraResumen para incluir los nuevos atributos
    public function muestraResumen() {
        return parent::muestraResumen() . 
               "<br> - Consola: {$this->consola} <br> - {$this->muestraJugadoresPosibles()}";
    }
}
?>