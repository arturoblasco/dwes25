<?php
namespace Dwes\ProyectoVideoclub;


include_once "Resumible.php";

// Estructura y Generalización: Se asegura que ninguna instancia directa 
// de Soporte pueda crearse, dado que es un concepto genérico.
abstract class Soporte  implements Resumible{
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
    // public function muestraResumen() {
    //     return "<br>{$this->numero} - <strong>{$this->titulo}</strong> <br> - precio: {$this->precio}€ (IVA no incluido)";
    // }
    // Método abstracto `muestraResumen` de la interfaz Resumible
    abstract public function muestraResumen();

    //326
    public function getTitulo() : string {
        return $this->titulo;
    }
}
?>
