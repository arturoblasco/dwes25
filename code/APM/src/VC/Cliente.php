<?php

class Cliente {
    // propiedad estatica
    private static int $contador = 1;
    // propiedades objeto
    public string $nombre;
    private int $numero;
    private $soportesAlquilados = [];
    private int $numSoportesAlquilados;
    private int $maxAlquilerConcurrente;

    public function __construct(string $nombre, 
                                int $maxAlquilerConcurrente=3){
        $this->nombre = $nombre;
        $this->numero = self::$contador++;
        $this->soportesAlquilados = [];
        $this->numSoportesAlquilados = 0;
        $this->maxAlquilerConcurrente = $maxAlquilerConcurrente;                            
    }

    // para el 328
    public function getNombre(): string{
        return $this->nombre;
    }
    //getter $numero
    public function getNumero(): int{
        return $this->numero;
    }

    //setter $numero
    public function setNumero(int $numero): void{
        $this->numero = $numero;
    }

    //getter $numSoportesAlquilados
    public function getNumSoportesAlquilados(): int{
        return $this->numSoportesAlquilados;
    }

    // Método para mostrar un resumen del cliente
    public function muestraResumen() {
        return "Nombre: " . $this->nombre . "<br>" .
               "Número de alquileres actuales: " . count($this->soportesAlquilados) . "<br>";
    }

    // Ejercicio 326
    public function tieneAlquilado(Soporte $s) : bool {
        // return in_array($s, $this->soportesAlquilados);
        foreach ($this->soportesAlquilados as $item) {
            if ($item->getTitulo() == $s->getTitulo()) {
                return true;
            }
        }   
        return false;
    }

    public function alquilar (Soporte $s) : bool {
        if ($this->numSoportesAlquilados < $this->maxAlquilerConcurrente) {
            if (!$this->tieneAlquilado($s)) {
                $this->soportesAlquilados[] = $s;
                $this->numSoportesAlquilados++;
                echo "El soporte " . $s->getTitulo() . " ha sido alquilado por " . $this->nombre . "<br>";
                return true;
            }
        }
        return false;
    }

    // Ejercicio 327
    public function devolver(int $numSoporte): bool {
        foreach ($this->soportesAlquilados as $key => $item) {
            if ($item->getNumero() == $numSoporte) {
                unset($this->soportesAlquilados[$key]);
                $this->numSoportesAlquilados--;
                echo "El soporte " . $item->getTitulo() . " ha sido devuelto por " . $this->nombre . "<br>";
                return true;
            }
        }
        return false;
    }

    public function listarAlquileres(): void {
        echo "Listado de alquileres de " . $this->nombre . ":";
        foreach ($this->soportesAlquilados as $item) {
            echo $item->muestraResumen() . "<br>";
        }
    }
}
