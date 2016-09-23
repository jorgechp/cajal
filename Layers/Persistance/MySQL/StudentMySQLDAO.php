<?php

include_once (ROOT .'/Layers/Persistance/dao/UserDAO.php');
include_once (ROOT .'/Layers/Persistance/dao/StudentDAO.php');

/**
 * Description of StudentMySQLDAO
 *
 * @author jorge
 */
class StudentMySQLDAO extends StudentDAO {
    


    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {          
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.User WHERE idUser IN ("
        . "SELECT idUser FROM {$GLOBALS['DB_NOMBRE']}.Student)";
       
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
       $query = "DELETE u FROM {$GLOBALS['DB_NOMBRE']}.User u 
                INNER JOIN {$GLOBALS['DB_NOMBRE']}.Student  s 
                ON s.idUser=u.idUser
                WHERE s.idUser={$idUser}";
         
        return $this->execute($query);
  
        
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto Usuario
     * @param String[] $row Array asociativo de Strings
     * @return Student
     */
    private function convertMySQLObjectUser($row){
        
        $idUser = $row['idUser'];
        $name = $row['name'];
        $lastName1 = $row['lastName1'];  
        $lastName2 = $row['lastName2'];          
        $dni = $row['dni'];  
        $mail = $row['mail'];  
        $password = $row['password'];  
        $profile_avatar = $row['profile_avatar'];  
        $phone = $row['phone'];  
        $rol = $row['idRol']; 
        $idArea = $row['idArea'];
        $idCentro = $row['idProfessorCentre']; 
        
        return new Student($idUser, $password, $name, $lastName1, $lastName2, $dni, $profile_avatar, $mail,$phone,$rol,$idArea,$idCentro);
        
    }
    
    /**
     * Obtiene un objeto Student desde la Base de Datos
     * @param int $idUser
     * @return Student
     */
    public function get($idUser) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.User WHERE idUser = {$idUser}";
       
  
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLObjectUser($fila);
        }
      
        return false;
        
        
    }

    /**
     * Obtiene todos los elementos de la tabla de Cursos que cumplan
     * con la consulta especificada en el argumento
     * @param String $query consulta
     * @return Student[] or boolean(false) en caso de error
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
                $listaSesiones[$index] = $this->convertMySQLObjectUser($listadoObjetosUsuarioMySQL[$index]);
            }
            
            
            return $listaSesiones;
        }
      
        return false;
    }
    /**
     * Obtiene todos los usuarios de la Base de Datos
     * @return Student[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.User u JOIN {$GLOBALS['DB_NOMBRE']}.Student s ON s.idUser = u.idUser";        
        return $this->getAllByQuery($query);
        
    }

    /**
     * Obtiene todos los objetos Student de la base de datos ordenados en base
     * a los valores de una columna especificada en los argumentos
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return Student[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.User u JOIN {$GLOBALS['DB_NOMBRE']}.Student s ON s.idUser = u.idUser ORDER BY $param ";
        if($order == 0){
            $query .="ASC";
        }
        else{
            $query .= "DESC";
        }
            
        return $this->getAllByQuery($query);
    }

    /**
     *  Inserta un objeto User en la Base de Datos, retornando su identificador
     * @param Student $object
     * @return int
     */
    public function insert($object, $isUserInserted = false) {
        
        if(!$isUserInserted){
            $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.User (name,lastName1,lastName2,dni,mail,password,profile_avatar,phone, idRol) "
                    . "VALUES ('{$object->getNombre()}',"
                    . "'{$object->getApellido1()}',"
                    . "'{$object->getApellido2()}',"
                    . "{$object->getDNI()},"
                    . "'{$object->getMail()}',"
                    . "'{$object->getPassword()}',"
                    . "'{$object->getImagenPerfil()}',"
                    . "'{$object->getPhone()}',"
                    . "'{$object->getIdRol()}')";


            
            $idUsuario = $this->execute($query);
        }
        else{
            
            $idUsuario = $object->getIdUsuario();
         
        }        
        if($idUsuario != FALSE){
            
            $queryS = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Student (idUser) VALUES ({$idUsuario})";           


            
            if($this->execute($queryS)){
            
                return $idUsuario;
            }
        }
                
    }

    /**
     * Actualiza la tabla de usuarios
     * @param Student $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.User "
                . "SET name = '{$object->getNombre()}',"
                . "lastName1 = '{$object->getApellido1()}',"
                . "lastName2 = '{$object->getApellido2()}', "               
                . "dni = '{$object->getDNI()}', " 
                . "mail = '{$object->getMail()}', " 
                . "password = '{$object->getPassword()}', " 
                . "profile_avatar = '{$object->getImagenPerfil()}', " 
                . "phone = '{$object->getPhone()}' "             
                . "idRol = '{$object->getidRol()}' "  
                . "WHERE idUser = {$object->getIdUsuario()}";  

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
    


    public function checkLogin($idUsuario, $password){
        return parent::checkLogin($idUsuario, $password);
    }

    public function getIdUserFromMail($mail) {
        return parent::getIdUserFromMail($mail);
    }

    public function getIdUserFromRealId($realId) {
        return parent::getIdUserFromRealId($realId);
    }
    
    public function getPasswordHashFromUser($idUser){
        return parent::getPasswordHashFromUser($idUser);
    }

    public function changeUserRol($idUser, $newRol) {
        return parent::changeUserRol($idUser, $newRol);
    }

    public function isStudentChangeRol($idUser, $idRol) {
        return parent::isStudentChangeRol($idUser, $idRol);
    }

    public function getAllByArea($idArea) {
        return parent::getAllByArea($idArea);
    }

    public function getAllByAreaAndCentre($idArea, $idCentre) {
        return parent::getAllByAreaAndCentre($idArea, $idCentre);
    }

    public function getAllByCentre($idCentre) {
        return parent::getAllByCentre($idCentre);
    }

}
