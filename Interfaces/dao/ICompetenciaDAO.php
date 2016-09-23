<?php

include_once (ROOT .'/Interfaces/dao/Idao_interface.php');

/**
 *
 * @author jorge
 */
interface ICompetenciaDAO extends Idao_interface {
    
    /**
     * Obtiene todas las competencias de un curso específico
     */
    public function getCompetencesByYear($year);
    
    
    /**
     * Obtiene todas las competencias del mismo nombre que fueron
     * creadas en diferentes cursos.
     */
    public function getCompetencesByName($name);
    
    /**
     * Obtiene todas las competencias de un tipo determinado
     */
    public function getCompetencesbyType($type);
    

    /**
     * Obtiene todas las competencias relacionadas con una actividad
     */
    public function getCompetencesByActivity($idActivity);
    
    
    /**
     * Obtiene las competencias que empiezan por $text y que no se encuentren en $idActivity
     */    
    public function getCompetencesStartsWith($text, $idActivity = null);
    
    
        /**
     * Elimina un indicador de una competencia
     */
    public function removeIndicatorFromCompetence($idCompetence, $idIndicator);
        

    /**
     * Obtiene las competencias matriculadas en el curso identificado por
     * $idCurso para el estudiante $idStudent
     */
    public function getCompetenciasMatriculadas($idStudent,$idYear);
    
    public function getCompetencesByCode($code);
}
