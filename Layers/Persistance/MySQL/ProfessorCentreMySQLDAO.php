<?php
require_once (ROOT .'/Layers/Persistance/dao/ProfessorCentreDAO.php');

class ProfessorCentreMySQLDAO extends ProfessorCentreDAO{
 /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {   
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.professor_centro";
        return $this->execute($query);       
    }

    /**
     * Elimina un elemento de la base de datos
     * @param int $idCentro -> Identificador del lugar
    
     * 
     * @return boolean con el resultado de la ejecución de la orden, o -2 si el
     * número de argumentos no fueron 2
     */
    public function delete($idCentro) {
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.professor_centro WHERE idCentro = {$idCentro} ";
        return $this->execute($query); 
        return false;
  
        
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto ProfessorCentre
     * @param String[] $row Array asociativo de Strings
     * @return Lugar
     */
    private function convertMySQLProfessorCentre($row){
        $idCentro = $row['idCentro'];
        $nombre = $row['nombre'];
   
               

    
  
        return new ProfessorCentre($idCentro, $nombre);
        
    }
    
    /**
     * Obtiene un objeto ProfessorCentre desde la Base de Datos
     * @param int $idCentro
     * @return ProfessorCentre
     */
    public function get($idCentro) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.professor_centro WHERE idCentro = {$idCentro}";
       
  
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLProfessorCentre($fila);
        }
      
        return false;
        
        
    }

    /**
     * Obtiene todos los elementos de la tabla de ProfessorCentre que cumplan
     * con la consulta especificada en el argumento
     * @param String $query consulta
     * @return ProfessorCentre[] or boolean(false) en caso de error
     */
    private function getAllByQuery($query){
        $this->execute($query);
       
        $listadoObjetosArea = null;
        $listaAreas = null; 
        if($this->connection->getNumRows() > 0){  
           
            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)){
                $listadoObjetosArea[] = $fila;
                        
            }
            $size = count($listadoObjetosArea);           
            
            for ($index = 0; $index < $size; $index++) {                
                $listaAreas[$index] = $this->convertMySQLProfessorCentre($listadoObjetosArea[$index]);
            }
            
            
            return $listaAreas;
        }
      
        return false;
    }
    
    /**
     * Obtiene todos los ProfessorCentre de la Base de Datos
     * @return ProfessorCentre[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.professor_centro";        
        return $this->getAllByQuery($query);
        
    }

    /**
     * Obtiene todos los objetos ProfessorCentre de la base de datos ordenados en base
     * a los valores de una columna especificada en los argumentos
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return ProfessorCentre[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.professor_centro ORDER BY $param ";
        if($order == 0){
            $query .="ASC";
        }
        else{
            $query .= "DESC";
        }
            
        return $this->getAllByQuery($query);
    }

    /**
     *  Inserta un objeto ProfessorCentre en la Base de Datos, retornando su identificador
     * @param ProfessorCentre $object
     * @return int
     */
    public function insert($object) {
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.professor_centro (nombre) "
                . "VALUES ('{$object->getNombre()}')";
                
        return $this->execute($query);
    }

    /**
     * Actualiza la tabla de lugares
     * @param ProfessorCentre $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.professor_centro "
                . "SET idCentro = {$object->getCodigo()},"  
                . "nombre = '{$object->getNombre()}' "   ;
   

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

    public function getCentroByName($name) {
         
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.professor_centro WHERE nombre = '{$name}'";
       
  
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLProfessorCentre($fila);
        }
      
        return false;
    
    }

}
