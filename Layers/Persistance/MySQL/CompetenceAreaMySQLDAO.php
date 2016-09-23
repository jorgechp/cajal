<?php

require (ROOT .'/Layers/Persistance/dao/CompetenceAreaDAO.php');
require_once  (ROOT .'/objects/CompetenceMateria.php');

/**
 * Description of CompetenceAreaMySQLDAO
 *
 * @author jorge
 */
class CompetenceAreaMySQLDAO extends CompetenceAreaDAO{
    
    
    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {   
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Competence_Materia";
        return $this->execute($query);       
    }

    /**
     * Elimina un elemento de la base de datos
     * @param int $id -> Identificador de la competencia
     * 
     */
    public function delete($id) {
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Competence_Materia WHERE idCompetenceArea = ".$id;
       
        return $this->execute($query);
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto Competencia
     * @param String[] $row Array asociativo de Strings
     * @return Competence
     */
    private function convertMySQLObjectCompetence($row){
        
        $idCompetence = $row['idMateria'];
        $nombre = $row['nombre'];  
        $codigo = $row['codigo'];


            return new CompetenceMateria(
                    $idCompetence,
                    $nombre,
                    $codigo
             );
        
       
        
        
    }
    
    /**
     * Obtiene un objeto Competencia desde la Base de Datos
     * @param int $id
     * @return Competencia
     */
    public function get($id) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Competence_Materia WHERE idCompetenceArea = {$id}";  
     
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLObjectCompetence($fila);
        }
      
        return false;
        
        
    }
    
    private function getAllPrivate($query){
        $this->execute($query);
       
        $listaObjetosMySQL = null;
        $listaCompetencias = null; 
        if($this->connection->getNumRows() > 0){  
           
            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)){
                $listaObjetosMySQL[] = $fila;
                        
            }
            $size = count($listaObjetosMySQL);           
            
            for ($index = 0; $index < $size; $index++) {                
                $listaCompetencias[$index] = $this->convertMySQLObjectCompetence($listaObjetosMySQL[$index]);
            }
            
            
            return $listaCompetencias;
        }
      
        return false;
    }

    /**
     * Obtiene todas las competencias de la Base de Datos
     * @return Competence[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Competence_Materia";       
        return $this->getAllPrivate($query);
        
    }

    /**
     * Obtiene todos los objetos Competence de la base de datos ordenados en base
     * a los valores de una columna
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return Competence[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Competence_Materia ORDER BY $param ";
        if($order == 0){
            $query .="ASC";
        }
        else{
            $query .= "DESC";
        }
            
        $this->execute($query);
       
        $listaObjetosMySQL = null;
        $listaCompetencias = null; 
        if($this->connection->getNumRows() > 0){  
           
            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)){
                $listaObjetosMySQL[] = $fila;
                        
            }
            $size = count($listaObjetosMySQL);           
            
            for ($index = 0; $index < $size; $index++) {                
                $listaCompetencias[$index] = $this->convertMySQLObjectCompetence($listaObjetosMySQL[$index]);
            }
            
            
            return $listaCompetencias;
        }
      
        return false;
    }
    


    
    /**
     *  Inserta un objeto Competencia en la Base de Datos, retornando su identificador
     * @param CompetenceMateria $object
     * @return int
     */
    public function insert($object) {      
        
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Competence_Materia (nombre,codigo) "
                . "VALUES ('{$object->getNombre()}',"
                . "'{$object->getCodigo()}'"
                . ")" ;

             
              
        return $this->execute($query);
    }

    /**
     * Actualiza la tabla de competencias
     * @param CompetenceMateria $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.Competence "
                . "SET nombre = '{$object->getNombre()}',"
                . "codigo = '{$object->getCodigo()}',"
                . "WHERE idCompetence = {$object->getIdArea()}";  
        
                
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


     /**
     * Obtiene todos los elementos de la tabla de de tipos e competencias que cumplan
     * con la consulta especificada en el argumento
     * @param String $query consulta
     * @return Competence[] or boolean(false) en caso de error
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
                $listaSesiones[$index] = $this->convertMySQLObjectCompetence($listaObjetosMySQL[$index]);
            }
            
            
            return $listaSesiones;
        }
      
        return false;
    }


    public function getCompetenceAreaStartsBy($text) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Competence_Materia WHERE nombre LIKE '{$text}' OR codigo  LIKE '{$text}'; ";
  
        return $this->getAllPrivate($query);
        
    }

}
