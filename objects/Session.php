<?php

/**
 * Representa y maneja sesiones
 *
 * @author jorge
 */
class Session {
    /**
     *
     * @var int 
     */
    private $idActivity;
    
    /**
     *
     * @var int 
     */
    private $idSession;
    
    /**
     *
     * @var timestamp 
     */
    private $dateStart;
    
    /**
     *
     * @var timestamp 
     */
    private $dateEnd;
    
    /**
     *
     * @var string 
     */
    private $password;
    
    /**
     *
     * @var int 
     */
    private $idLugar;
    
    function __construct($idActivity, $idSession, $dateStart, $dateEnd, $password,$idLugar) {
        $this->idActivity = $idActivity;
        $this->idSession = $idSession;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->password = $password;
        $this->idLugar = $idLugar;
    }
    
    /**
     * 
     * @return int
     */
    public function getIdActivity() {
        return $this->idActivity;
    }

    /**
     * 
     * @return int
     */
    public function getIdSession() {
        return $this->idSession;
    }

    /**
     * 
     * @return timestamp
     */
    public function getDateStart() {
        return $this->dateStart;
    }

    /**
     * 
     * @return timestamp
     */
    public function getDateEnd() {
        return $this->dateEnd;
    }

    /**
     * 
     * @return String
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * 
     * @param int $idActivity
     */
    public function setIdActivity($idActivity) {
        $this->idActivity = $idActivity;
    }

    /**
     * 
     * @param int $idSession
     */
    public function setIdSession($idSession) {
        $this->idSession = $idSession;
    }

    /**
     * 
     * @param timestamp $dateStart
     */
    public function setDateStart($dateStart) {
        $this->dateStart = $dateStart;
    }

    /**
     * 
     * @param timestamp $dateEnd
     */
    public function setDateEnd($dateEnd) {
        $this->dateEnd = $dateEnd;
    }

    /**
     * 
     * @param timestamp $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function getIdLugar() {
        return $this->idLugar;
    }

    public function setIdLugar($idLugar) {
        $this->idLugar = $idLugar;
    }




}
