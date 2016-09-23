<?php

/**
 * Description of competenceType
 *
 * @author jorge
 */
class CompetenceMateria {
    private $nombre;
    private $codigo;
    private $idMateria;
    
    function __construct($idMateria, $nombre, $codigo ) {
        $this->nombre = $nombre;
        $this->codigo = $codigo;
        $this->idMateria = $idMateria;
    }

    
    public function getNombre() {
        return $this->nombre;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getIdMateria() {
        return $this->idMateria;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function setIdMateria($idMateria) {
        $this->idMateria = $idMateria;
    }




}
