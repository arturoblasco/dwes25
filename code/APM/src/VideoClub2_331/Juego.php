<?php
include "Soporte.php";

class Juego extends Soporte {
    private $consola;
    private $minNumJugadores;
    private $maxNumJugadores;

    // Constructor sobreescrito para inicializar los atributos adicionales
    public function __construct($titulo, $numero, $precio, $consola, $minNumJugadores, $maxNumJugadores) {
        // Llamamos al constructor del padre para inicializar las propiedades heredadas
        parent::__construct($titulo, $numero, $precio);
        $this->consola = $consola;
        $this->minNumJugadores = $minNumJugadores;
        $this->maxNumJugadores = $maxNumJugadores;
    }

    // Método para mostrar el número de jugadores posibles
    public function muestraJugadoresPosibles() {
        if ($this->minNumJugadores == 1 && $this->maxNumJugadores == 1) {
            return "Para un jugador";
        } elseif ($this->minNumJugadores == $this->maxNumJugadores) {
            return "Para " . $this->minNumJugadores . " jugadores";
        } else {
            return "De " . $this->minNumJugadores . " a " . $this->maxNumJugadores . " jugadores";
        }
    }

    // Sobrescribimos el método muestraResumen para incluir los nuevos atributos
    public function muestraResumen() {
        return parent::muestraResumen() . 
               "<br>Consola: " . $this->consola . 
               "<br>" . $this->muestraJugadoresPosibles();
    }
}
?>
