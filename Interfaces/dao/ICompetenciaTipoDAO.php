<?php


/**
 *
 * @author jorge
 */
interface ICompetenciaTipoDAO extends Idao_interface{
    
    /**
     * Obtiene las actividades que empiecen por un nombre determinado
     * 
     */
    public function getCompetenciaTipoStartsWith($nombre);
    
    
    /**
     * Obtener las competencias que empiezan por un código determinado
     */
    public function getCompetenciaTipoByCode($code);
    
}