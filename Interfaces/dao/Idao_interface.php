<?php



/**
 *
 * Interfaz para el acceso a datos que debería implementar cualquier archivo
 * DAO
 * @author jorge
 */
interface Idao_interface {
    /**
     * Obtiene el objeto cuyo identificador es $id     * 
     */
    public function get($id);
    
    /**
     * Obtiene todos los objetos
     */
    public function getAll();
    
    /**
     * Obtiene todos los objetos ordenados en función del parámetro
     * @param Columna por la cual se realizará la ordenación
     * @order Ascendente/Descendente
     */
    public function getAllOrderedBy($param,$order);
    
    /**
     * Elimina el objeto identificado por $id
     */
    public function delete($id);
    
    /**
     * Inserta un nuevo objeto
     */
    public function insert($object);
    
    /**
     * Actualiza el objeto $object
     */
    public function update($object);
    
    /**
     * Elimina todos los objetos
     */
    public function clean();
    
    /**
     * Ejecuta una orden en la Base de Datos
     */
    public function execute($query);
    
}
