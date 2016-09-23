<?php

include_once (ROOT .'/Layers/Persistance/dao/Student_has_competencesDAO.php');


/**
 * Esta clase se utilizará para insertar, actualiza y obtener competencias
 * APROBADAS por un profesor a un estudiante en un curso y fecha determinadas.
 *
 * @author jorge
 */
class Student_has_compentecesMySQLDAO extends Student_has_competencesDAO{
     /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {   
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Competence";
        return $this->execute($query);       
    }

    /**
     * Elimina un elemento de la base de datos
     * @param int $idUser -> Identificador del usuario
    
     * 
     * @return boolean con el resultado de la ejecución de la orden, o -2 si el
     * número de argumentos no fueron 2
     */
    public function delete($idUser) {
       if(func_num_args() == 2 ){
           $idCompetencia = func_get_arg(1);       
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Competence WHERE idUser = {$idUser} AND idCompetence = {$idCompetencia} ";
        return $this->execute($query);
       }
       return -2;
    }

    /**
     * Convierte un objeto Student_has_Competence
     * @param String[] $row Array asociativo de Strings
     * @return Student_has_Competence
     */
    private function convertMySQLObjectCurso($row){
        
        $idStudent = $row['idUser'];
        $idCompetencia = $row['idCompetence'];       
        $fechaSuperacion = $row['datePassed'];  
        $idCurso = $row['idYear'];          
        $idProfessor = $row['idProfessor'];  

  
        return new Student_has_Competence($idCompetencia, $idStudent, $idProfessor, $fechaSuperacion, $idCurso);
        
    }
    
    /**
     * Obtiene un Student_has_Competence User desde la Base de Datos
     * @param int $idUser
     * @return Student_has_Competence o -2 si el número de argumentos introducidos
     * no es igual a 2
     */
    public function get($idUser) {
        if(func_num_args() != 2 ){
            return -2;
        }
        $idProfessor = func_get_arg(1);
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Competence WHERE idUser = {$idUser} AND idProfessor = {$idProfessor}";
       
  
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLObjectCurso($fila);
        }
      
        return false;
        
        
    }

    /**
     * Obtiene todos los elementos de la tabla de Cursos que cumplan
     * con la consulta especificada en el argumento
     * @param String $query consulta
     * @return Student_has_Competence[] or boolean(false) en caso de error
     */
    private function getAllByQuery($query){
        $this->execute($query);
       
        $listadoObjetosUsuarioMySQL = null;
        $listaSesiones = null; 
        if($this->connection->getNumRows() > 0){  
           
            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)){
                $listadoObjetosUsuarioMySQL[] = $fila;
                        
            }
            $size = count($listadoObjetosUsuarioMySQL);           
            
            for ($index = 0; $index < $size; $index++) {                
                $listaSesiones[$index] = $this->convertMySQLObjectCurso($listadoObjetosUsuarioMySQL[$index]);
            }
            
            
            return $listaSesiones;
        }
      
        return false;
    }
    /**
     * Obtiene todas las competencias aprobadas por estudiantes de la Base de Datos
     * @return Student_has_Competence[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Competence";        
        return $this->getAllByQuery($query);
        
    }

    /**
     * Obtiene todos los objetos Student_has_Competence de la base de datos ordenados en base
     * a los valores de una columna especificada en los argumentos
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return Student_has_Competence[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Competence ORDER BY $param ";
        if($order == 0){
            $query .="ASC";
        }
        else{
            $query .= "DESC";
        }
            
        return $this->getAllByQuery($query);
    }
    
 /**
  * Obtiene todas las competencias aprobadas por un estudiante
  * @param int $idStudent
  */
    public function getAllbyStudent($idStudent){
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Competence WHERE idUser = {$idStudent} ";
        return $this->getAllByQuery($query);
    }
    
 /**
  * Obtiene todas las competencias aprobadas por un profesor
  * @param int $idProfessor
  */
    public function getAllbyProfessor($idProfessor){
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Competence WHERE idProfessor = {$idProfessor} ";
        return $this->getAllByQuery($query);        
    }  
    
 /**
  * Obtiene todas las competencias que han sido superadas por estudiantes en un curso
  * @param int $idYear
  */    
    public function getAllByYear($idYear){
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Competence WHERE idYear = {$idYear} ";
        return $this->getAllByQuery($query);        
    }
    
    /**
     *  Inserta un objeto Student_has_Competence en la Base de Datos, retornando su identificador
     * @param Student_has_Competence $object
     * @return int
     */
    public function insert($object) {
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Student_has_Competence (idUser,idCompetence,datePassed,idYear,idProfessor) "
                . "VALUES ({$object->getIdEstudiante()},"
                . "{$object->getIdCompetencia()},"
                . "'{$object->getFechaSuperacion}',"
                . "{$object->getIdCurso()},"
                . "{$object->getIdProfessor()}')";
       
                 
            
        
        return $this->execute($query);
    }

    /**
     * Actualiza la tabla Student_has_Competence
     * @param Student_has_Competence $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.Student_has_Competence "
                . "SET datePassed = '{$object->getFechaSuperacion()}',"
                . "idYear = {$object->getIdCurso()},"
                . "idProfessor = {$object->getIdProfessor()}"
                . "WHERE idUser = {$object->getIdEstudiante()} AND idCompetence = {$object->getIdCompetencia()}";  

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
