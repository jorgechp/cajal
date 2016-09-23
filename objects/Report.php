<?php


/**
 * Description of Report
 *
 * @author jorge
 */
class Report {
    /**
     *
     * @var int 
     */
    private $idReport;
    
    /**
     *
     * @var int 
     */
    private $status;
    
    /**
     *
     * @var string 
     */
    private $subject;
    
    /**
     *
     * @var string 
     */
    private $text;
    
    /**
     *
     * @var datetime 
     */
    private $date;
    
    /**
     *
     * @var int 
     */
    private $idSender;
    
    /**
     *
     * @var int 
     */
    private $priority;
    
    /**
     *
     * @var boolean 
     */
    private $isAnError;
    
    function __construct($status, $subject, $text, $date, $idSender, $priority, $isAnError) {
        $this->status = $status;
        $this->subject = $subject;
        $this->text = $text;
        $this->date = $date;
        $this->idSender = $idSender;
        $this->priority = $priority;
        $this->isAnError = $isAnError;
    }

    
    public function getIdReport() {
        return $this->idReport;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function getText() {
        return $this->text;
    }

    public function getDate() {
        return $this->date;
    }

    public function getIdSender() {
        return $this->idSender;
    }

    public function getPriority() {
        return $this->priority;
    }

    public function getIsAnError() {
        return $this->isAnError;
    }

    public function setIdReport($idReport) {
        $this->idReport = $idReport;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function setIdSender($idSender) {
        $this->idSender = $idSender;
    }

    public function setPriority($priority) {
        $this->priority = $priority;
    }

    public function setIsAnError($isAnError) {
        $this->isAnError = $isAnError;
    }


}
