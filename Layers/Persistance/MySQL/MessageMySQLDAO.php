<?php
require_once (ROOT .'/Layers/Persistance/dao/MessageDAO.php');
/**
 * Description of MessageMySQLDAO
 *
 * @author jorge
 */
class MessageMySQLDAO extends MessageDAO{
    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {   
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.MESSAGES";
        return $this->execute($query);       
    }

    /**
     * Elimina un elemento de la base de datos
     * @param int $idMessage -> Identificador del mensaje
    
     * 
     * @return boolean con el resultado de la ejecución de la orden, o -2 si el
     * número de argumentos no fueron 2
     */
    public function delete($idMessage) {
        if(func_num_args() == 2){
            $idUser = func_get_arg(1);
            $query = "CALL {$GLOBALS['DB_NOMBRE']}.DELETE_MESSAGE({$idMessage},{$idUser})"; 
            return $this->execute($query);
//            $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.MESSAGES_has_recipients WHERE idMessage = {$idMessage} AND idRecipient = {$idUser}"; 
//            
//            if($this->execute($query) != false){
//                $query2 = 'SELECT COUNT(*) FROM {$GLOBALS['DB_NOMBRE']}.MESSAGES_has_recipients'
//            }
//            else{
//                return false;
//            }
        }
        return false;
  
        
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto Message
     * @param String[] $row Array asociativo de Strings
     * @return Message
     */
    private function convertMySQLObjectCurso($row){
        $idMessage = $row['idMessage'];
        $IdSender = $row['Idsender'];
        $subject = $row['subject'];  
        $message = $row['message'];          
        $date = $row['date'];  
        $idMessageReplied = $row['idMessageReplied'];  
    
  
        return new Message($idMessage, $IdSender, $subject, $message, $date, $idMessageReplied);
        
    }
    
    /**
     * Obtiene un objeto Message desde la Base de Datos
     * @param int $idMessage
     * @return Message
     */
    public function get($idMessage) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.MESSAGES WHERE idMessage = {$idMessage}";
       
  
        $this->execute($query);
       
        if($this->connection->getNumRows() > 0){  
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
            
            return $this->convertMySQLObjectCurso($fila);
        }
      
        return false;
        
        
    }

    /**
     * Obtiene todos los elementos de la tabla de Mensajes que cumplan
     * con la consulta especificada en el argumento
     * @param String $query consulta
     * @return Message[] or boolean(false) en caso de error
     */
    private function getAllByQuery($query){
        $this->execute($query);
       
        $listadoObjetosMensajeMySQL = null;
        $listaMensajes = null; 
        if($this->connection->getNumRows() > 0){  
           
            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)){
                $listadoObjetosMensajeMySQL[] = $fila;
                        
            }
            $size = count($listadoObjetosMensajeMySQL);           
            
            for ($index = 0; $index < $size; $index++) {                
                $listaMensajes[$index] = $this->convertMySQLObjectCurso($listadoObjetosMensajeMySQL[$index]);
            }
            
            
            return $listaMensajes;
        }
      
        return false;
    }
    
    /**
     * Obtiene todos los mensajes de la Base de Datos
     * @return Message[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.MESSAGES";        
        return $this->getAllByQuery($query);
        
    }

    /**
     * Obtiene todos los objetos Message de la base de datos ordenados en base
     * a los valores de una columna especificada en los argumentos
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return Message[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.MESSAGES ORDER BY $param ";
        if($order == 0){
            $query .="ASC";
        }
        else{
            $query .= "DESC";
        }
            
        return $this->getAllByQuery($query);
    }

    /**
     *  Inserta un objeto Message en la Base de Datos, retornando su identificador
     * @param Message $object
     * @return int
     */
    public function insert($object) {
        $idMessage = $object->getIdMessage();
        if(is_null($object->getIdMessage())){
            $idMessage = 'NULL';
        }
        $date = $object->getDate();
        if(is_null($object->getDate())){
            $date = 'CURRENT_TIMESTAMP()';
        }    
        $idReplied = $object->getIdMessageReplied();
        if(is_null($idReplied)){
            $idReplied = 'NULL';
        }           
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.MESSAGES (idMessage,IdSender,subject,message,date,idMessageReplied) "
                . "VALUES ({$idMessage},"
                . "{$object->getIdSender()},"
                . "'{$object->getSubject()}',"
                . "'{$object->getMessage()}',"
                . "'{$date}',"
                . "{$idReplied})";
            
        
       
                 
            
        
        return $this->execute($query);
    }

    /**
     * Actualiza la tabla de mensajes
     * @param Message $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.MESSAGES "
                . "SET idMessage = {$object->getIdMessage()},"
                . "IdSender = {$object->getIdSender()},"
                . "subject = '{$object->getSubject()}', "               
                . "message = '{$object->getIdMessage()}', " 
                . "date = '{$object->getDate()}', " 
                . "idMessageReplied = {$object->getIdMessageReplied()} " ;

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
     * Obtiene todos los mensajes que han sido enviados por el usuario
     * @param integer $idUser
     * @return Mensaje[]
     */
    public function getAllMessagesFromUser($idUser) {
  
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.MESSAGES WHERE Idsender = $idUser";

        
        return $this->getAllByQuery($query);
    }

    /**
     * Obtiene todos los mensajes que han sido enviados al usuario
     * @param int $idUser
     * @return Mensaje[]
     */
    public function getAllMessagesToUser($idUser) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.MESSAGES me JOIN {$GLOBALS['DB_NOMBRE']}.MESSAGES_has_Recipients mhr ON mhr.idMessage = me.idMessage"
        . " WHERE mhr.idRecipient = $idUser"
        . " AND mhr.isHidden = 0 ORDER BY me.date DESC"; 
      
        
        return $this->getAllByQuery($query);
        
    }

    /**
     * Obtiene el número de mensajes no leídos del usuario
     * @param integer $idUser
     * @return integer
     */
    public function getNumberOfMessagesNotReadByUser($idUser) {
        $query = "SELECT COUNT(*) AS 'NUMBEROFMESSAGES' FROM {$GLOBALS['DB_NOMBRE']}.VIEW_MESSAGES WHERE idRecipient = $idUser AND dateRead IS NULL;";
   
        $this->execute($query);
        if($this->connection->getNumRows() > 0){  
            
           $fila = $this->connection->fetch_array(MYSQLI_ASSOC);            
           return $fila['NUMBEROFMESSAGES'];
        }
      
        return false;
    }

    /**
     * Comprueba si el mensaje especificado es propiedad del usuario. Es decir,
     * si el usuario tiene permisos para acceder y/o eliminar el mensaje
     * @param int $idMessage
     * @param int $idUser
     * @return boolean
     */
    public function isMessagePropertyOfUser($idMessage, $idUser) {        
        $query = "SELECT COUNT(*) AS 'ISTRUE' "
                . "FROM {$GLOBALS['DB_NOMBRE']}.MESSAGES_has_Recipients mhr JOIN {$GLOBALS['DB_NOMBRE']}.MESSAGES m "
                . "on m.idMessage = mhr.idMessage "
                . "WHERE mhr.idMessage = {$idMessage} AND (mhr.idRecipient = {$idUser} OR m.Idsender = {$idUser});";
       
        $this->execute($query);
        if($this->connection->getNumRows() > 0){  
            
           $fila = $this->connection->fetch_array(MYSQLI_ASSOC);            
           if($fila['ISTRUE'] == 1){
               return true;
           }
           else{
               return false;
           }
               
        }
      
        return false;
    }

    /**
     *  Comprueba si el mensaje especificado ha sido leído por un usuario
     * determinado
     * @param int $idUser
     * @param int $idMessage
     * @return boolean
     */
    public function isMessageRead($idUser, $idMessage) {
        $query = "SELECT COUNT(*) AS 'ISTRUE' FROM {$GLOBALS['DB_NOMBRE']}.MESSAGES_has_Recipients WHERE idMessage = $idMessage AND idRecipient = $idUser AND dateRead IS NOT NULL";
       
        $this->execute($query);
        if($this->connection->getNumRows() > 0){  
            
           $fila = $this->connection->fetch_array(MYSQLI_ASSOC);            
           if($fila['ISTRUE'] == 1){
               return true;
           }
           else{
               
               return false;
           }
               
        }
      
        return false;
    }

    /**
     * Marca como leído un mensaje por parte de un usuario, se utilizará
     * la hora por defecto del Sistema Gestor de Bases de Datos como hora
     * de lectura del mensaje.
     * 
     * @param int $idMessage
     * @param int $iduser
     * @return boolean true si la consulta se ejecutó con éxito. false en caso
     * contrario
     */
    public function setMessageRead($idMessage, $iduser) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.MESSAGES_has_Recipients SET dateRead = CURRENT_TIMESTAMP() WHERE idMessage = {$idMessage} AND idRecipient = {$iduser}";
        return $this->execute($query);
    }

    /**
     * Obtiene un array con los identificadores de usuario de cada destinatario
     * al que va dirigido el mensaje especificado
     * @param int $idMessage
     * @return int[] 
     */
    public function getDestinatarios($idMessage) {
        $query = "SELECT idRecipient FROM {$GLOBALS['DB_NOMBRE']}.MESSAGES_has_Recipients WHERE idMessage = {$idMessage}";
        $this->execute($query);
       
        $listadoObjetosMensajeMySQL = null;
        $listaMensajes = null; 
        if($this->connection->getNumRows() > 0){  
           
            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)){
                $listadoObjetosMensajeMySQL[] = $fila['idRecipient'];
                        
            }
            return $listadoObjetosMensajeMySQL;
        }
      
        return false;
    }

    public function sendMessage($idSender, $idRecipients, $subject, $message, $idReplied = null) {
           $mensaje = new Message(null,
              $idSender,
              $subject,
              $message,
              null,
              $idReplied);
           $query = "";
           
           if(!isset($idReplied)){
                $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.MESSAGES (IdSender,subject,message) VALUES($idSender,'{$subject}','{$message}');";
           }else{
                $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.MESSAGES (IdSender,subject,message) VALUES($idSender,'{$subject}','{$message}',$idReplied);";
           }
          
           
           $id = $this->execute($query);
           $mensajesEnviados = true;
           if($id != false){
               foreach ($idRecipients as $idRecipient) {
                   $query2 = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.MESSAGES_has_Recipients (idMessage,idRecipient) VALUES($id,$idRecipient);";
                   
                   if($this->execute($query2) == false){
                       $mensajesEnviados = false;
                   } 
               }
           }else{
               $mensajesEnviados = false;
           }
           return $mensajesEnviados;
    }

}
