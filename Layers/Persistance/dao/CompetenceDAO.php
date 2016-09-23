<?php

include_once (ROOT .'/Interfaces/dao/ICompetenciaDAO.php');


/**
 * Description of CompetenceDAO
 *
 * @author jorge
 */
abstract class CompetenceDAO implements ICompetenciaDAO{
    
    protected $connection;
    
     /**
     * 
     * @param DB_MySQL $conection
     */
    function __construct($conection) {
        $this->connection = $conection;        
    }
    
    abstract public function clean();

    abstract public function delete($id);

    abstract public function execute($query);

    abstract public function get($id);

    abstract public function getAll();

    abstract public function getAllOrderedBy($param, $order);

    abstract public function getCompetencesByName($name);

    abstract public function getCompetencesByYear($year);

    abstract public function getCompetencesbyType($type);

    abstract public function insert($object);

    abstract public function update($object);
}
