<?php

/**
 *
 * @author jorge
 */
interface IIndicadorDAO extends Idao_interface{
    
    /**
     * Obtiene todos los indicadores asociados a una competencia
     */
    public function getIndicatorByCompetenceId($idCompetence);
    
        /**
     * Obtiene un listado de indicadores pertenecientes a una competencia
     */
    public function getIndicatorsByCompetence($idCompetence);
    
    /**
     * OBtiene un listado de indicadores que empiezan por la cadena especificada
     */
    public function getIndicatorsStartsWith($text);
    

    public function getIndicatorByCode($code);
}
