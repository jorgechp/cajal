<?php

/*
 *
 * @author jorge
 */
interface ILugarDAO extends Idao_interface{
    
    /**
     * Obtiene todos los lugares relacionados con un centro de estudios
     */
    public function getAllByCentre($idCentre);
    
   
}
