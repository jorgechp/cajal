<?php

/**
 * Representación de una Actividad
 *
 * @author jorge
 */
class Activity {
    private $idActividad;
    private $curso;
    private $descripcion;
    private $nombre;
    private $listaCompetencias;
    private $isActive;
    private $codigo;
  
    private $idCategoria;
    
    function __construct($idActividad, $curso, $descripcion, $nombre, $listaCompetencias, $isActive,$codigo,$idCategoria) {
        $this->idActividad = $idActividad;
        $this->curso = $curso;
        $this->descripcion = $descripcion;
        $this->nombre = $nombre;    
        $this->listaCompetencias = $listaCompetencias; 
        $this->isActive = $isActive; 
        $this->codigo = $codigo; 
        
        $this->idCategoria = $idCategoria;
    }
    
    public function getIdActividad() {
        return $this->idActividad;
    }

    public function getCurso() {
        return $this->curso;
    }
    public function getIsActive() {
        return $this->isActive;
    }

    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

        public function getDescripcion() {
        return $this->descripcion;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setIdActividad($idActividad) {
        $this->idActividad = $idActividad;
    }

    public function setCurso($curso) {
        $this->curso = $curso;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function addCompetencia($idCompetencia){
        $this->listaCompetencias[] = $idCompetencia;
    }
    
    public function deleteCompetencia($idCompetencia){
        unset($this->listaCompetencias[$idCompetencia]);
    }


    public function getEstadisticaActividad(){
        die("No implementado");        
    }
    
    public function getListaCompetencias(){
        die("No implementado"); 
    }
    
    public function getListaMatriculados(){
        die("No implementado"); 
    }
    
    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getIdCategoria() {
        return $this->idCategoria;
    }

    public function setIdCategoria($idCategoria) {
        $this->idCategoria = $idCategoria;
    }



    
    
}
