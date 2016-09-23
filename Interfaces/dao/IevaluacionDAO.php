<?php


/**
 * Interfaz para los objetos DAO que trabajen con Evaluaciones
 * @author jorge
 */
interface IevaluacionDAO extends Idao_interface{
    /**
     * Obtiene el histórico de la evaluación de un indicador para un estudiante en un curso determinado
     */
    public function getEvaluationByIndicator($idCompetencia, $idIndicator, $idStudent, $idSession = null, $idYear, $idActivity = -1);
    
    /**
     * Obtiene la última evaluación realizada a cada indicador
     */
    public function getLastEvaluationByIndicator($idCompetencia, $idIndicator, $idStudent, $idYear, $idActivity = -1);
    
    /**
     * Obtiene la media de las calificaciones última evaluación realizada a cada indicador
     */
    public function getMeanLastEvaluationByIndicator($idCompetencia, $idIndicator, $idStudent, $idYear, $idActivity = -1, $strict = false);   
    
    
    /**
     * Obtiene el máximo de las calificaciones última evaluación realizada a cada indicador
     */
    public function getMaxLastEvaluationByIndicator($idCompetencia, $idIndicator, $idStudent, $idYear, $idActivity = -1);
    
    public function getLastPlaceEvaluatedOnIndicator($idCompetencia,$idIndicator,$idStudent);
    

}
