<?php
namespace Dwes\ProyectoVideoclub;

use Dwes\ProyectoVideoclub\Util\SoporteYaAlquiladoException;
use Dwes\ProyectoVideoclub\Util\CupoSuperadoException;

class Cliente {
    public $nombre;
    private $numero;
    private $soportesAlquilados = [];
    private $numSoportesAlquilados = 0;
    private $maxAlquilerConcurrente;

    public function __construct($nombre, $numero, $maxAlquilerConcurrente = 3) {
        $this->nombre = $nombre;
        $this->numero = $numero;
        $this->maxAlquilerConcurrente = $maxAlquilerConcurrente;
    }

    public function alquilar(Soporte $s): self {
        if ($this->tieneAlquilado($s)) {
            throw new SoporteYaAlquiladoException("El soporte '" . $s->titulo . "' ya está alquilado.");
        }

        if ($this->numSoportesAlquilados >= $this->maxAlquilerConcurrente) {
            throw new CupoSuperadoException("No puedes alquilar más de " . $this->maxAlquilerConcurrente . " soportes a la vez.");
        }

        $this->soportesAlquilados[] = $s;
        $this->numSoportesAlquilados++;
        echo "Soporte '" . $s->titulo . "' alquilado con éxito.<br>";
        
        return $this;
    }

    // Otros métodos sin cambios
}
?>
