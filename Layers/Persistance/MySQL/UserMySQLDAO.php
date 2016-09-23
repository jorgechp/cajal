<?php

require_once (ROOT .'/Layers/Persistance/dao/UserDAO.php');
/**
 * Description of UserMySQLDAO
 *
 * @author jorge
 */
class UserMySQLDAO extends UserDAO{
    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {   
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.User";
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

        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.User WHERE idUser = {$idUser}";
        return $this->execute($query);
  
        
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto Usuario
     * @param String[] $row Array asociativo de Strings
     * @return User
     */
    private function convertMySQLObjectCurso($row){
        
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
        $idAreaClinica = $row['idArea']; 
        $idProfessorCentre= $row['idProfessorCentre']; 
  
        return new User($idUser, $password, $name, $lastName1, $lastName2, $dni, $profile_avatar, $mail,$phone,$rol,$idAreaClinica,$idProfessorCentre);
        
    }
    
    /**
     * Obtiene un objeto User desde la Base de Datos
     * @param int $idUser
     * @return User
     */
    public function get($idUser) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.User WHERE idUser = {$idUser}";
      
  
        $res = $this->execute($query);
           
        if($res != false && $this->connection->getNumRows() > 0){  
           
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLObjectCurso($fila);
        }
      
        return false;
        
        
    }

    /**
     * Obtiene todos los elementos de la tabla de Cursos que cumplan
     * con la consulta especificada en el argumento
     * @param String $query consulta
     * @return User[] or boolean(false) en caso de error
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
     * Obtiene todos los usuarios de la Base de Datos
     * @return User[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.User";        
        return $this->getAllByQuery($query);
        
    }

    /**
     * Obtiene todos los objetos User de la base de datos ordenados en base
     * a los valores de una columna especificada en los argumentos
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return User[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.User ORDER BY $param ";
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
     * @param User $object
     * @return int
     */
    public function insert($object) {
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.User (name,lastName1,lastName2,dni,mail,password,profile_avatar,phone,idRol,idArea,idProfessorCentre) "
                . "VALUES ('{$object->getNombre()}',"
                . "'{$object->getApellido1()}',"
                . "'{$object->getApellido2()}',"
                . "'{$object->getDNI()}',"
                . "'{$object->getMail()}',"
                . "'{$object->getPassword()}',"
                . "'{$object->getImagenPerfil()}',"
                . "'{$object->getPhone()}',"
                . "'{$object->getRol()}',"
                . "'{$object->getIdArea()}',"
                . "'{$object->getIdCentro()}'"
                . ")";
       
         

        $res = $this->execute($query);
   
        return $res;       
      
    }

    /**
     * Actualiza la tabla de usuarios
     * @param User $object
     * @return boolean
     */
    public function update($object) {
        $query = null;
        if($object->getPassword() != null){ 
            $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.User "
                    . "SET name = '{$object->getNombre()}',"
                    . "lastName1 = '{$object->getApellido1()}',"
                    . "lastName2 = '{$object->getApellido2()}', "               
                    . "dni = '{$object->getDNI()}', " 
                    . "mail = '{$object->getMail()}', " 
                    . "password = '{$object->getPassword()}', " 
                    . "profile_avatar = '{$object->getImagenPerfil()}', " 
                    . "phone = '{$object->getPhone()}' , "             
                    . "idRol = '{$object->getRol()}', "
                    . "idArea = '{$object->getIdArea()}', "
                    . "idProfessorCentre = '{$object->getIdCentro()}' "
                    . "WHERE idUser = {$object->getIdUsuario()}";  
        }else{
             $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.User "
                    . "SET name = '{$object->getNombre()}',"
                    . "lastName1 = '{$object->getApellido1()}',"
                    . "lastName2 = '{$object->getApellido2()}', "               
                    . "dni = '{$object->getDNI()}', " 
                    . "mail = '{$object->getMail()}', "                     
                    . "profile_avatar = '{$object->getImagenPerfil()}', " 
                    . "phone = '{$object->getPhone()}' , "             
                    . "idRol = '{$object->getRol()}', "
                    . "idArea = '{$object->getIdArea()}', "
                    . "idProfessorCentre = '{$object->getIdCentro()}' "
                    . "WHERE idUser = {$object->getIdUsuario()}";             
        }
    
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
        $this->erroNo = $this->connection->getErrorNo();
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
     * Comprueba que el login se efectuó correctamente
     * @param int $idUsuario
     * @param String $password
     * @return boolean
     */
    public function checkLogin($idUsuario, $password) {
        $query = "SELECT COUNT(*) AS 'USERCOUNT' FROM {$GLOBALS['DB_NOMBRE']}.User WHERE idUser = {$idUsuario} AND password = '{$password}'";
        
        $this->execute($query);
        if($this->connection->getNumRows() > 0){  
            
           $fila = $this->connection->fetch_array(MYSQLI_ASSOC);            
           if($fila['USERCOUNT'] == 1){
               return true;
           }
           else{
               
               return false;
           }
               
        }
      
        return false;
        
    }

    /**
     * Obtiene el identificador en la Base de Datos de un usuario a partir
     * de su correo electrónico
     * @param String $mail
     * @return String
     */
    public function getIdUserFromMail($mail) {
        $query = "SELECT idUser FROM {$GLOBALS['DB_NOMBRE']}.User WHERE mail = '{$mail}';";
        $this->execute($query);
        if($this->connection->getNumRows() > 0){  
           $fila = $this->connection->fetch_array(MYSQLI_ASSOC);            
           return $fila['idUser'];
        }
        return false;
    }

    /**
     * Obtiene el identificador en la Base de Datos de un usuario a partir
     * de su identificador real (DNI/Pasaporte)
     * @param int $realId
     * @return String
     */
    public function getIdUserFromRealId($realId) {
        
        $query = "SELECT idUser FROM {$GLOBALS['DB_NOMBRE']}.User WHERE dni = '{$realId}';";
             
        if($this->execute($query)){              
           $fila = $this->connection->fetch_array(MYSQLI_ASSOC);            
           return $fila['idUser'];
        }
        
        return false;        
    }
    
    /**
     * Obtiene el hash de la contraseña del usuario
     * @param int $idUser
     * @return String
     */
    public function getPasswordHashFromUser($idUser){
        $query = "SELECT password FROM {$GLOBALS['DB_NOMBRE']}.User WHERE idUser = {$idUser};";
        if($this->execute($query)){              
           $fila = $this->connection->fetch_array(MYSQLI_ASSOC);            
           return $fila['password'];
        }
        
        return false;  
    }

    /**
     * Cambia el rol de un usuario
     * @param int $idUser
     * @param int $newRol
     */
    public function changeUserRol($idUser, $newRol) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.User SET idRol = {$newRol} WHERE idUser = {$idUser}";  
        return $this->execute($query);
    }

    public function isStudentChangeRol($idUser, $idRol) {
        $query = "";
        
        switch ($idRol) {
            case 2:
                $query = "SELECT COUNT(*) AS 'IS_ROL' FROM {$GLOBALS['DB_NOMBRE']}.Student WHERE idUser = {$idUser}";
                break;
            case 3:
                $query = "SELECT COUNT(*) AS 'IS_ROL' FROM {$GLOBALS['DB_NOMBRE']}.Professor WHERE idUser = {$idUser}";
                break;
            case 4:
                $query = "SELECT COUNT(*) AS 'IS_ROL' FROM {$GLOBALS['DB_NOMBRE']}.Admin WHERE idUser = {$idUser}";
                break;  
            default:                
                return false;
                break;
        }
          
        if($this->execute($query)){              
            
           $fila = $this->connection->fetch_array(MYSQLI_ASSOC);            
           if($fila['IS_ROL'] == 1){               
               return true;
           }
        }
        
        return false;    
        
    }

    public function getAllByArea($idArea) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.User WHERE idArea = {$idArea}";        
        return $this->getAllByQuery($query);
    }

    public function getAllByAreaAndCentre($idArea, $idCentre) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.User WHERE idArea = {$idArea} AND idProfessorCentre = {$idCentre}";      
        
        return $this->getAllByQuery($query);        
    }

    public function getAllByCentre($idCentre) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.User WHERE idProfessorCentre = {$idCentre}";        
        return $this->getAllByQuery($query);        
    }

}
