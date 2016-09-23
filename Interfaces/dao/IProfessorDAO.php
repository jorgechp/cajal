<?php

/**
 *
 * DAO para el acceso a la información relacionada con el Profesor
 * @author jorge
 */
interface IProfessorDAO extends IUsuarioDAO {
    /**
     * Obtiene una lista de profesores asociada a una actividad
     */
    public function getProfessorsByActivity($idActivity, $idYear);
    
    /**
     * Obtiene una lista de profesores cuyo DNI empieza por $text. Si $idActivity != null, busca aquellos que no impartan $idActivity
     * 
     */
    public function getProfessorsStartsWith($text, $idActivity = null);
    
}
