<?php


/**
 * Lugar representa la ubicación donde se ha realizado una sesión de prácticas.
 * Es necesario representar los lugares de cara a la evaluación de cada estudiante.
 *
 * @author jorge
 */
class Lugar {
    /**
     * Identificador del lugar
     * @var int
     */
    private $idLugar;
    
    /**
     * Nombre del lugar
     * @var string
     */
    private $nombre;
    
    /**
     * Representación detallada del lugar
     * @var string
     */
    private $descripcion;
    
    /**
     * Identificador del centro al que pertenece el lugar
     * @var int
     */
    private $idCentro;
    
    /**
     * Constructor del objeto Lugar
     * 
     * @param int $idLugar
     * @param string $nombre
     * @param string $descripcion
     * @param int $idCentro
     */
    function __construct($idLugar, $nombre, $descripcion, $idCentro) {
        $this->idLugar = $idLugar;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->idCentro = $idCentro;
    }
    
    public function getIdLugar() {
        return $this->idLugar;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getIdCentro() {
        return $this->idCentro;
    }

    public function setIdLugar($idLugar) {
        $this->idLugar = $idLugar;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setIdCentro($idCentro) {
        $this->idCentro = $idCentro;
    }



            
}
