<?php

include_once (ROOT .'/Layers/Persistance/dao/DaoClass.php');
include_once (ROOT .'/Interfaces/dao/ICompetenciaTipoDAO.php');
include_once (ROOT .'/objects/CompetenciaTipo.php');

/**
 * Clase para el manejo de Actividades en un SGBD
 *
 * @author jorge
 */
abstract class CompetenciaTipoDAO extends DaoClass implements ICompetenciaTipoDAO {

    /**
     * Elimina todos los elementos de la Base de Datos
     */
    abstract public function clean();

    /**
     * Elimina un elemento de la base de datos
     * @param int $id -> Identificador de la actividad
     * 
     */
    abstract public function delete($id);


    
    /**
     *  Obtiene un objeto actividad desde la Base de Datos
     * @param int $id
     * @return Activity
     */
    abstract public function get($id);

    /**
     * Obtiene todas las actividades de la Base de Datos
     * @return Activity[]
     */
    abstract public function getAll();

    /**
     * Obtiene todos los objetos Actividad de la base de datos ordenados en base
     * a los valores de una columna
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return Activity[]
     */
    abstract public function getAllOrderedBy($param, $order = 0);

    /**
     *  Inserta un objeto Actividad en la Base de Datos, retornando su identificador
     * @param Activity $object
     * @return int
     */
    abstract public function insert($object);

    /**
     * Actualiza la tabla de actividades
     * @param Activity $object
     * @return boolean
     */
    abstract public function update($object);

    /**
     * Ejecuta una consulta MySQL en la Base de Datos
     * @param String $query
     */
    abstract public function execute($query);

}

