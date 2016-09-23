<?php


/**
 * Representa un mensaje entre usuarios
 *
 * @author jorge
 */
class Message {
    /**
     * Identificador del mensaje en la Base de datos
     * @var integer
     */
    private $idMessage;
    
    /**
     * Identificador del remitente del mensaje
     * @var integer
     */
    private $idSender;
    
    /**
     * Asunto del mensaje
     * @var string
     */
    private $subject;
    
    /**
     * Contenido del mensaje
     * @var string
     */
    private $message;
    
    /**
     * Fecha de envío del mensaje
     * @var timestamp 
     */
    private $date;
    
    /**
     * Identificador del mensaje al que responde el mensaje actual. 
     * Si el mensaje es un mensaje Padre, el valor de la variable
     * será null
     * @var integer 
     */
    private $idMessageReplied;
    

    
    function __construct($idMessage, $idSender, $subject, $message, $date, $idMessageReplied) {
        $this->idMessage = $idMessage;
        $this->idSender = $idSender;
        $this->subject = $subject;
        $this->message = $message;
        $this->idMessageReplied = $idMessageReplied;
        $this->date = $date;
    }
    
    public function getIdMessage() {
        return $this->idMessage;
    }

    public function getIdSender() {
        return $this->idSender;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getIdMessageReplied() {
        return $this->idMessageReplied;
    }

    public function setIdMessage($idMessage) {
        $this->idMessage = $idMessage;
    }

    public function setIdSender($idSender) {
        $this->idSender = $idSender;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setIdMessageReplied($idMessageReplied) {
        $this->idMessageReplied = $idMessageReplied;
    }
    
    public function getDate() {
        return $this->date;
    }

    public function setDate(timestamp $date) {
        $this->date = $date;
    }





}
