<?php
namespace Dwes\ProyectoVideoclub;

include_once "Soporte.php";
include_once "Cliente.php";
include_once "CintaVideo.php";
include_once "Dvd.php";
include_once "Juego.php";

class Videoclub {
    // atributos
    private string $nombre;
    private $productos = [];
    private int $numProductos = 0;
    private $socios = [];
    private int $numSocios = 0;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    // métodos
    private function incluirProducto(Soporte $s): Videoclub{
        $this->productos[] = $s;
        $this->numProductos++;
        echo "Incluido soporte {$this->numProductos}<br/>";
        return $this;
    }

    public function incluirCintaVideo($titulo, $precio, $duracion): Videoclub{
        $cintaVideo = new CintaVideo($titulo, $precio, $duracion);
        return $this->incluirProducto($cintaVideo);
    }

    public function incluirDvd($titulo, $precio, $idiomas, $pantalla): Videoclub{
        $dvd = new Dvd($titulo, $precio, $idiomas, $pantalla);
        return $this->incluirProducto($dvd);
    }

    public function incluirJuego($titulo, $precio, $consola, $minJ, $maxJ): Videoclub{
        $juego = new Juego($titulo, $precio, $consola, $minJ, $maxJ);
        return $this->incluirProducto($juego);
    }

    public function incluirSocio ($nombre, $masAlquileresConcurrentes=3): Videoclub{
        $socio = new Cliente ($nombre, $masAlquileresConcurrentes);
        $this->socios[] = $socio;
        $this->numSocios++;
        return $this;

    }

    public function listarProductos():Videoclub{
        echo "<br/>Listado de {$this->numProductos} productos:<br/>";
        foreach ($this->productos as $item){
            echo "{$item->getNumero()} ) {$item->getTitulo()}<br/>";
        }
        return $this;
    }
    public function listarSocios():Videoclub{
        echo "Listado de socios:<br/>";
        foreach ($this->socios as $item){
            echo "{$item->getNumero()} ) {$item->getNombre()}<br/>";
        }
        return $this;
    }    

    public function alquilarSocioProducto(int $numeroCliente, int $numeroSoporte): Videoclub{
        $cli = $this->buscarSocio($numeroCliente);
        $sop = $this->buscarProducto($numeroSoporte);

        if ($cli && $sop){
            $cli->alquilar($sop);
        } else {
            echo "Error: No se encontró el socio y/o el soporte.<br>";
        }
        return $this;
    }
    public function buscarSocio(int $numS): Cliente{
        foreach ($this->socios as $item){
            if ($numS == $item->getNumero()){
                return $item;
            }
        }
        return null;
    }
    public function buscarProducto(int $numP): Soporte{
        foreach ($this->productos as $item){
            if ($numP == $item->getNumero()){
                return $item;
            }
        }
        return null;
    }
}

?>