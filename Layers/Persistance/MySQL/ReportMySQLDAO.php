<?php

require (ROOT .'/Layers/Persistance/dao/ReportDAO.php');
require_once  (ROOT .'/objects/Competence.php');
/**
 * Description of ReportMySQLDAO
 *
 * @author jorge
 */
class ReportMySQLDAO extends ReportDAO{
      
    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {   
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Report";
        return $this->execute($query);       
    }

    /**
     * Elimina un elemento de la base de datos
     * @param int $id -> Identificador del Report
     * 
     */
    public function delete($id) {
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Report WHERE idReport = ".$id;
        
        return $this->execute($query);
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto Report
     * @param String[] $row Array asociativo de Strings
     * @return Report
     */
    private function convertMySQLObjectReport($row){
        
        $id = $row['idReport']; 
        $status = $row['status'];  
        $subject = $row['subject'];
        $text = $row['text'];
        $date = $row['date'];
        $idSender = $row['idSender'];
        $priority = $row['priority'];
        $isAnError = $row['isAnError'];

                    
           
            
            $report = new Report(                   
                    $status,
                    $subject,
                    $text,                 
                    $date,
                    $idSender,
                    $priority,  
                    $isAnError
             );
        $report->setIdReport($id);
        return $report;
        
        
    }
    
    /**
     * Obtiene un objeto Competencia desde la Base de Datos
     * @param int $id
     * @return Competencia
     */
    public function get($id) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Report WHERE idReport = {$id}";  
     
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLObjectReport($fila);
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
                $listaCompetencias[$index] = $this->convertMySQLObjectReport($listaObjetosMySQL[$index]);
            }
            
            
            return $listaCompetencias;
        }
      
        return false;
    }

    /**
     * Obtiene todos los Report de la Base de Datos
     * @return Competence[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Report";        
        return $this->getAllPrivate($query);
        
    }

    /**
     * Obtiene todos los objetos Report de la base de datos ordenados en base
     * a los valores de una columna
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return Report[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Report ORDER BY $param ";
        if($order == 0){
            $query .="ASC";
        }
        else{
            $query .= "DESC";
        }
            
        $this->execute($query);
       
        $listaObjetosMySQL = null;
        $listaReports = null; 
        if($this->connection->getNumRows() > 0){  
           
            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)){
                $listaObjetosMySQL[] = $fila;
                        
            }
            $size = count($listaObjetosMySQL);           
            
            for ($index = 0; $index < $size; $index++) {                
                $listaReports[$index] = $this->convertMySQLObjectReport($listaObjetosMySQL[$index]);
            }
            
            
            return $listaReports;
        }
      
        return false;
    }
    


    
    /**
     *  Inserta un objeto Report en la Base de Datos, retornando su identificador
     * @param Report $object
     * @return int
     */
    public function insert($object) {      
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Report (status,subject, text, date, idSender, priority, isAnError) "
                . "VALUES ('{$object->getStatus()}',"
                . "'{$object->getSubject()}',"
                . "'{$object->getText()}',"
                . "'{$object->getDate()}',"  
                . "{$object->getIdSender()},"  
                . "{$object->getPriority()}," 
                . "{$object->getIsAnError()})" ;   
              

       
              
        return $this->execute($query);
    }

    /**
     * Actualiza la tabla de Reportes
     * @param Report $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.Report "
                . "SET idReport = '{$object->getIdReport()}',"
                . "status = '{$object->getStatus()}',"
                . "subject = '{$object->getSubject()}' "
                . "text = '{$object->getText()}' "
                . "date = {$object->getDate()} "
                . "idSender = {$object->getIdSender()} "
                . "priority = {$object->getPriority()} "
                . "isAnError = {$object->getIsAnError()} "
                . "WHERE idReport = {$object->getIdReport()}";  
          
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
     * Obtiene todos los elementos de la tabla de de tipos de reportes que cumplan
     * con la consulta especificada en el argumento
     * @param String $query consulta
     * @return Report[] or boolean(false) en caso de error
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
                $listaSesiones[$index] = $this->convertMySQLObjectReport($listaObjetosMySQL[$index]);
            }
            
            
            return $listaSesiones;
        }
      
        return false;
    }

    /**
     * Marca como resuelto una notificación
     * @param int $idReport
     */
    public function checkSolved($idReport,$unsolved = -1) {
        $query ="";
        
        if($unsolved == -1){
            $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.Report SET status = 1 WHERE idReport = {$idReport}";
        }else{
            $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.Report SET status = 0 WHERE idReport = {$idReport}";
        }

        return $this->execute($query);
    }

}
