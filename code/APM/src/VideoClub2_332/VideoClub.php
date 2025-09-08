<?php
namespace Dwes\ProyectoVideoclub;

include_once "Soporte.php";
include_once "Cliente.php";
include_once "CintaVideo.php";
include_once "Dvd.php";
include_once "Juego.php";


class Videoclub {
    private $nombre;
    private $productos = [];
    private $numProductos = 0;
    private $socios = [];
    private $numSocios = 0;

    // Constructor
    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    // Método para alquilar un producto a un socio
    public function alquilaSocioProducto($numeroCliente, $numeroSoporte): self {
        $socio = $this->buscarSocio($numeroCliente);
        $soporte = $this->buscarProducto($numeroSoporte);

        if ($socio && $soporte) {
            $socio->alquilar($soporte);
        } else {
            echo "Error: No se encontró el socio o el soporte.<br>";
        }

        return $this;  // Devolver la instancia actual para permitir el encadenamiento
    }

    // Otros métodos sin cambios (como listarProductos, listarSocios, etc.)
    // Método privado para incluir productos en el array
    private function incluirProducto(Soporte $producto) {
        $this->productos[] = $producto;
        $this->numProductos++;
    }

    // Métodos públicos para incluir soportes
    public function incluirCintaVideo($titulo, $precio, $duracion) {
        $cinta = new CintaVideo($titulo, $this->numProductos + 1, $precio, $duracion);
        $this->incluirProducto($cinta);
    }

    public function incluirDvd($titulo, $precio, $idiomas, $formatoPantalla) {
        $dvd = new Dvd($titulo, $this->numProductos + 1, $precio, $idiomas, $formatoPantalla);
        $this->incluirProducto($dvd);
    }

    public function incluirJuego($titulo, $precio, $consola, $minJ, $maxJ) {
        $juego = new Juego($titulo, $this->numProductos + 1, $precio, $consola, $minJ, $maxJ);
        $this->incluirProducto($juego);
    }

    // Incluir un socio en el videoclub
    public function incluirSocio($nombre, $maxAlquilerConcurrente = 3) {
        $socio = new Cliente($nombre, $this->numSocios + 1, $maxAlquilerConcurrente);
        $this->socios[] = $socio;
        $this->numSocios++;
    }

    // Método para listar productos
    public function listarProductos() {
        echo "Lista de productos disponibles en el videoclub:<br>";
        foreach ($this->productos as $producto) {
            echo "- " . $producto->titulo . " (Número: " . $producto->getNumero() . ")<br>";
        }
    }

    // Método para listar socios
    public function listarSocios() {
        echo "Lista de socios del videoclub:<br>";
        foreach ($this->socios as $socio) {
            echo "- " . $socio->nombre . " (Número: " . $socio->getNumero() . ")<br>";
        }
    }

    // Método auxiliar para buscar un socio por su número
    private function buscarSocio($numeroCliente) {
        foreach ($this->socios as $socio) {
            if ($socio->getNumero() == $numeroCliente) {
                return $socio;
            }
        }
        return null;
    }

    // Método auxiliar para buscar un producto por su número
    private function buscarProducto($numeroSoporte) {
        foreach ($this->productos as $soporte) {
            if ($soporte->getNumero() == $numeroSoporte) {
                return $soporte;
            }
        }
        return null;
    }

}
?>
