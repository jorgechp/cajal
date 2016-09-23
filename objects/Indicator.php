<?php

/**
 * Representa y maneja indicadores de competencias
 *
 * @author jorge
 */
class Indicator {
    private $idCompetence;
    private $idIndicator;
    private $name;
    private $code;
    private $description;
    
    
    /**
     * Crea un objeto Indicator
     * @param int $idCompetence
     * @param int $idIndicator
     * @param String $name
     * @param String $description
     */
    function __construct($idCompetence, $idIndicator, $name, $description, $code) {
        $this->idCompetence = $idCompetence;
        $this->idIndicator = $idIndicator;
        $this->name = $name;
        $this->description = $description;
        $this->code = $code;
    }
    
    /**
     * Devuelve el identificador de la competencia asociada al indicador
     * @return int
     */
    public function getIdCompetence() {
        return $this->idCompetence;
    }

    /**
     * Devuelve el identificador del indicador
     * @return int
     */
    public function getIdIndicator() {
        return $this->idIndicator;
    }

    /**
     * Devuelve el nombre del indicador
     * @return String
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Devuelve la descripción del indicador
     * @return String
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Asigna el identificador de la competencia a la que pertenece el
     * indicador
     * @param int $idCompetence
     */
    public function setIdCompetence($idCompetence) {
        $this->idCompetence = $idCompetence;
    }

    /**
     * Asigna el identificador del identificador
     * @param int $idIndicator
     */
    public function setIdIndicator($idIndicator) {
        $this->idIndicator = $idIndicator;
    }

    /**
     * Establece el nombre del identificador
     * @param String $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Establece la descripción del identificador
     * @param String $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Obtiene el código de un indicador
     * @return String
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Establece el código de un indicador
     * @param String $code
     */
    public function setCode($code) {
        $this->code = $code;
    }




}
