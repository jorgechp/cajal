<?php

/**
 * Description of competenceType
 *
 * @author jorge
 */
class CompetenceType {
    private $idTypeCompetence;
    private $name;
    
    function __construct($idTypeCompetence, $name) {
        $this->idTypeCompetence = $idTypeCompetence;
        $this->name = $name;
    }
    
    public function getIdTypeCompetence() {
        return $this->idTypeCompetence;
    }

    public function getName() {
        return $this->name;
    }

    public function setIdTypeCompetence($idTypeCompetence) {
        $this->idTypeCompetence = $idTypeCompetence;
    }

    public function setName($name) {
        $this->name = $name;
    }



}
