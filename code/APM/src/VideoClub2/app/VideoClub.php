<?php
namespace Dwes\ProyectoVideoclub;

use Dwes\ProyectoVideoclub\Util\ClienteNoEncontradoException;
use Dwes\ProyectoVideoclub\Util\SoporteNoEncontradoException;

class Videoclub {
    private $nombre;
    private $productos = [];
    private $numProductos = 0;
    private $socios = [];
    private $numSocios = 0;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    public function alquilaSocioProducto($numeroCliente, $numeroSoporte) {
        $socio = $this->buscarSocio($numeroCliente);
        if (!$socio) {
            throw new ClienteNoEncontradoException("No se encontró el cliente con el número " . $numeroCliente);
        }

        $soporte = $this->buscarProducto($numeroSoporte);
        if (!$soporte) {
            throw new SoporteNoEncontradoException("No se encontró el soporte con el número " . $numeroSoporte);
        }

        $socio->alquilar($soporte);
        return $this;
    }

    // Otros métodos sin cambios
}
?>
