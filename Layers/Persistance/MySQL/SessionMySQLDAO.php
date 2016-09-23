<?php

include_once (ROOT .'/Layers/Persistance/dao/SessionDAO.php');

/**
 * Implementación de SessionDAO para MySQL
 *
 * @author jorge
 */
class SessionMySQLDAO extends SessionDAO {
    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {   
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Session";
        return $this->execute($query);       
    }

    /**
     * Elimina un elemento de la base de datos
     * @param int $idActivity -> Identificador de la actividad
     * @param int $idSession -> Identificador de la sesión
     * 
     * @return boolean con el resultado de la ejecución de la orden, o -2 si el
     * número de argumentos no fueron 2
     */
    public function delete($idActivity) {
        if(func_num_args() == 2 ){
            $idSession = func_get_arg(1);
            $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Session WHERE idActivity = {$idActivity} AND idSession = {$idSession}";
           
            return $this->execute($query);
        }
        return -2;
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto Session
     * @param String[] $row Array asociativo de Strings
     * @return Session
     */
    private function convertMySQLObjectSession($row){
        
        $idActivity = $row['idActivity'];
        $idSession = $row['idSession'];
        $dateStart = $row['dateStart'];  
        $dateEnd = $row['dateEnd'];  
        $password = $row['password'];    
        $idLugar = $row['idLugar'];
        
        return new Session($idActivity, $idSession, $dateStart, $dateEnd, $password, $idLugar);
    }
    
    /**
     *  Obtiene un objeto Session desde la Base de Datos
     * @param int $idCompetence
     * @param int $idSession
     * @return Session
     */
    public function get($idActivity) {
        if(func_num_args() != 2 ){
            return -2;
        }
        $idSession = func_get_arg(1);
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Session WHERE idActivity = {$idActivity} AND idSession = {$idSession}";
       
  
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLObjectSession($fila);
        }
      
        return false;
        
        
    }

    /**
     * Obtiene todos los elementos de la tabla de Sesiones que cumplan
     * con la consulta especificada en el argumento
     * @param String $query consulta
     * @return Session[] or boolean(false) en caso de error
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
                $listaSesiones[$index] = $this->convertMySQLObjectSession($listaObjetosMySQL[$index]);
            }
            
            
            return $listaSesiones;
        }
      
        return false;
    }
    /**
     * Obtiene todas las sesiones de la Base de Datos
     * @return Session[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Session";        
        return $this->getAllByQuery($query);
        
    }

    /**
     * Obtiene todos los objetos Session de la base de datos ordenados en base
     * a los valores de una columna especificada en los argumentos
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return Session[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Session ORDER BY $param ";
        if($order == 0){
            $query .="ASC";
        }
        else{
            $query .= "DESC";
        }
            
        return $this->getAllByQuery($query);
    }

    /**
     *  Inserta un objeto Session en la Base de Datos, retornando su identificador
     * @param Session $object
     * @return int
     */
    public function insert($object) {
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Session (idActivity,idSession,dateStart,dateEnd,password,idLugar) "
                . "VALUES ({$object->getIdActivity()},"
                . "'{$object->getIdSession()}',"
                . "'{$object->getDateStart()}',"
                . "'{$object->getDateEnd()}',"
                . "'{$object->getPassword()}',"
                . "{$object->getIdLugar()})"; 
                
        
         return $this->execute($query);
    }

    /**
     * Actualiza la tabla de actividades
     * @param Session $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.Session "
                . "SET idActivity = {$object->getIdActivity()},"
                . "dateStart = {$object->getDateStart()},"
                . "dateEnd = {$object->getDateEnd()}, "
                . "password = '{$object->getPassword()}' "
                . "idLugar = {$object->getIdLugar()} "
                . "WHERE idActivity = {$object->getIdActivity()} AND idSession = {$object->getIdSession()}";  
          
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
     * Obtiene todas las sesiones asociadas a una actividad concreta ordenadas
     * por fecha de inicio (dateStart) ascendente.
     * @param type $idActivity
     * @return Session[]
     */
    public function getSessionByActivity($idActivity) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Session WHERE idActivity = {$idActivity} ORDER BY dateStart ASC"; 
        
        return $this->getAllByQuery($query);
    }

    public function getFirstSession($idActivity) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Session WHERE idActivity = {$idActivity} ORDER BY dateStart DESC LIMIT 1";  
        
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLObjectSession($fila);
        }
      
        return false;
    }

    /**
     * Comprueba si un estudiante acudió a una sesión
     * @param int $idStudent
     * @param int $idActivity
     * @param int $idSession
     * @return boolean
     */
    public function isStudentAssisted($idStudent, $idActivity, $idSession) {
        $query = "SELECT COUNT(*) AS 'ASSISTED' FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Session WHERE idUser = {$idStudent} AND idActivity = {$idActivity} AND idSession = {$idSession}";
        
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $fila['ASSISTED'];
        }
      
        return false; 
    }

    /**
     * Marca o desmarca la asistencia de un estudiante a una sesión
     * si $uncheck != -1, la sesión es desmarcada
     * 
     * @param int $idStudent
     * @param int $idActivity
     * @param int $idSession
     * @param int $uncheck
     */
    public function checkSession($idStudent, $idActivity, $idSession, $uncheck = -1) {
        $query = "";
        if($uncheck != -1){
            $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Student_has_Session(idUser,idActivity,idSession) VALUES($idStudent,$idActivity,$idSession)";
        }else{
            $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Session WHERE idUser = $idStudent AND idActivity = $idActivity AND idSession = $idSession";
        }
        
        return $this->execute($query);
    }

    public function getMaxIdSession($idActivity) {
        $query = "SELECT MAX(idSession) AS 'Maximo' FROM {$GLOBALS['DB_NOMBRE']}.Session WHERE idActivity = $idActivity;";
        
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $fila['Maximo'];
        }
      
        return false; 
    }

}
