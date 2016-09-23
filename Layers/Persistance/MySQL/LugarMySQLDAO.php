<?php
require_once (ROOT .'/Layers/Persistance/dao/LugarDAO.php');
/**
 * Description of LugarMySQLDAO
 *
 * @author jorge
 */
class LugarMySQLDAO extends LugarDAO{
    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {   
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Lugar";
        return $this->execute($query);       
    }

    /**
     * Elimina un elemento de la base de datos
     * @param int $idLugar -> Identificador del lugar
    
     * 
     * @return boolean con el resultado de la ejecución de la orden, o -2 si el
     * número de argumentos no fueron 2
     */
    public function delete($idLugar) {
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Lugar WHERE idLugar = {$idLugar} ";
        return $this->execute($query); 
        return false;
  
        
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto Lugar
     * @param String[] $row Array asociativo de Strings
     * @return Lugar
     */
    private function convertMySQLObjectLugar($row){
        $idLugar = $row['idLugar'];
        $nombre = $row['nombre'];
        $descripcion= $row['descripcion'];  
        $idCentro = $row['idCentro'];          

    
  
        return new Lugar($idLugar, $nombre, $descripcion, $idCentro);
        
    }
    
    /**
     * Obtiene un objeto Lugar desde la Base de Datos
     * @param int $idLugar
     * @return Lugar
     */
    public function get($idLugar) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Lugar WHERE idLugar = {$idLugar}";
       
  
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLObjectLugar($fila);
        }
      
        return false;
        
        
    }

    /**
     * Obtiene todos los elementos de la tabla de Lugares que cumplan
     * con la consulta especificada en el argumento
     * @param String $query consulta
     * @return Lugar[] or boolean(false) en caso de error
     */
    private function getAllByQuery($query){
        $this->execute($query);
       
        $listadoObjetosLugaresMySQL = null;
        $listaLugares = null; 
        if($this->connection->getNumRows() > 0){  
           
            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)){
                $listadoObjetosLugaresMySQL[] = $fila;
                        
            }
            $size = count($listadoObjetosLugaresMySQL);           
            
            for ($index = 0; $index < $size; $index++) {                
                $listaLugares[$index] = $this->convertMySQLObjectLugar($listadoObjetosLugaresMySQL[$index]);
            }
            
            
            return $listaLugares;
        }
      
        return false;
    }
    
    /**
     * Obtiene todos los Lugares de la Base de Datos
     * @return Lugar[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Lugar";        
        return $this->getAllByQuery($query);
        
    }

    /**
     * Obtiene todos los objetos Lugar de la base de datos ordenados en base
     * a los valores de una columna especificada en los argumentos
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return Lugar[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Lugar ORDER BY $param ";
        if($order == 0){
            $query .="ASC";
        }
        else{
            $query .= "DESC";
        }
            
        return $this->getAllByQuery($query);
    }

    /**
     *  Inserta un objeto Lugar en la Base de Datos, retornando su identificador
     * @param Lugar $object
     * @return int
     */
    public function insert($object) {
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Lugar (nombre,descripcion,idCentro) "
                . "VALUES ({$idMessage},"
                . "{$object->getNombre()},"
                . "'{$object->getDescripcion()}',"
                . "'{$object->getIdCentro()}')";
                
        return $this->execute($query);
    }

    /**
     * Actualiza la tabla de lugares
     * @param Lugar $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.Lugar "
                . "SET idLugar = {$object->getIdLugar()},"  
                . "nombre = '{$object->getNombre()}', "               
                . "descripcion = '{$object->getDescripcion()}', " 
                . "idCentro = '{$object->getIdCentro()}' " ;
   

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
     * Obtiene todos los lugares asociados a un centro
     * @param int $idCentre
     * @return Lugar[]
     */
    public function getAllByCentre($idCentre) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Lugar WHERE idCentro = {$idCentre}";
        
        return $this->getAllByQuery($query);        
    }

}
