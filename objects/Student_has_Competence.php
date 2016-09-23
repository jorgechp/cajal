<?php


/**
 * Student_has_Competence representa una relación débil respecto a una Competencia
 * para represetar competencias aprobadas.
 * 
 * Una Competencia aprobada tiene información sobre profesor, cursi fecha
 *  y estudiante relacionados con la misma.
 *
 * @author jorge
 */
class Student_has_Competence {
    private $idCompetencia;
    private $idEstudiante;
    private $idProfessor;
    private $fechaSuperacion;
    private $idCurso;
    
    function __construct($idCompetencia, $idEstudiante, $idProfessor, $fechaSuperacion, $idCurso) {
        $this->idCompetencia = $idCompetencia;
        $this->idEstudiante = $idEstudiante;
        $this->idProfessor = $idProfessor;
        $this->fechaSuperacion = $fechaSuperacion;
        $this->idCurso = $idCurso;
    }

    
    public function getIdCompetencia() {
        return $this->idCompetencia;
    }

    public function getIdEstudiante() {
        return $this->idEstudiante;
    }

    public function getIdProfessor() {
        return $this->idProfessor;
    }

    public function getFechaSuperacion() {
        return $this->fechaSuperacion;
    }

    public function getIdCurso() {
        return $this->idCurso;
    }

    public function setIdCompetencia($idCompetencia) {
        $this->idCompetencia = $idCompetencia;
    }

    public function setIdEstudiante($idEstudiante) {
        $this->idEstudiante = $idEstudiante;
    }

    public function setIdProfessor($idProfessor) {
        $this->idProfessor = $idProfessor;
    }

    public function setFechaSuperacion($fechaSuperacion) {
        $this->fechaSuperacion = $fechaSuperacion;
    }

    public function setIdCurso($idCurso) {
        $this->idCurso = $idCurso;
    }


}
