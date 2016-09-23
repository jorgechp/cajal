<?php

include_once (ROOT .'/Layers/Persistance/dao/ProgramSettingsDAO.php');

/**
 * Description of ProgramSettinsMySQLDAO
 *
 * @author jorge
 */
class ProgramSettingsMySQLDAO extends ProgramSettingsDAO{
    public function getCurrentIdYear() {
        $query = "SELECT idCurrentYear FROM {$GLOBALS['DB_NOMBRE']}.Program_Settings ";
        
        if($this->execute($query)){
            if($this->connection->getNumRows() > 0){  
                $fila = $this->connection->fetch_array(MYSQLI_ASSOC);

                return $fila['idCurrentYear'];
            }
      
        }else
            return false;
    }

    public function setCurrentIdYear($idYear) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.Program_Settings SET idCurrentYear = {$idYear}";
        
        return $this->execute($query);
    }
    
    /**
     * Ejecuta una consulta MySQL en la Base de Datos
     * @param String $query
     */
    private function execute($query) {
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
