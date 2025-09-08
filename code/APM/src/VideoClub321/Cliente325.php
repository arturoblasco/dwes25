<?php
include_once 'Soporte.php';

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

    // Método para alquilar un soporte (añadirlo al array de soportes alquilados)
    public function alquilarSoporte($soporte) {
        if ($this->numSoportesAlquilados < $this->maxAlquilerConcurrente) {
            $this->soportesAlquilados[] = $soporte;
            $this->numSoportesAlquilados++;
        } else {
            echo "No se puede alquilar más de {$this->maxAlquilerConcurrente} soportes a la vez.<br>";
        }
    }

    // Método para mostrar un resumen del cliente
    public function muestraResumen() {
        return "Nombre: " . $this->nombre . "<br>" .
               "Número de alquileres actuales: " . count($this->soportesAlquilados) . "<br>";
    }
}
?>
