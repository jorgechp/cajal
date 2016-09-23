<?php

require (ROOT .'/Layers/Persistance/dao/EvaluacionDAO.php');
require_once (ROOT .'/Interfaces/dao/IevaluacionDAO.php');
require_once  (ROOT .'/objects/Evaluacion.php');

/**
 * Description of EvaluacionMySQLDAO
 *
 * @author jorge
 */
class EvaluacionMySQLDAO extends EvaluacionDAO{
    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {   
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Evaluacion";
        return $this->execute($query);       
    }

    /**
     * Elimina un elemento de la base de datos
     * @param int $date
     * @param int $idStudent
     * @param int $idActivity
     * @param int $idSession
     * @param int $idCompetence
     * @param int $idIndicator
     * @param int $idProfessor
     * @param int $idYear
     * 
     */
    public function delete($date) {
        if(func_num_args() != 8){
            return -1;
        }
        $idStudent = func_get_arg(2);
        $idActivity = func_get_arg(3);
        $idSession = func_get_arg(4);
        $idCompetence= func_get_arg(5);
        $idIndicator = func_get_arg(6);
        $idProfessor = func_get_arg(7);
        $idYear = func_get_arg(8);
        
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Evaluacion WHERE "
        . "idActivity = $idActivity"
        . " ,date = '$date'"
        . " ,idStudent = $idStudent"
        . " ,idSession = $idSession"
        . " ,idCompetence = $idCompetence"
        . " ,idActivity = $idSession"
        . " ,idIndicator = $idIndicator"
        . " ,idProfessor = $idProfessor"
        . " ,idYear = $idYear";   
        
        return $this->execute($query);
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto Actividad
     * @param String[] $row Array asociativo de Strings
     * @return Activity
     */
    private function convertMySQLObjectEvaluacion($row){
        
        $date = $row['date'];
        $idStudent = $row['idStudent'];
        $idActivity = $row['idActivity'];
        $idSession = $row['idSession'];
        $idCompetence= $row['idCompetence'];
        $idIndicator = $row['idIndicator'];
        $idProfessor = $row['idProfessor'];
        $evaluacion = $row['evaluacion'];
        $comment = $row['comment'];        
        $idYear = $row['idYear'];                    

        
       return new Evaluacion($date, $evaluacion, $comment, $idStudent, $idActivity, $idSession, $idCompetence, $idIndicator, $idProfessor,$idYear);
        
       
        
        
    }
    
    /**
     *  Obtiene un objeto Evaluacion desde la Base de Datos
     * @param int $date
     * @param int $idStudent
     * @param int $idActivity
     * @param int $idSession
     * @param int $idCompetence
     * @param int $idIndicator
     * @param int $idProfessor
     * @return Evaluation
     */
    public function get($date) {
        if(func_num_args() != 8){
            return -1;
        }
        $idStudent = func_get_arg(2);
        $idActivity = func_get_arg(3);
        $idSession = func_get_arg(4);
        $idCompetence= func_get_arg(5);
        $idIndicator = func_get_arg(6);
        $idProfessor = func_get_arg(7);
        $idYear = func_get_arg(8);
        
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Evaluacion WHERE "
        . "idActivity = $idActivity"
        . " ,date = '$date'"
        . " ,idStudent = $idStudent"
        . " ,idSession = $idSession"
        . " ,idCompetence = $idCompetence"
        . " ,idActivity = $idSession"
        . " ,idIndicator = $idIndicator"
        . " ,idProfessor = $idProfessor"     
        . " ,idYear = $idYear";  
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLObjectActivity($fila);
        }
      
        return false;
        
        
    }
    
    /**
     * Obtiene todos los elementos de la tabla de Cursos que cumplan
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
                $listaSesiones[$index] = $this->convertMySQLObjectEvaluacion($listaObjetosMySQL[$index]);
            }
            
            
            return $listaSesiones;
        }
      
        return false;
    }
    
    /**
     * Obtiene todas las evaluaciones de la Base de Datos
     * CUIDADO El número de tuplas puede ser muy elevado
     * @return Activity[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Evaluacion";        
        $this->execute($query);
       
        $listaObjetosMySQL = null;
        $listaEvaluaciones = null; 
        if($this->connection->getNumRows() > 0){  
           
            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)){
                $listaObjetosMySQL[] = $fila;
                        
            }
            $size = count($listaObjetosMySQL);           
            
            for ($index = 0; $index < $size; $index++) {                
                $listaEvaluaciones[$index] = $this->convertMySQLObjectEvaluacion($listaObjetosMySQL[$index]);
            }
            
            
            return $listaEvaluaciones;
        }
      
        return false;
        
    }

    /**
     * Obtiene todos los objetos Evaluacion de la base de datos ordenados en base
     * a los valores de una columna
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return Evaluacion[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Evaluacion ORDER BY $param ";
        if($order == 0){
            $query .="ASC";
        }
        else{
            $query .= "DESC";
        }
            
        $this->execute($query);
       
        $listaObjetosMySQL = null;
        $listaEvaluaciones = null; 
        if($this->connection->getNumRows() > 0){  
           
            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)){
                $listaObjetosMySQL[] = $fila;
                        
            }
            $size = count($listaObjetosMySQL);           
            
            for ($index = 0; $index < $size; $index++) {                
                $listaEvaluaciones[$index] = $this->convertMySQLObjectEvaluacion($listaObjetosMySQL[$index]);
            }
            
            
            return $listaEvaluaciones;
        }
      
        return false;
    }

    /**
     *  Inserta un objeto Evaluacion en la Base de Datos, retornando su identificador
     * @param Evaluacion $object
     * @return int
     */
    public function insert($object) {
        $date = null;
        $comment = null;
        if(null != $object->getDate()){
            $date = $object->getDate();
        }else{
            $date = 'CURRENT_TIMESTAMP';
        }
        
        if(null != $object->getComment()){
            $comment = $object->getComment();
        }else{
            $comment = 'NULL';
        }
        
        
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Evaluacion (date,evaluacion,comment, "
        .          "idStudent,idActivity, idSession, "
                . "idCompetence,idIndicator,"
                . "idProfessor, idYear) "
                . "VALUES ({$date},"
                . "{$object->getEvaluacion()},"
                . "'{$object->getComment()}',"                
                . "{$object->getIdStudent()},"
                . "{$object->getIdActivity()},"
                . "{$object->getIdSession()}," 
                . "{$object->getIdCompetence()},"
                . "{$object->getIdIndicator()},"
                . "{$object->getIdProfessor()},"
                . "{$object->getIdYear()}"
                . ")"; 
             
               
        return $this->execute($query);
    }

    /**
     * Actualiza la tabla de evaluaciones
     * @param Evaluacion $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.Evaluacion "
                . "SET date = '{$object->getDate()}',"
                . " ,evaluacion = {$object->getEvaluacion()},"
                . " ,comment = '{$object->getComment()}' "
                . " ,idStudent = {$object->getIdStudent()} "
                . " ,idActivity = {$object->getIdActivity()} "
                . " ,idSession = {$object->getIdSession()} "
                . " ,idCompetence = {$object->getIdCompetence()} "
                . " ,idIndicator = {$object->getIdIndicator()} "
                . " ,idProfessor = {$object->getIdProfessor()} "
                . " ,idYear = {$object->getIdYear()} "
                . " WHERE "
                    . "idActivity = {$object->getIdActivity()}"
                    . " ,date = '{$object->getDate()}'"
                    . " ,idStudent = {$object->getIdStudent()}"
                    . " ,idSession = {$object->getIdSession()}"
                    . " ,idCompetence = {$object->getIdCompetence()}"
                    . " ,idActivity = {$object->getIdActivity()}"
                    . " ,idIndicator = {$object->getIdIndicator()}"
                    . " ,idYear = {$object->getIdYear()} "
                    . " ,idProfessor = {$object->getIdProfessor()}"; 
          
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
     * Obtiene el histórico de competencias de un indicador para un estudiante
     * @param int $idCompetencia
     * @param int $idIndicator
     * @param int $idStudent
     * @param int $idYear
     * @return Object[]
     */
    public function getEvaluationByIndicator($idCompetencia, $idIndicator, $idStudent, $idSession = null, $idYear = null,  $idActivity = -1) {
        $query = "";
        if(!is_null($idYear)){
            $query = "SELECT  * FROM {$GLOBALS['DB_NOMBRE']}.Evaluacion "
            . "WHERE idCompetence = $idCompetencia AND idIndicator = $idIndicator AND idStudent = $idStudent AND idYear = $idYear";
        }
        else{
            $query = "SELECT  * FROM {$GLOBALS['DB_NOMBRE']}.Evaluacion "
            . "WHERE idCompetence = $idCompetencia AND idIndicator = $idIndicator AND idStudent = $idStudent";            
        }
        
        if(!is_null($idSession)){
             $query .= " AND idSession = {$idSession}";
        }
        
        if($idActivity != -1){
            $query .= " AND idActivity = {$idActivity}";
        }
        
        $query .= " ORDER BY date DESC;";
        
       
        return $this->getAllByQuery($query);
    }

    public function getLastEvaluationByIndicator($idCompetencia, $idIndicator, $idStudent, $idYear, $idActivity = -1) {
        $query = "SELECT e.date, e.evaluacion, e.comment, e.idStudent, e.idActivity, e.idSession, e.idCompetence, e.idIndicator, e.idProfessor,e.idYear            
        FROM (
                SELECT MAX(date) as maxdate, evaluacion, comment, idStudent, idActivity, idSession, idCompetence, idIndicator, idProfessor,idYear
                FROM pCompetencias.Evaluacion group by idSession, idIndicator, idCompetence, idYear, idStudent

            ) as x inner join pCompetencias.Evaluacion as e on e.idYear = x.idYear 
            AND e.idProfessor = x.idProfessor 
            AND e.idCompetence = x.idCompetence 
            AND e.idIndicator = x.idIndicator 
            AND e.idActivity = x.idActivity 
            AND e.idStudent = x.idStudent 
            AND e.idSession = x.idSession 
            AND e.idYear = x.idYear 
            AND e.date = x.maxdate ";
       
        if($idActivity != -1){
            $query .= "AND e.idActivity = {$idActivity} ";
        }
        
        $query .= "AND e.idCompetence = {$idCompetencia} AND e.idYear = {$idYear}  AND e.idStudent = {$idStudent} AND e.idIndicator = {$idIndicator} ORDER BY date ASC;";
        
         
        return $this->getAllByQuery($query);
    }

    public function getMaxLastEvaluationByIndicator($idCompetencia, $idIndicator, $idStudent, $idYear, $idActivity = -1) {
        $query = "SELECT MAX(evaluacion) as 'maxEval' FROM (";
        $query .= "SELECT e.evaluacion            
        FROM (
                SELECT MAX(date) as maxdate, evaluacion, comment, idStudent, idActivity, idSession, idCompetence, idIndicator, idProfessor,idYear
                FROM pCompetencias.Evaluacion group by idSession, idIndicator, idCompetence, idYear, idStudent

            ) as x inner join pCompetencias.Evaluacion as e on e.idYear = x.idYear 
            AND e.idProfessor = x.idProfessor 
            AND e.idCompetence = x.idCompetence 
            AND e.idIndicator = x.idIndicator 
            AND e.idActivity = x.idActivity 
            AND e.idStudent = x.idStudent 
            AND e.idSession = x.idSession 
            AND e.idYear = x.idYear 
            AND e.date = x.maxdate ";
        
                if($idActivity != -1){
            $query .= "AND e.idActivity = {$idActivity} ";
        }
        
        $query .= "AND e.idCompetence = {$idCompetencia} AND e.idYear = {$idYear}  AND e.idStudent = {$idStudent} AND e.idIndicator = {$idIndicator} ORDER BY date DESC";
        $query .= " LIMIT {$GLOBALS["GENERAL_NUMBER_OF_EVALUATIONS"]}) x;";
        
        $this->execute($query);
       if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
             if(isset($fila['maxEval'])){
                return $fila['maxEval'];
            }else{
                return 0;
            }
        }
    }

    /**
     * Obtiene la media de las últimas evaluaciones
     * Nota, las evaluaciones que se tienen en cuenta se definen en la 
     * variable global de configuración {$GLOBALS["GENERAL_NUMBER_OF_EVALUATIONS"]}
     * 
     * Si $strict == false y el estudiante no tiene 5 evaluaciones, se hace la media
     * sobre las evaluaciones que tenga actualmente. 
     * Si $strict == true, y el estudiante no tiene 5 evaluaciones, las evaluaciones
     * faltantes se calificarán con un 0 a la hora hacer la media.
     * 
     * @param int $idCompetencia
     * @param int $idIndicator
     * @param int $idStudent
     * @param int $idYear
     * @param int $idActivity
     * @param boolean $strict
     * @return int
     */
    public function getMeanLastEvaluationByIndicator($idCompetencia, $idIndicator, $idStudent, $idYear, $idActivity = -1, $strict = false) {
        $query = null;
        $year = "";
        if($strict){
           $query = "SELECT SUM(evaluacion)/{$GLOBALS["GENERAL_NUMBER_OF_EVALUATIONS"]} as 'avgEval' FROM ("; 
        }else{
            $query = "SELECT AVG(evaluacion) as 'avgEval' FROM (";
        }
        
        $query .= "SELECT e.evaluacion            
        FROM (
                SELECT MAX(date) as maxdate, evaluacion, comment, idStudent, idActivity, idSession, idCompetence, idIndicator, idProfessor,idYear
                FROM pCompetencias.Evaluacion group by idSession, idIndicator, idCompetence, idYear, idStudent

            ) as x inner join pCompetencias.Evaluacion as e on e.idYear = x.idYear 
            AND e.idProfessor = x.idProfessor 
            AND e.idCompetence = x.idCompetence 
            AND e.idIndicator = x.idIndicator 
            AND e.idActivity = x.idActivity 
            AND e.idStudent = x.idStudent 
            AND e.idSession = x.idSession 
            AND e.idYear = x.idYear 
            AND e.date = x.maxdate ";
        
                if($idActivity != -1){
            $query .= "AND e.idActivity = {$idActivity} ";
        }
        
        if($idYear != null){
             $year = "AND e.idYear = {$idYear}";
        }        
       
        $query .= "AND e.idCompetence = {$idCompetencia} ".$year." AND e.idStudent = {$idStudent} AND e.idIndicator = {$idIndicator} ORDER BY date DESC";
        $query .= " LIMIT {$GLOBALS["GENERAL_NUMBER_OF_EVALUATIONS"]}) x;";
        
        $this->execute($query);
       
       if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            if(isset($fila['avgEval'])){
                return $fila['avgEval'];
            }else{
               
                return 0;
            }
            
        }
    }

    public function getLastPlaceEvaluatedOnIndicator($idCompetencia, $idIndicator, $idStudent) {
        $query = "SELECT ses.idLugar FROM Evaluacion ev JOIN Session ses "
                . "ON ses.idSession = ev.idSession "
                . "AND ses.idActivity = ev.idActivity "
                . "WHERE ev.idCompetence = $idCompetencia "        
                . "AND ev.idIndicator = $idIndicator "                            
                . "AND ev.idStudent = $idStudent "
                . "ORDER BY ev.date DESC LIMIT 1;";
              
       
  
        $this->execute($query);
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            if(isset($fila['idLugar'])){
                return $fila['idLugar'];
            }else{
               
                return 0;
            }
            
        }  
    }

}
