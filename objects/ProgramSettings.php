<?php


/**
 * Representa configuraciones generales del sistema
 *
 * @author jorge
 */
class ProgramSettings {
    /**
     * Obtiene el valor del año actual
     * @var int 
     */
    private $idCurrentYear;
    private $nameCurrentYear;
    
    public function getIdCurrentYear() {
        return $this->idCurrentYear;
    }

    public function setIdCurrentYear($idCurrentYear) {
        $this->idCurrentYear = $idCurrentYear;
    }
    
   public function getNameCurrentYear() {
        return $this->nameCurrentYear;
    }

    public function setNameCurrentYear($nameCurrentYear) {
        $this->nameCurrentYear = $nameCurrentYear;
    }
    
    
    




}
