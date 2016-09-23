<?php

require (ROOT . '/Layers/Persistance/dao/ActividadDAO.php');
require_once (ROOT . '/Interfaces/dao/IActividadDAO.php');
require_once (ROOT . '/objects/Activity.php');

/**
 * Clase para el manejo de Actividades en un SGBD MySQL
 *
 * @author jorge
 */
class ActividadMySQLDAO extends ActividadDAO {

    /**
     * Elimina todos los elementos de la Base de Datos
     */
    public function clean() {
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Activity";
        return $this->execute($query);
    }

    /**
     * Elimina un elemento de la base de datos
     * @param int $id -> Identificador de la actividad
     * 
     */
    public function delete($id) {
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Activity WHERE idActivity = " . $id;
        
        return $this->execute($query);
    }

    /**
     * Convierte un objeto que representa la Base de datos en un objeto Actividad
     * @param String[] $row Array asociativo de Strings
     * @return Activity
     */
    private function convertMySQLObjectActivity($row) {

        $idActividad = $row['idActivity'];
        $curso = $row['idYear'];
        $descripcion = $row['descripcion'];
        $nombre = $row['nombre'];
        $isActive = $row['isActivo'];
        $codigo = $row['codigo'];        
        $idCategoria= $row['idCategoriaTipo'];
        
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Competence_has_activity WHERE idActivity = " . $idActividad;


        $listaCompetencias[] = array();
        if ($this->connection->getNumRows() > 0) {
            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)) {
                $listaCompetencias[] = $fila["idCompetence"];
            }
        }
        return new Activity($idActividad, $curso, $descripcion, $nombre, $listaCompetencias, $isActive,$codigo,$idCategoria);
    }

    /**
     *  Obtiene un objeto actividad desde la Base de Datos
     * @param int $id
     * @return Activity
     */
    public function get($id) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Activity WHERE idActivity = {$id}";
        $this->execute($query);

        if ($this->connection->getNumRows() > 0) {
            $fila = $this->connection->fetch_array(MYSQLI_ASSOC);

            return $this->convertMySQLObjectActivity($fila);
        }

        return false;
    }

    /**
     * Obtiene todas las actividades de la Base de Datos
     * @return Activity[]
     */
    public function getAll() {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Activity";
        $this->execute($query);

        $listaObjetosMySQL = null;
        $listaActividades = null;
        if ($this->connection->getNumRows() > 0) {

            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)) {
                $listaObjetosMySQL[] = $fila;
            }
            $size = count($listaObjetosMySQL);

            for ($index = 0; $index < $size; $index++) {
                $listaActividades[$index] = $this->convertMySQLObjectActivity($listaObjetosMySQL[$index]);
            }


            return $listaActividades;
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
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Activity ORDER BY $param ";
        if ($order == 0) {
            $query .="ASC";
        } else {
            $query .= "DESC";
        }

        $this->execute($query);

        $listaObjetosMySQL = null;
        $listaActividades = null;
        if ($this->connection->getNumRows() > 0) {

            while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)) {
                $listaObjetosMySQL[] = $fila;
            }
            $size = count($listaObjetosMySQL);

            for ($index = 0; $index < $size; $index++) {
                $listaActividades[$index] = $this->convertMySQLObjectActivity($listaObjetosMySQL[$index]);
            }


            return $listaActividades;
        }

        return false;
    }

    /**
     *  Inserta un objeto Actividad en la Base de Datos, retornando su identificador
     * @param Activity $object
     * @return int
     */
    public function insert($object) {
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Activity (idYear,descripcion,nombre, isActivo,codigo,idCategoriaTipo) "
                . "VALUES ({$object->getCurso()},"
                . "'{$object->getDescripcion()}',"
                . "'{$object->getNombre()}',"
                . "{$object->getIsActive()},"
                . "'{$object->getCodigo()}',"               
                . "'{$object->getIdCategoria()}'"
                . ")";


                
        return $this->execute($query);
    }

    /**
     * Actualiza la tabla de actividades
     * @param Activity $object
     * @return boolean
     */
    public function update($object) {
        $query = "UPDATE {$GLOBALS['DB_NOMBRE']}.Activity "
                . "SET descripcion = '{$object->getDescripcion()}',"
                . "nombre = '{$object->getNombre()}',"
                . "idYear = '{$object->getCurso()}', "
                . "isActivo = {$object->getIsActive()}, "
                . "codigo = '{$object->getCodigo()}', "
                . "idCategoriaTipo = '{$object->getIdCategoria()}' "
                . "WHERE idActivity = {$object->getIdActividad()}";

             
       
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
     * Obtiene la lista de actividades impartidas por un profesor
     * @param int $idProfessor
     * @param int $idYear
     * @return int[] o false si no se produjo ningún resultado
     */
    public function getActivityByProfessor($idProfessor, $idYear = -1) {
        $query = "";
        if ($idYear == -1) {
            $query = "SELECT idActivity FROM {$GLOBALS['DB_NOMBRE']}.Professor_has_Activity WHERE idProfessor = {$idProfessor}";
        } else {
            $query = "SELECT idActivity FROM {$GLOBALS['DB_NOMBRE']}.Professor_has_Activity WHERE idProfessor = {$idProfessor} AND idYear = {$idYear}";
        }
        
        if ($this->execute($query)) {


            $listaActividades = null;
            if ($this->connection->getNumRows() > 0) {

                while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)) {
                    $listaActividades[] = $fila['idActivity'];
                }

                return $listaActividades;
            }
        } else {
            return false;
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
                $listaMensajes[$index] = $this->convertMySQLObjectActivity($listadoObjetosMensajeMySQL[$index]);
            }


            return $listaMensajes;
        }

        return false;
    }

    /**
     * Comprueba si la actividad especificada es impartida por el $idProfessor
     * 
     * @param int $idActivity
     * @param int $idProfessor
     */
    public function isActivityLinkedByProfessor($idActivity, $idProfessor) {
        $query = "SELECT COUNT(*) AS 'RESULTADO' FROM {$GLOBALS['DB_NOMBRE']}.Professor_has_Activity WHERE idProfessor = {$idProfessor} AND idActivity = {$idActivity}";
        
        if ($this->execute($query)) {
            if ($this->connection->getNumRows() > 0) {
                $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
                return $fila['RESULTADO'];
            }
        }
    }

    /**
     * Obtiene todos los estudiantes asociados a una actividad
     * @param int $idActivity
     * @param int $idYear
     */
    public function getStudentsByActivity($idActivity, $idYear) {
        $query = "SELECT idUser FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Activity WHERE idActivity = {$idActivity} AND idYear = {$idYear}";

        $listadoObjetosMensajeMySQL = null;
        if ($this->execute($query)) {
            if ($this->connection->getNumRows() > 0) {

                while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)) {

                    $listadoObjetosMensajeMySQL[] = $fila['idUser'];
                }
                return $listadoObjetosMensajeMySQL;
            }            
        }
        return false;
    }
    
    /**
     * Obtiene todos los estudiantes asociados a una actividad
     * @param int $idActivity
     * @param int $idYear
     */
    public function getProfessorsByActivty($idActivity, $idYear) {
        $query = "SELECT idProfessor FROM {$GLOBALS['DB_NOMBRE']}.Professor_has_Activity WHERE idActivity = {$idActivity} AND idYear = {$idYear}";
        
        $listadoObjetosMensajeMySQL = null;
        if ($this->execute($query)) {
            if ($this->connection->getNumRows() > 0) {

                while ($fila = $this->connection->fetch_array(MYSQLI_ASSOC)) {

                    $listadoObjetosMensajeMySQL[] = $fila['idProfessor'];
                }
                return $listadoObjetosMensajeMySQL;
            }            
        }
        return false;        
    }


    public function isActivityLinkedyByCompetence($idActivity, $idCompetence) {
        $query = "SELECT COUNT(*) AS 'RESULTADO' FROM {$GLOBALS['DB_NOMBRE']}.Competence_has_Activity WHERE idActivity = {$idActivity} AND idCompetence = {$idCompetence}";


        if ($this->execute($query)) {
            if ($this->connection->getNumRows() > 0) {
                $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
                return $fila['RESULTADO'];
            }
        }
    }
    
    /**
     * Comprueba si un profesor tiene permisos administrativos sobre una
     * sesión
     */
    public function isProfessorLinkedtoActivity($idActivity, $idProfessor){        
        $query = "SELECT COUNT(*) AS 'resultado' FROM {$GLOBALS['DB_NOMBRE']}.Professor_has_Activity WHERE idProfessor = {$idProfessor} AND idActivity = {$idActivity} ";
        if ($this->execute($query)) {
            if ($this->connection->getNumRows() > 0) {
                $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
                return $fila['resultado'];
            }
        } 
        
    }

    /**
     * Inserta un estudiante en una actividad en un año determinado
     * @param int $idActivity
     * @param int $idStudent
     * @param int $idYear
     */
    public function insertStudentInActivity($idActivity, $idStudent,$idYear) {
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Student_has_Activity (idUser,idActivity,idYear) VALUES({$idStudent},{$idActivity},{$idYear})";
        return $this->execute($query);
    }

    /**
     * Elimina a un estudiante de una actividad
     * @param int $idActivity
     * @param int $idStudent
     * @param int $idYear
     */
    public function removeStudentOfActivity($idActivity, $idStudent, $idYear) {
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Activity WHERE idUser = {$idStudent} AND idActivity = {$idActivity} AND idYear = {$idYear}";
        return $this->execute($query);
    }

    /**
     * Obtiene una lista de actividades de un estudiante en un año determinado
     * @param int $idStudent
     * @param int $idYear
     * @return Activity[]
     */
    public function getActivityByStudent($idStudent, $idYear = -1) {
        
        $query = "";
        if ($idYear == -1) {
            $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Activity WHERE idActivity IN (SELECT idActivity FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Activity WHERE idUser = {$idStudent})";
        } else {
            $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Activity WHERE idActivity IN (SELECT idActivity FROM {$GLOBALS['DB_NOMBRE']}.Student_has_Activity WHERE idUser = {$idStudent} AND idYear = {$idYear})";
        }
        
        return $this->getAllByQuery($query);
    }

    /**
     * Añade una competencia a una actividad
     * @param int $idActivity
     * @param int $idCompetence
     * @return boolean
     */
    public function addCompetenceToActivity($idActivity, $idCompetence) {
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Competence_has_Activity(idCompetence,idActivity) VALUES ({$idCompetence},{$idActivity});";
        return $this->execute($query);
    }

    /**
     * Elimina una competencia de una actividad
     * @param int $idActivity
     * @param int $idCompetence
     * @return boolean
     */
    public function removeCompetenceFromActivity($idActivity, $idCompetence) {
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Competence_has_Activity WHERE idCompetence = {$idCompetence} AND idActivity = {$idActivity};";     
        return $this->execute($query);        
    }

    /**
     * Añade un profesor a una actividad
     * @param int $idActivity
     * @param int $idProfessor
     * @return boolean
     */
    public function addProfessorToActivity($idActivity, $idProfessor, $idYear) {
        $query = "INSERT INTO {$GLOBALS['DB_NOMBRE']}.Professor_has_Activity (idProfessor,idActivity, idYear) VALUES ({$idProfessor},{$idActivity}, {$idYear});";

        return $this->execute($query);        
    }

    /**
     * Elimina un profesor de una actividad
     * @param int $idActivity
     * @param int $idProfessor
     * @return boolean
     */
    public function removeProfessorFromActivity($idActivity, $idProfessor, $idYear) {
        $query = "DELETE FROM {$GLOBALS['DB_NOMBRE']}.Professor_has_Activity WHERE idProfessor = {$idProfessor} AND idActivity = {$idActivity} AND idYear = {$idYear};";     
        return $this->execute($query);              
    }

    /**
     * Obtiene las actividades que empiezan por $nombre
     * @param type $nombre
     */
    public function getActivityStartsWith($nombre) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Activity WHERE nombre LIKE '{$nombre}%' OR codigo LIKE '{$nombre}%'";
        return $this->getAllByQuery($query);
    }

    public function getActivitiesByCode($code) {
        $query = "SELECT * FROM {$GLOBALS['DB_NOMBRE']}.Activity WHERE codigo LIKE '{$code}'";
        return $this->getAllByQuery($query);       
    }

}
