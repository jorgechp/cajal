<?php

require (ROOT . '/Layers/Persistance/dao/CompetenciaTipoDAO.php');
require_once (ROOT . '/Interfaces/dao/ICompetenciaTipoDAO.php');
require_once (ROOT . '/objects/CompetenciaTipo.php');

/**
 * Clase para el manejo de Actividades en un SGBD MySQL
 *
 * @author jorge
 */
class CompetenciaTipoMySQLDAO extends CompetenciaTipoDAO {

    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.competencia_tipo";
        return $this->execute($query);
    }

    /**
     * Elimina un elemento de la base de datos
     * @param int $id -> Identificador de la actividad
     * 
     */
    public function delete($id) {
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.competencia_tipo WHERE idTipo = " . $id;
        
        return $this->execute($query);
    }


    /**
     *  Obtiene un objeto actividad desde la Base de Datos
     * @param int $id
     * @return Activity
     */
    public function get($id) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.competencia_tipo WHERE idTipo = {$id}";
        $this->execute($query);

        if ($this->connection->getNumRows() > 0) {
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);

            return $this->convertMySQLObjectCompetenciaTipo($fila);
        }

        return false;
    }
    
    private function convertMySQLObjectCompetenciaTipo($row){
        
        $idTipo = $row['idTipo'];
        $nombre = $row['nombre']; 
        $codigo = $row['codigo'];    
  
        
        return new CompetenciaTipo($idTipo, $nombre, $codigo);
        
        
    }

    /**
     * Obtiene todas las actividades de la Base de Datos
     * @return Activity[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.competencia_tipo";
        $this->execute($query);

        $listaObjetosMySQL = null;
        $listaTipo = null;
        if ($this->connection->getNumRows() > 0) {

            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)) {
                $listaObjetosMySQL[] = $fila;
            }
            $size = count($listaObjetosMySQL);

            for ($index = 0; $index < $size; $index++) {
                $listaTipo[$index] = $this->convertMySQLObjectCompetenciaTipo($listaObjetosMySQL[$index]);
            }


            return $listaTipo;
        }

        return false;
    }

    /**
     * Obtiene todos los objetos Actividad de la base de datos ordenados en base
     * a los valores de una columna
     * Si $order = 0 -> Ordenación ascendente
     * Si $order = 1 -> Ordenación descendente
     * (Por defecto, $order vale 0)
     * @param String $param
     * @param int $order
     * @return Activity[]
     */
    public function getAllOrderedBy($param, $order = 0) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.competencia_tipo ORDER BY $param ";
        if ($order == 0) {
            $query .="ASC";
        } else {
            $query .= "DESC";
        }

        $this->execute($query);

        $listaObjetosMySQL = null;
        $listaTipo = null;
        if ($this->connection->getNumRows() > 0) {

            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)) {
                $listaObjetosMySQL[] = $fila;
            }
            $size = count($listaObjetosMySQL);

            for ($index = 0; $index < $size; $index++) {
                $listaTipo[$index] = $this->convertMySQLObjectCompetenciaTipo($listaObjetosMySQL[$index]);
            }


            return $listaTipo;
        }

        return false;
    }

    /**
     *  Inserta un objeto Actividad en la Base de Datos, retornando su identificador
     * @param Activity $object
     * @return int
     */
    public function insert($object) {
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.competencia_tipo (nombre, codigo) "
                . "VALUES ({$object->getNombre()},"
                . "'{$object->getCodigo()}'"
                . ")";

                      
        return $this->execute($query);
    }

    /**
     * Actualiza la tabla de actividades
     * @param Activity $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.competencia_tipo "
                . "SET nombre = '{$object->getNombre()}',"
                . "codigo = '{$object->getCodigo()}' "
                . "WHERE idTipo = {$object->getIdTipo()}";

          
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
        if ($idInsertada == 0) {
            return $res;
        } else {
            return $idInsertada;
        }
    }

    /**
     * Obtiene todos los elementos de la tabla de Actividad que cumplan
     * con la consulta especificada en el argumento
     * @param String $query consulta
     * @return Message[] or boolean(false) en caso de error
     */
    private function getAllByQuery($query) {
        
        $this->execute($query);

        $listadoObjetosMensajeMySQL = null;
        $listaMensajes = null;
        if ($this->connection->getNumRows() > 0) {

            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)) {
                $listadoObjetosMensajeMySQL[] = $fila;
            }
            $size = count($listadoObjetosMensajeMySQL);

            for ($index = 0; $index < $size; $index++) {
                $listaMensajes[$index] = $this->convertMySQLObjectCompetenciaTipo($listadoObjetosMensajeMySQL[$index]);
            }


            return $listaMensajes;
        }

        return false;
    }

    /**
     * Obtiene las actividades que empiezan por $nombre
     * @param type $nombre
     */
    public function getCompetenciaTipoStartsWith($nombre) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.competencia_tipo WHERE nombre LIKE '{$nombre}%' OR codigo LIKE '{$nombre}%'";
        return $this->getAllByQuery($query);
    }

    public function getCompetenciaTipoByCode($code) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.competencia_tipo WHERE codigo LIKE '{$code}'";
        return $this->getAllByQuery($query);        
    }

}