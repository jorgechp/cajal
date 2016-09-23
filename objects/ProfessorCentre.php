<?php


/**
 * Description of ProfessorCentre
 *
 * @author jorge
 */
class ProfessorCentre {
    private $idCentre;
    private $nombre;
    
    function __construct($idCentre, $nombre) {
        $this->idCentre = $idCentre;
        $this->nombre = $nombre;
    }

        public function getIdCentre() {
        return $this->idCentre;
    }

    public function getNombre() {
        return $this->nombre;
    }



    public function setIdCentre($idCentre) {
        $this->idCentre = $idCentre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }




}
