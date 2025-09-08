<?php
include_once "Soporte.php";
include_once "Cliente.php";
include_once "CintaVideo.php";
include_once "Dvd.php";
include_once "Juego.php";

class Videoclub {
    private $nombre;
    private $productos = [];  // Array de soportes (productos)
    private $numProductos = 0;
    private $socios = [];     // Array de clientes (socios)
    private $numSocios = 0;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

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
        echo "Listado de productos disponibles:<br>";
        foreach ($this->productos as $item) {
            echo " - {$item->titulo} (número {$item->getNumero()})<br>";
        }
    }

    // Método para listar socios
    public function listarSocios() {
        echo "Listado de socios:<br>";
        foreach ($this->socios as $item) {
            echo "- {$item->nombre} (número {$item->getNumero()})<br>";
        }
    }

    // Método para alquilar un producto a un socio
    public function alquilaSocioProducto($numeroCliente, $numeroSoporte) {
        $socio = $this->buscarSocio($numeroCliente);
        $soporte = $this->buscarProducto($numeroSoporte);

        if ($socio && $soporte) {
            $socio->alquilar($soporte);
        } else {
            echo "Error: No se encontró el socio y/o el soporte.<br>";
        }
    }

    // Método auxiliar para buscar un socio por su número
    private function buscarSocio($numeroCliente): Cliente {
        foreach ($this->socios as $item) {
            if ($item->getNumero() == $numeroCliente) {
                return $item;
            }
        }
        return null;
    }

    // Método auxiliar para buscar un producto por su número
    private function buscarProducto($numeroSoporte): Soporte {
        foreach ($this->productos as $item) {
            if ($item->getNumero() == $numeroSoporte) {
                return $item;
            }
        }
        return null;
    }
}
?>
