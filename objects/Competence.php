<?php

/**
 * Representación de una Competencia
 *
 * @author jorge
 */
class Competence {
    private $idCompetencia;
    private $name;
    private $description;
    private $idYear;
    private $indicators; //Array de indicadores
    private $observations;
    private $isActive;
    private $date;
    private $idType;
    private $degreeCode;
    private $idCreator;
    private $code;

    
    
    /**
 * 
     * @param int $idCompetencia
     * @param String $name
     * @param String $description
     * @param int $idYear
     * @param int[] $indicators
    * @param String $observations
    * @param boolean $isActive
    * @param timestamp $date
    * @param int $idType
    * @param int $degreeCode
 */
        function __construct($idCompetencia, $name, $description, $idYear, $indicators, $observations, $isActive, $date, $idType, $degreeCode, $idCreator, $code) {
            $this->idCompetencia = $idCompetencia;
            $this->name = $name;
            $this->description = $description;
            $this->idYear = $idYear;
            $this->indicators = $indicators;
            $this->observations = $observations;
            $this->isActive = $isActive;
            $this->date = $date;
            $this->idType = $idType;
            $this->degreeCode = $degreeCode;
            $this->idCreator = $idCreator;
            $this->code = $code;  
           
        }
        
        
    public function getObservations() {
        return $this->observations;
    }

    public function getIsActive() {
        return $this->isActive;
    }

    public function getDate() {
        return $this->date;
    }

    public function getIdType() {
        return $this->idType;
    }

    public function getDegreeCode() {
        return $this->degreeCode;
    }

    public function setObservations($observations) {
        $this->observations = $observations;
    }

    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function setIdType($idType) {
        $this->idType = $idType;
    }

    public function setDegreeCode($degreeCode) {
        $this->degreeCode = $degreeCode;
    }
            
    /**
     * 
     * @return int
     */
    public function getIdCompetencia() {
        return $this->idCompetencia;
    }

    
    /**
     * 
     * @return String
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 
     * @return String
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * 
     * @return int
     */
    public function getIdYear() {
        return $this->idYear;
    }

    /**
     * Devuelve un array con la id de los diferentes indicadores
     * que componen una competencia.
     * 
     * @return int[]
     */
    public function getIndicators() {
        return $this->indicators;
    }

    /**
     * 
     * @param int $idCompetencia
     */
    public function setIdCompetencia($idCompetencia) {
        $this->idCompetencia = $idCompetencia;
    }

    /**
     * 
     * @param String $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * 
     * @param String $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * 
     * @param int $idYear
     */
    public function setIdYear($idYear) {
        $this->idYear = $idYear;
    }

    /**
     * 
     * @param int[] $indicators
     */
    public function setIndicators($indicators) {
        $this->indicators = $indicators;
    }
    
    
    /**
     * 
     * @param int $idIndicator
     */
    public function addIndicator($idIndicator){
        $this->indicators[] = $idIndicator;
                
    }
    
    /**
     * 
     * @param int $idIndicator
     */
    public function deleteIndicator($idIndicator){
        unset($this->indicators[$idIndicator]);
    }


    
    public function getIdCreator() {
        return $this->idCreator;
    }

    public function setIdCreator($idCreator) {
        $this->idCreator = $idCreator;
    }
    
    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
    }





    
}
