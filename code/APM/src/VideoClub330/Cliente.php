<?php
class Cliente {
    public $nombre;
    private $numero;
    private $soportesAlquilados = [];  // Array que contendrá los soportes alquilados
    private $numSoportesAlquilados = 0; // Contador de alquileres
    private $maxAlquilerConcurrente;

    // Constructor que inicializa nombre, numero, y maxAlquilerConcurrente (con valor por defecto 3)
    public function __construct($nombre, $numero, $maxAlquilerConcurrente = 3) {
        $this->nombre = $nombre;
        $this->numero = $numero;
        $this->maxAlquilerConcurrente = $maxAlquilerConcurrente;
    }

    // Getter y Setter para numero
    public function getNumero() {
        return $this->numero;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    // Getter para numSoportesAlquilados (total de alquileres realizados)
    public function getNumSoportesAlquilados() {
        return $this->numSoportesAlquilados;
    }

    // Método para comprobar si el cliente tiene alquilado un soporte
    public function tieneAlquilado(Soporte $s): bool {
        return in_array($s, $this->soportesAlquilados, true);
    }

    // Método para alquilar un soporte, con verificación de cupo y estado de alquiler
    public function alquilar(Soporte $s): bool {
        if ($this->tieneAlquilado($s)) {
            echo "El soporte '" . $s->titulo . "' ya está alquilado.<br>";
            return false;
        }

        if ($this->numSoportesAlquilados >= $this->maxAlquilerConcurrente) {
            echo "No puedes alquilar más de " . $this->maxAlquilerConcurrente . " soportes a la vez.<br>";
            return false;
        }

        // Alquilar el soporte
        $this->soportesAlquilados[] = $s;
        $this->numSoportesAlquilados++;
        echo "Soporte '" . $s->titulo . "' alquilado con éxito.<br>";
        return true;
    }

    // Método para devolver un soporte
    public function devolver(int $numSoporte): bool {
        foreach ($this->soportesAlquilados as $key => $soporte) {
            if ($soporte->getNumero() == $numSoporte) {
                unset($this->soportesAlquilados[$key]);
                $this->numSoportesAlquilados--;
                echo "El soporte con número " . $numSoporte . " ha sido devuelto.<br>";
                return true;
            }
        }
        echo "El soporte con número " . $numSoporte . " no está alquilado.<br>";
        return false;
    }

    // Método para listar los alquileres del cliente
    public function listarAlquileres(): void {
        echo "El cliente tiene " . count($this->soportesAlquilados) . " alquileres:<br>";
        foreach ($this->soportesAlquilados as $soporte) {
            echo "- " . $soporte->titulo . " (número: " . $soporte->getNumero() . ")<br>";
        }
    }

    // Método para mostrar un resumen del cliente
    public function muestraResumen() {
        return "Nombre: " . $this->nombre . "<br>" .
               "Número de alquileres actuales: " . count($this->soportesAlquilados) . "<br>";
    }
}
?>
