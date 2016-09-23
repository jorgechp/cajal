<?php

include_once (ROOT .'/Layers/Persistance/dao/UserDAO.php');
include_once (ROOT .'/Layers/Persistance/dao/ProfessorDAO.php');

/**
 * Description of ProfessorMySQLDAO
 *
 * @author jorge
 */
class ProfessorMySQLDAO extends ProfessorDAO {
    


    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {          
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.User WHERE idUser IN ("
        . "SELECT idUser FROM {$GLOBALS['DB_NOMBRE']}.Professor)";
       
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
                INNER JOIN {$GLOBALS['DB_NOMBRE']}.Professor  p 
                ON p.idUser=u.idUser
                WHERE p.idUser={$idUser}";
         
        return $this->execute($query);
  
        
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto Usuario
     * @param String[] $row Array asociativo de Strings
     * @return Professor
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
        $idRol = $row['idRol']; 
        $idAreaClinica = $row['idAreaClinica']; 
        $idProfessorCentre= $row['idCentro']; 
  
        return new Professor($idUser, $password, $name, $lastName1, $lastName2, $dni, $profile_avatar, $mail,$phone,$idRol,$idAreaClinica,$idProfessorCentre);
        
    }
    
    /**
     * Obtiene un objeto Professor desde la Base de Datos
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
     * @return Professor[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.User u JOIN {$GLOBALS['DB_NOMBRE']}.Professor p ON p.idUser = u.idUser";        
        return $this->getAllByQuery($query);
        
    }

    /**
     * Obtiene todos los objetos Professor de la base de datos ordenados en base
     * a los valores de una columna especificada en los argumentos
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return Professor[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.User u JOIN {$GLOBALS['DB_NOMBRE']}.Professor p ON p.idUser = u.idUser ORDER BY $param ";
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
     * @param Professor $object
     * @return int
     */
    public function insert($object, $isUserInserted = false) {
        if(!$isUserInserted){
            $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.User (name,lastName1,lastName2,dni,mail,password,profile_avatar,phone,idArea,idProfessorCentre) "
                    . "VALUES ('{$object->getNombre()}',"
                    . "'{$object->getApellido1()}',"
                    . "'{$object->getApellido2()}',"
                    . "{$object->getDNI()},"
                    . "'{$object->getMail()}',"
                    . "'{$object->getPassword()}',"
                    . "'{$object->getImagenPerfil()}',"
                    . "'{$object->getPhone()}',"
                    . "'{$object->getIdRol()}',"
                    . "'{$object->getIdArea()}',"
                    . "'{$object->getIdCentro()}',"
                    . ")";



            $idUsuario = $this->execute($query);
        }
        else{
            $idUsuario = $object->getIdUsuario();
        }
        if($idUsuario != FALSE){
            
            $queryS = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Professor (idUser,idtipoProfesor) VALUES ({$idUsuario},1)";
            if($this->execute($queryS)){
            
                return $idUsuario;
            }
        }
                
    }

    /**
     * Actualiza la tabla de usuarios
     * @param Professor $object
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
                . "phone = '{$object->getPhone()}', "             
                . "idRol = '{$object->getIdRol()}', "
                . "idArea = '{$object->getIdArea()}', "
                . "idProfessorCentre = '{$object->getIdCentro()}' "
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

    
    public function getProfessorsByActivity($idActivity, $idYear) {
        $query = "SELECT u.idUser AS 'idUser',"
                . " u.name AS 'name',"
                . " u.lastName1 AS 'lastName1',"
                . " u.lastName2 AS 'lastName2',"
                . " u.dni AS 'dni', u.mail AS 'mail',"
                . " u.password AS 'password',"
                . " u.profile_avatar AS 'profile_avatar',"
                . "  u.phone AS 'phone',"
                . " u.idRol AS 'idRol' "
        . "FROM {$GLOBALS['DB_NOMBRE']}.User u JOIN {$GLOBALS['DB_NOMBRE']}.Professor_has_Activity pha "
        . "ON pha.idProfessor = u.idUser "
        . "WHERE pha.idActivity = {$idActivity} AND pha.idYear = {$idYear};";
       
        return $this->getAllByQuery($query);
    }

    public function getProfessorsStartsWith($text, $idActivity = null) {
        $query = "";
        if($idActivity == null){
            $query = "SELECT u.idUser AS 'idUser',"
                . " u.name AS 'name',"
                . " u.lastName1 AS 'lastName1',"
                . " u.lastName2 AS 'lastName2',"
                . " u.dni AS 'dni', u.mail AS 'mail',"
                . " u.password AS 'password',"
                . " u.profile_avatar AS 'profile_avatar',"
                . "  u.phone AS 'phone',"
                . " u.idRol AS 'idRol' FROM {$GLOBALS['DB_NOMBRE']}.User u "
            . "JOIN {$GLOBALS['DB_NOMBRE']}.Professor p "
            . "ON p.idUser = u.idUser "
            . "WHERE name LIKE '{$text}%'";
        }else{            
            $query = "SELECT "
            . "u.idUser AS 'idUser',"
                    . " u.name AS 'name',"
                    . " u.lastName1 AS 'lastName1',"
                    . " u.lastName2 AS 'lastName2',"
                    . " u.dni AS 'dni', u.mail AS 'mail',"
                    . " u.password AS 'password',"
                    . " u.profile_avatar AS 'profile_avatar',"
                    . " u.phone AS 'phone',"
                    . " u.idRol AS 'idRol'"
                    . " FROM {$GLOBALS['DB_NOMBRE']}.User u "
                    . "JOIN {$GLOBALS['DB_NOMBRE']}.Professor p "
                    . "ON p.idUser = u.idUser "
                    . "WHERE u.idUser "
                    . "NOT IN (SELECT idProfessor "
                            . "FROM {$GLOBALS['DB_NOMBRE']}.Professor_has_Activity "
                            . "WHERE idActivity = {$idActivity}) "
                            . "AND u.dni LIKE '{$text}%';";

        }
        
        return $this->getAllByQuery($query);
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
