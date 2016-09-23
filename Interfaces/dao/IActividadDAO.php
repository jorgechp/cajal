<?php

require (ROOT .'/Interfaces/dao/Idao_interface.php');
/**
 *
 * @author jorge
 */
interface IActividadDAO extends Idao_interface{
    
    /**
     * Obtiene las actividades que empiecen por un nombre determinado
     * 
     */
    public function getActivityStartsWith($nombre);
    
    /**
     * Obtiene las actividades de un profesor en un año determinado
     */
    public function getActivityByProfessor($idProfessor, $idYear = -1);
    
    /**
     * Obtiene las actividades de un estudiante en un año determinado
     */
    public function getActivityByStudent($idStudent, $idYear = -1);
    
    /**
     * Obtiene todos los estudiantes asociados a una actividad
     */
    public function getStudentsByActivity($idActivity, $idYear);
    
    /**
     * Obtiene  la actividad que tenga un determinado código
     */
    public function getActivitiesByCode($code);    
    
    /**
     * Obtiene todos los profesores asociados a una actividad
     */
    public function getProfessorsByActivty($idActivity, $idYear);
     
    /**
     * Muestra si una actividad tiene una competencia determinada
     */
    public function isActivityLinkedyByCompetence($idActivity,$idCompetence);
    
    /**
     * Muestra si una actividad es impartida por un profesor
     */
    public function isActivityLinkedByProfessor($idActivity, $idProfessor);
    
    /**
     * Inserta a un estudiante en una actividad en un año determinado
     */
    public function insertStudentInActivity($idActivity,$idStudent, $idYear);
    
    /**
     * Elimina a un estudiante de una actividad
     */
    public function removeStudentOfActivity($idActivity,$idStudent, $idYear);
    
    /**
     * Añade una competencia a una actividad
     */
    public function addCompetenceToActivity($idActivity, $idCompetence);
    
   /**
     * Elimina una competencia de una actividad
     */
    public function removeCompetenceFromActivity($idActivity, $idCompetence);
    
    /**
     * Añade un profesor a una actividad
     */
    public function addProfessorToActivity($idActivity, $idProfessor, $idYear);
    
   /**
     * Elimina un profesor de una actividad
     */
    public function removeProfessorFromActivity($idActivity, $idProfessor, $idYear);
    
    
   
}
