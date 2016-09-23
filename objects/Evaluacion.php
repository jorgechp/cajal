<?php
/**
 * El objeto Evaluation representa la evaluación de un indicador
 *
 * @author jorge
 */
class Evaluacion {
    /**
     * Fecha en la que se realizó la evaluación
     * @var Timestamp 
     */
    private $date;
    
    /**
     * Evaluación numérica de la competencia
     * @var int
     */
    private $evaluacion;
    
    /**
     * Comentario realizado por el examinador acerca de la evaluación
     * @var String
     */
    private $comment;
    
    /**
     * Identificador del estudiante evaluado
     * @var int
     */
    private $idStudent;
    
    /**
     * Identificador de la actividad desde la que se realizó la evaluación
     * @var int 
     */
    private $idActivity;
    
    /**
     * Identificador de la sesión desde la que se realizó la evaluación
     * @var int 
     */
    private $idSession;
    
    /**
     * Identificador de la competencia evaluada
     * @var int
     */
    private $idCompetence;
    
    /**
     * Identificador del indicador evaluado
     * @var int 
     */
    private $idIndicator;
    
    /**
     * Identificador del profesor que ha evaluado el indicador de la competencia.
     * @var int
     */
    private $idProfessor;
    
    /**
     * Identificador del curso al que pertenece la evaluación
     * @var int 
     */
    private $idYear;
    
    /**
     * Identificador del lugar en el que se ha realizado la evaluación
     * @var int 
     */
    private $idPlace;
    
    /**
     * Constructor del objeto Evaluacioon
     * @param Timestamp $date
     * @param int $evaluacion
     * @param String $comment
     * @param int $idStudent
     * @param int $idActivity
     * @param int $idSession
     * @param int $idCompetence
     * @param int $idIndicator
     * @param int $idProfessor
     * @param int $idYear
     */
    function __construct($date, $evaluacion, $comment, $idStudent, $idActivity, $idSession, $idCompetence, $idIndicator, $idProfessor, $idYear) {
        $this->date = $date;
        $this->evaluacion = $evaluacion;
        $this->comment = $comment;
        $this->idStudent = $idStudent;
        $this->idActivity = $idActivity;
        $this->idSession = $idSession;
        $this->idCompetence = $idCompetence;
        $this->idIndicator = $idIndicator;
        $this->idProfessor = $idProfessor;
        $this->idYear = $idYear;
    }
    
    public function getDate() {
        return $this->date;
    }

    public function getEvaluacion() {
        return $this->evaluacion;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getIdStudent() {
        return $this->idStudent;
    }

    public function getIdActivity() {
        return $this->idActivity;
    }

    public function getIdSession() {
        return $this->idSession;
    }

    public function getIdCompetence() {
        return $this->idCompetence;
    }

    public function getIdIndicator() {
        return $this->idIndicator;
    }

    public function getIdProfessor() {
        return $this->idProfessor;
    }

    public function setDate(Timestamp $date) {
        $this->date = $date;
    }

    public function setEvaluacion($evaluacion) {
        $this->evaluacion = $evaluacion;
    }

    public function setComment(String $comment) {
        $this->comment = $comment;
    }

    public function setIdStudent($idStudent) {
        $this->idStudent = $idStudent;
    }

    public function setIdActivity($idActivity) {
        $this->idActivity = $idActivity;
    }

    public function setIdSession($idSession) {
        $this->idSession = $idSession;
    }

    public function setIdCompetence($idCompetence) {
        $this->idCompetence = $idCompetence;
    }

    public function setIdIndicator($idIndicator) {
        $this->idIndicator = $idIndicator;
    }

    public function setIdProfessor($idProfessor) {
        $this->idProfessor = $idProfessor;
    }

    public function getIdYear() {
        return $this->idYear;
    }

    public function setIdYear($idYear) {
        $this->idYear = $idYear;
    }


}
