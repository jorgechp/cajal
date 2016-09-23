<?php

require (ROOT .'/Layers/Persistance/dao/CompetenceDAO.php');
require_once  (ROOT .'/objects/Competence.php');

/**
 * Description of CompetenceMySQLDAO
 *
 * @author jorge
 */
class CompetenceMySQLDAO extends CompetenceDAO{
    
    
    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {   
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Competence";
        return $this->execute($query);       
    }

    /**
     * Elimina un elemento de la base de datos
     * @param int $id -> Identificador de la competencia
     * 
     */
    public function delete($id) {
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Competence WHERE idCompetence = ".$id;
       
        return $this->execute($query);
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto Competencia
     * @param String[] $row Array asociativo de Strings
     * @return Competence
     */
    private function convertMySQLObjectCompetence($row){
        
        $idCompetence = $row['idCompetence'];
        $nombre = $row['name'];  
        $descripcion = $row['description'];
        $curso = $row['idYear'];
        $observations = $row['observaciones'];
        $isActive = $row['activo'];
        $time = $row['fecha'];
        $idCreador = $row['idCreador'];
        $idTiposCompetencia = $row['idtiposCompetencia'];
        $codigoGrado = $row['codigoGrado'];    
        $codigo = $row['codigo'];    
        $idTipo = $row['idtiposCompetencia'];   
                    
        //En el modelo EER, un Indicador es una entidad débil cuya existencia
        //depende de una competencia
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Indicator WHERE idCompetence = ".$idCompetence;
        $this->execute($query);
      
            $listaIndicadores = array(); 
          
            if($this->connection->getNumRows() > 0){     
                while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)) {
                   
                    $listaIndicadores[] = $fila["idIndicator"];
                    
                    }            
            }
            
            
            return new Competence(
                    $idCompetence,
                    $nombre,
                    $descripcion,
                    $curso,
                    $listaIndicadores,
                    $observations,
                    $isActive,
                    $time,                    
                    $idTiposCompetencia,
                    $codigoGrado,
                    $idCreador,
                    $codigo,
                    $idTipo
             );
        
       
        
        
    }
    
    /**
     * Obtiene un objeto Competencia desde la Base de Datos
     * @param int $id
     * @return Competencia
     */
    public function get($id) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Competence WHERE idCompetence = {$id}";  
     
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
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Competence";       
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
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Competence ORDER BY $param ";
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
     * @param Competence $object
     * @return int
     */
    public function insert($object) {      
        
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Competence (name,description,idYear, observaciones, activo, fecha, idCreador, idtiposCompetencia, codigoGrado,codigo) "
                . "VALUES ('{$object->getName()}',"
                . "'{$object->getDescription()}',"
                . "{$object->getIdYear()},"
                . "'{$object->getObservations()}',"
                . "{$object->getIsActive()},"  
                . "CURRENT_TIMESTAMP(),"  
                . "{$object->getIdCreator()}," 
                . "{$object->getIdType()},"    
                . "'{$object->getDegreeCode()}',"
                . "'{$object->getCode()}'"
                
                . ")" ;

             
              
        return $this->execute($query);
    }

    /**
     * Actualiza la tabla de competencias
     * @param Competence $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.Competence "
                . "SET name = '{$object->getName()}',"
                . "description = '{$object->getDescription()}',"
                . "idYear = '{$object->getIdYear()}', "
                . "observaciones = '{$object->getObservations()}', "
                . "activo = {$object->getIsActive()} , "
                . "fecha = '{$object->getDate()}' , "
                . "idCreador = {$object->getIdCreator()} , "
                . "idtiposCompetencia = {$object->getIdType()} , "
                . "codigoGrado = '{$object->getDegreeCode()}', "
                . "codigo = '{$object->getCode()}' "            
                . "WHERE idCompetence = {$object->getIdCompetencia()}";  
        
             
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

    public function getCompetencesByName($name) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Competence WHERE name = '{$name}' ORDER BY idTiposCompetencia,name ASC";
  
        return $this->getAllPrivate($query);
        
    }

    public function getCompetencesByYear($year) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Competence WHERE idYear = {$year} ORDER BY idTiposCompetencia,name ASC";
        return $this->getAllPrivate($query);
    }

    public function getCompetencesbyType($type) {
    
        die("No implementado");
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

    /**
     * Obtiene una lista de objetos Competencia asociados a $idActivity
     * @param int $idActivity
     * @return Competencia[]
     */
    public function getCompetencesByActivity($idActivity) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Competence WHERE idCompetence IN ("
                . "SELECT idCompetence FROM {$GLOBALS['DB_NOMBRE']}.Competence_has_Activity WHERE idActivity = {$idActivity}"
                . ") ORDER BY idTiposCompetencia,name ASC";
        
        return $this->getAllByQuery($query);
    }

    /**
     * Obtiene el listado de competencias que empiezan por $text y que no se encuentren en $idActivity
     * Si $idActivity = null, busca todas las competencias que empiezan por $text
     * @param String $text
     */
    public function getCompetencesStartsWith($text, $idActivity = null) {
        $query = "";
        if($idActivity == null){
            $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Competence WHERE name LIKE '{$text}%' OR codigo LIKE '{$text}%'";
        }else{
            $query = "SELECT c.idCompetence "
                    . "AS 'idCompetence', c.name AS 'name',"
                    . " c.description AS 'description',"
                    . " c.idYear AS 'idYear',"
                    . " c.observaciones AS 'observaciones',"
                    . " c.activo AS 'activo',"
                    . " c.fecha AS 'fecha',"
                    . " c.idCreador AS 'idCreador',"
                    . " c.idTiposCompetencia as 'idtiposCompetencia',"
                    . " c.codigo as 'codigo',"
                    . " c.codigoGrado AS 'codigoGrado'"
                    . " FROM {$GLOBALS['DB_NOMBRE']}.Competence c " 
                    . "WHERE c.idCompetence NOT IN(SELECT idCompetence "
                            . "FROM {$GLOBALS['DB_NOMBRE']}.Competence_has_Activity "
                            . "WHERE idActivity = {$idActivity}) "
                    . "AND (name LIKE '{$text}%' OR codigo LIKE '{$text}%') ORDER BY idTiposCompetencia,name ASC";
        }
      
        return $this->getAllByQuery($query);
    }
    
    /**
     * Elimina un indicador de una competencia
     */
    public function removeIndicatorFromCompetence($idCompetence, $idIndicator){
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Indicator WHERE idCompetence = {$idCompetence} AND idIndicator = {$idIndicator};";     
        return $this->execute($query);        
    }
    
    /**
     * Añade un indicador a una competencia
     */
    public function addIndicatorToCompetence($idCompetence,$idIndicator){
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Indicator(idCompetence,idIndicator) VALUES ({$idCompetence},{$idIndicator});";
        return $this->execute($query);
    }
    
    /**
     * 
     * @param int $student
     * @param int $idYear
     */
    public function getCompetenciasMatriculadas($idStudent, $idYear = -1) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.V_Student_has_Competences_Enroled "
        . "WHERE idUser = {$idStudent} ORDER BY idTiposCompetencia, name ASC";
        
        if($idYear == -1){
            $query = $query.";";
        }
        else{
            $query = $query." AND idYear = {$idYear};";
        }
        
        
        return $this->getAllByQuery($query);
        
    }

    public function getCompetencesByCode($code) {
         $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Competence WHERE codigo LIKE '$code'";
        
        return $this->getAllByQuery($query);
    }

}
