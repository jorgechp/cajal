<?php

/**
 * Representación de una Actividad
 *
 * @author jorge
 */
class CompetenciaTipo {
    private $idTipo;
    private $nombre;
    private $codigo;
    
    function __construct($idTipo, $nombre, $codigo) {
        $this->idTipo = $idTipo; 
	$this->nombre = $nombre; 
        $this->codigo = $codigo; 
    }
    
    public function getIdTipo() {
        return $this->idTipo;
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