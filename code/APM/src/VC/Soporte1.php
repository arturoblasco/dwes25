<?php
class Soporte {
    // Definimos la constante IVA como una propiedad privada y estática
    private static $IVA = 0.21;
    // propiedad estatica
    private static int $contador = 1;

    //atributos
    private int $numero;
    // Constructor con promoción de atributos
    public function __construct(public string $titulo,
                                private float $precio) {
        $this->numero = self::$contador++;                            
    }

    // Método para obtener el precio
    public function getPrecio() : float {
        return $this->precio;
    }

    // Método para obtener el precio con IVA
    public function getPrecioConIVA() : float {
        return $this->precio * (1 + self::$IVA);
    }

    // Método para obtener el número
    public function getNumero() : int {
        return $this->numero;
    }

    // Método para mostrar un resumen
    public function muestraResumen() {
        return "<br>{$this->numero} - <strong>{$this->titulo}</strong> <br> - precio: {$this->precio}€ (IVA no incluido)";
    }

    //326
    public function getTitulo() : string {
        return $this->titulo;
    }
}
?>
