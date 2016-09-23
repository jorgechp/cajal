<?php

/**
 *
 * @author jorge
 */
interface ISesionDAO extends Idao_interface {
    
    /**
     * Obtiene todas las sesiones que ha tenido una actividad
     */
    public function getSessionByActivity($idActivity);
    
    /**
     * Obtiene la primera sesión de una actividad
     */
    public function getFirstSession($idActivity);
    
    /**
     * Comprueba si un estudiante asistió a una sesión
     */
    public function isStudentAssisted($idStudent, $idActivity, $idSession);

    /**
     * Marca o desmarca la asistencia de un estudiante a una sesión
     */
    public function checkSession($idStudent,$idActivity,$idSession, $uncheck = -1);
    
    /**
     * Obtiene el máximo identificador de sesión para una actividad
     */
    public function getMaxIdSession($idActivity);


}
