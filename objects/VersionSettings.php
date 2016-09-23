<?php


/**
 * Almacena las opciones de versión de un programa. Necsarias para
 * gestionar actualizaciones de la aplicación
 *
 * @author jorge
 */
class VersionSettings {
   
    /**
     * Número de la versión de la aplicación
     * @var int 
     */
    private $numberOfVersion;
    
    /**
     * Número de la revisión e la versión
     * @var int 
     */
    private $numberOfMinorVersion;
    
    /**
     * Número de parche de la revisión
     * @var int 
     */
    private $numberOfPatch;
    
    /**
     * Descripción del parche
     * @var String 
     */
    private $descriptionOfPatch;
    
    /**
     * Fecha de la revisión
     * @var Timestamp 
     */
    private $dateTime;
    
    /**
     * Booleano. Si true, la actualización es crítica, false en caso contrario
     * @var boolean 
     */
    private $isCritical;
    
    function __construct($numberOfVersion, $numberOfMinorVersion, $numberOfPatch, $descriptionOfPatch, $dateTime, $isCritical, $idCurrentYear) {
        $this->numberOfVersion = $numberOfVersion;
        $this->numberOfMinorVersion = $numberOfMinorVersion;
        $this->numberOfPatch = $numberOfPatch;
        $this->descriptionOfPatch = $descriptionOfPatch;
        $this->dateTime = $dateTime;
        $this->isCritical = $isCritical;
        $this->idCurrentYear = $idCurrentYear;
    }

    
    public function getNumberOfVersion() {
        return $this->numberOfVersion;
    }

    public function getNumberOfMinorVersion() {
        return $this->numberOfMinorVersion;
    }

    public function getNumberOfPatch() {
        return $this->numberOfPatch;
    }

    public function getDescriptionOfPatch() {
        return $this->descriptionOfPatch;
    }

    public function getDateTime() {
        return $this->dateTime;
    }

    public function getIsCritical() {
        return $this->isCritical;
    }

    public function setNumberOfVersion($numberOfVersion) {
        $this->numberOfVersion = $numberOfVersion;
    }

    public function setNumberOfMinorVersion($numberOfMinorVersion) {
        $this->numberOfMinorVersion = $numberOfMinorVersion;
    }

    public function setNumberOfPatch($numberOfPatch) {
        $this->numberOfPatch = $numberOfPatch;
    }

    public function setDescriptionOfPatch($descriptionOfPatch) {
        $this->descriptionOfPatch = $descriptionOfPatch;
    }

    public function setDateTime($dateTime) {
        $this->dateTime = $dateTime;
    }

    public function setIsCritical($isCritical) {
        $this->isCritical = $isCritical;
    }




}
