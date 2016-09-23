<?php


/**
 * Description of Curso
 *
 * @author jorge
 */
class Curso {
    private $idYear;
    private $initialYear;
    private $finalYear;
    private $description;
    private $listaActividades;
    private $listaCompetencias;
    private $listaProfesores;
    
    function __construct($idYear= null, $initialYear, $finalYear, $description = null, $listaActividades = null, $listaCompetencias = null, $listaProfesores = null) {
        $this->idYear = $idYear;
        $this->initialYear = $initialYear;
        $this->finalYear = $finalYear;
        $this->description = $description;
        $this->listaActividades = $listaActividades;
        $this->listaCompetencias = $listaCompetencias;
        $this->listaProfesores = $listaProfesores;
    }


    
    public function getIdYear() {
        return $this->idYear;
    }

    public function getInitialYear() {
        return $this->initialYear;
    }

    public function getFinalYear() {
        return $this->finalYear;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getListaActividades() {
        return $this->listaActividades;
    }

    public function getListaCompetencias() {
        return $this->listaCompetencias;
    }

    public function getListaProfesores() {
        return $this->listaProfesores;
    }

    public function setIdYear($idYear) {
        $this->idYear = $idYear;
    }

    public function setInitialYear($initialYear) {
        $this->initialYear = $initialYear;
    }

    public function setFinalYear($finalYear) {
        $this->finalYear = $finalYear;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setListaActividades($listaActividades) {
        $this->listaActividades = $listaActividades;
    }

    public function setListaCompetencias($listaCompetencias) {
        $this->listaCompetencias = $listaCompetencias;
    }

    public function setListaProfesores($listaProfesores) {
        $this->listaProfesores = $listaProfesores;
    }


    
    
}
