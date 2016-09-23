<?php
include_once (ROOT .'/Layers/Persistance/dao/CompetenceTypeDAO.php');
include_once (ROOT .'/objects/CompetenceType.php');
/**
 * Description of CompetenceTypeDAO
 *
 * @author jorge
 */
class CompetenceTypeMySQLDAO extends CompetenceTypeDAO{
    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {   
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.tiposCompetencia";
        return $this->execute($query);       
    }

    /**
     * Elimina un elemento de la base de datos
     * @param int $idCompetenceType -> Identificador de tipo de competencia
    
     * 
     * @return boolean con el resultado de la ejecución de la orden, o -2 si el
     * número de argumentos no fueron 2
     */
    public function delete($idCompetenceType) {

        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.tiposCompetencia WHERE idYear = {$idCompetenceType}";
        return $this->execute($query);
  
        
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto CompetenceType
     * @param String[] $row Array asociativo de Strings
     * @return CompetenceMateria
     */
    private function convertMySQLObjectCurso($row){
        
        $idTypeCompetence = $row['idtiposCompetencia'];
        $name = $row['descripcion'];

  
        return new CompetenceType($idTypeCompetence, $name);
        
    }
    
    /**
     * Obtiene un objeto CompetenceType desde la Base de Datos
     * @param int $idCompetenceType
     * @return CompetenceMateria
     */
    public function get($idCompetenceType) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.tiposCompetencia WHERE idTiposCompetencia = {$idCompetenceType}";
   
  
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLObjectCurso($fila);
        }
      
        return false;
        
        
    }

    /**
     * Obtiene todos los elementos de la tabla de de tipos e competencias que cumplan
     * con la consulta especificada en el argumento
     * @param String $query consulta
     * @return CompetenceMateria[] or boolean(false) en caso de error
     */
    private function getAllByQuery($query){
        $this->execute($query);
       
        $listaObjetosMySQL = null;
        $listaSesiones = null; 
        if($this->connection->getNumRows() > 0){  
           
            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)){
                $listaObjetosMySQL[] = $fila;
                        
            }
            $size = count($listaObjetosMySQL);           
            
            for ($index = 0; $index < $size; $index++) {                
                $listaSesiones[$index] = $this->convertMySQLObjectCurso($listaObjetosMySQL[$index]);
            }
            
            
            return $listaSesiones;
        }
      
        return false;
    }
    /**
     * Obtiene todos los CompetenceType de la Base de Datos
     * @return CompetenceMateria[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.tiposCompetencia";        
        return $this->getAllByQuery($query);
        
    }

    /**
     * Obtiene todos los objetos Curso de la base de datos ordenados en base
     * a los valores de una columna especificada en los argumentos
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return Session[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.tiposCompetencia ORDER BY $param ";
        
            
        if($order == 0){
            $query .="ASC";
        }
        else{
            $query .= "DESC";
        }
            
        return $this->getAllByQuery($query);
    }

    /**
     *  Inserta un objeto Curso en la Base de Datos, retornando su identificador
     * @param CompetenceMateria $object
     * @return int
     */
    public function insert($object) {
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.tiposCompetencia (idTiposCompetencia,descripcion) "
                . "VALUES ({$object->getIdTypeCompetence()},"                
                . "'{$object->getName()}')";
                 
                
        
        return $this->execute($query);
    }

    /**
     * Actualiza la tabla tiposCompetencia
     * @param CompetenceMateria $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.tiposCompetencia "   
                . "SET descripcion = '{$object->getName()}' "               
                . "WHERE idTiposCompetencia = {$object->getIdTypeCompetence()}";  
 
        return $this->execute($query);
    }

    /**
     * Ejecuta una consulta MySQL en la Base de Datos
     * @param String $query
     */
    public function execute($query) {
        $this->connection->connect();
        $res = $this->connection->execute($query);   
        $idInsertada = $this->connection->getIdInserted();
        $this->connection->close();   
        
        /* Si se produjo una  insercion, $idInsertada debe contener
         * El valor de la última inserción realizada. Si no se produjo una inserción
         * su valor será 0
         */
        if($idInsertada == 0){
            return $res;
        }
        else{
            return $idInsertada;
        }
    }


  
}
