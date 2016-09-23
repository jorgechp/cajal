<?php

/**
 * Representación de una Actividad
 *
 * @author jorge
 */
class ActivityTipo {
    private $idActividad;
    private $nombre;
    private $codigo;
    
    function __construct($idActividad, $nombre, $codigo) {
        $this->idActividad = $idActividad; 
        $this->nombre = $nombre; 
        $this->codigo = $codigo; 
    }
    
    public function getIdActividadTipo() {
        return $this->idActividad;
    }
	
    public function getNombre() {
        return $this->nombre;
    }
	
	public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }



    
    
}