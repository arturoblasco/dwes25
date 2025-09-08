<?php
class Cliente {
    public $nombre;
    private $numero;
    private $soportesAlquilados = [];  // Array que contendrá los soportes alquilados
    private $numSoportesAlquilados = 0; // Contador de alquileres
    private $maxAlquilerConcurrente;

    // Constructor
    public function __construct($nombre, $numero, $maxAlquilerConcurrente = 3) {
        $this->nombre = $nombre;
        $this->numero = $numero;
        $this->maxAlquilerConcurrente = $maxAlquilerConcurrente;
    }

    // Método para alquilar un soporte
    public function alquilar(Soporte $s): self {
        if ($this->tieneAlquilado($s)) {
            echo "El soporte '" . $s->titulo . "' ya está alquilado.<br>";
        } elseif ($this->numSoportesAlquilados >= $this->maxAlquilerConcurrente) {
            echo "No puedes alquilar más de " . $this->maxAlquilerConcurrente . " soportes a la vez.<br>";
        } else {
            $this->soportesAlquilados[] = $s;
            $this->numSoportesAlquilados++;
            echo "Soporte '" . $s->titulo . "' alquilado con éxito.<br>";
        }
        return $this;  // Devolver la instancia actual para permitir el encadenamiento
    }

    // Otros métodos sin cambios (como tieneAlquilado, devolver, etc.)
}
?>
