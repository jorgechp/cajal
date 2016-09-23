<?php


/**
 *
 * @author jorge
 */
interface IActividadTipoDAO extends Idao_interface{
    
    /**
     * Obtiene las actividades que empiecen por un nombre determinado
     * 
     */
    public function getActivityTipoStartsWith($nombre);
    
}