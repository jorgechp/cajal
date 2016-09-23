<?php


include_once (ROOT .'/Layers/Persistance/dao/IndicatorDAO.php');
/**
 * Description of IndicadorMySQLDAO
 *
 * @author jorge
 */
class IndicadorMySQLDAO extends IndicatorDAO {
   
    


    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {   
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Indicator";
        return $this->execute($query);       
    }

    /**
     * Elimina un elemento de la base de datos
     * @param int $idCompetence -> Identificador de la competencia
     * @param int $idIndicator -> Identificador del indicador
     * 
     * @return boolean con el resultado de la ejecución de la orden, o -2 si el
     * número de argumentos no fueron 2
     */
    public function delete($idCompetence) {
        if(func_num_args() == 2 ){
            $segundoArgumento = func_get_arg(1);
            $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Indicator WHERE idCompetence = {$idCompetence} AND idIndicator = {$segundoArgumento}";
            return $this->execute($query);
        }
        return -2;
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto Indicador
     * @param String[] $row Array asociativo de Strings
     * @return Indicator
     */
    private function convertMySQLObjectIndicator($row){
        
        $idIndicator = $row['idIndicator'];
        $nombre = $row['name'];  
        $idCompetence = $row['idCompetence'];    
        $descripcion = $row['description'];
        $codigo= $row['codigo'];
        return new Indicator($idCompetence, $idIndicator, $nombre, $descripcion,$codigo);
    }
    
    /**
     *  Obtiene un objeto Indicador desde la Base de Datos
     * @param int $idCompetence
     * @return Indicator
     */
    public function get($idCompetence) {
        if(func_num_args() != 2 ){
            return -2;
        }
        $idIndicator = func_get_arg(1);
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Indicator WHERE idCompetence = {$idCompetence} AND idIndicator = {$idIndicator}"; 
     
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLObjectIndicator($fila);
        }
      
        return false;
        
        
    }

    /**
     * Obtiene todos los elementos de la tabla de Indicadores que cumplan
     * con la consulta especificada en el argumento
     * @param String $query consulta
     * @return Indicator[] or boolean(false) en caso de error
     */
    private function getAllByQuery($query){
        $this->execute($query);
        
       
        $listaObjetosMySQL = null;
        $listaIndicadores = null; 
        if($this->connection->getNumRows() > 0){  
           
            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)){
                $listaObjetosMySQL[] = $fila;
                        
            }
            $size = count($listaObjetosMySQL);           
            
            for ($index = 0; $index < $size; $index++) {                
                $listaIndicadores[$index] = $this->convertMySQLObjectIndicator($listaObjetosMySQL[$index]);
            }
            
            
            return $listaIndicadores;
        }
      
        return false;
    }
    /**
     * Obtiene todos los indicadores de la Base de Datos
     * @return Indicator[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Indicator";        
        return $this->getAllByQuery($query);
        
    }

    /**
     * Obtiene todos los objetos Indicator de la base de datos ordenados en base
     * a los valores de una columna especificada en los argumentos
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return Indicator[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Indicator ORDER BY $param ";
        if($order == 0){
            $query .="ASC";
        }
        else{
            $query .= "DESC";
        }
            
        return $this->getAllByQuery($query);
    }

    /**
     *  Inserta un objeto Indicator en la Base de Datos, retornando su identificador
     * @param Indicator $object
     * @return int
     */
    public function insert($object) {
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Indicator (idCompetence,name, codigo,description) "
                . "VALUES ({$object->getIdCompetence()},"
                . "'{$object->getName()}',"
                . "'{$object->getCode()}',"
                . "'{$object->getDescription()}')"; 
                
        
        return $this->execute($query);
    }

    /**
     * Actualiza la tabla de indicadores
     * @param Indicator $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.Indicator "
                . "SET description = '{$object->getDescription()}',"
                . "idCompetence = {$object->getIdCompetence()},"
                . "name = '{$object->getName()}', "                
                . "codigo = '{$object->getCode()}' " 
                . "WHERE idCompetence = '{$object->getIdCompetence()}' AND idIndicator = '{$object->getIdIndicator()}'";  
                
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
     * Obtiene todos los indicadores asociados a una determinada
     * competencia
     * @param int $idCompetence
     * @return Indicator[]
     */
    public function getIndicatorByCompetenceId($idCompetence) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Indicator WHERE idCompetence = {$idCompetence}";  
        return $this->getAllByQuery($query);
    }

    /**
     * Obtiene un listado de indicadores pertenecientes a una competencia
     */
    public function getIndicatorsByCompetence($idCompetence){
        return $this->getIndicatorByCompetenceId($idCompetence);
    }
    
    /**
     * OBtiene un listado de indicadores que empiezan por la cadena especificada
     */
    public function getIndicatorsStartsWith($text){
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Indicator WHERE name LIKE '{$text}%'";
        return $this->getAllByQuery($query);   
    }

    public function getIndicatorByCode($code) {
        
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Indicator WHERE codigo LIKE '{$code}'";
        
        return $this->getAllByQuery($query);           
    }

}
