<?php

require (ROOT . '/Layers/Persistance/dao/ValidadorCodigoDAO.php');
require_once (ROOT . '/Interfaces/dao/IValidadorCodigo.php');


/**
 * Clase para el manejo de Actividades en un SGBD MySQL
 *
 * @author jorge
 */
class ValidadorCodigoMySQLDAO extends ValidadorCodigoDAO {

    public function validar($codigo) {
        $tipo = $this->getTipoCodigo($codigo);        
        return $this->validarCodigo($codigo, $tipo);
    }

    public function validarCodigo($codigo, $tipoCodigo) {
        $codigoDividido = explode('-', $codigo);
        $colaConsulta = array();
        
        $resultado = true ;
        switch ($tipoCodigo){
            case 1:
                $colaConsulta[]  = "SELECT * FROM actividad_tipo WHERE codigo LIKE '$codigoDividido[0]-$codigoDividido[1]';"; 
  
                if(!is_numeric($codigoDividido[2])){                    
                    $resultado = false ;
                }
                if($codigoDividido[3][0] != 'G'){
                    $resultado = false ;
                }                
                break;
            case 2:
                $colaConsulta[]  = "SELECT * FROM competencia_tipo WHERE codigo LIKE '$codigoDividido[0]';";
                $colaConsulta[]  = "SELECT * FROM Competence_Materia WHERE codigo LIKE '$codigoDividido[1]';";
                if(!is_numeric($codigoDividido[2])){
                    $resultado = false ;
                }
                break;
            case 3:
                $colaConsulta[]  = "SELECT * FROM Competence_Materia WHERE codigo LIKE '$codigoDividido[0]';";
                if(!is_numeric($codigoDividido[1])){
                    $resultado = false ;
                }
                break;
            default:
                $resultado = false;
                break;
        }
        
        if($resultado != false){
            
            foreach ($colaConsulta as $consulta) {
               
                $this->execute($consulta);  
                if($this->connection->getNumRows() == 0){
                  
                    $resultado = false;
                }
            }
        }
        
        return $resultado;
    }

    /**
     * Obtiene el tipo de código introducido.
     * 
     * El valor de retorno es un entero con la siguiente informacion:
     * 
     * -1 -> Codigo desconocido
     * 1 -> Actividad
     * 2 -> Competencia
     * 3 -> Indicador
     * 
     * @param String $codigo
     * @return int
     */
    public function getTipoCodigo($codigo){
        $codigoDividido = explode('-', $codigo);
        $tam = count($codigoDividido);
        $tipo = -1;
        switch ($tam) {
            case 2: // Indicador
                $tipo = 3; 
                break;
            case 3: //Competencia
                $tipo = 2;
                break;
            case 4:
                $tipo = 1; //Actividad
                break;
            default:
                $tipo = -1;
                break;
        }
        return $tipo;
    }

    public function execute($query) {
        $this->connection->connect();
        $res = $this->connection->execute($query);          
        $this->connection->close();   
    }

    public function getNombres($codigo) {
        $tipo = $this->getTipoCodigo($codigo);  
        $codigoDividido = explode('-', $codigo);
        $colaConsulta = array();
        $nombres = array();
        $resultado = true;
        switch ($tipo){
            case 1:
                $colaConsulta[]  = "SELECT nombre FROM actividad_tipo WHERE codigo LIKE '$codigoDividido[0]-$codigoDividido[1]';"; 
                break;
            case 2:
                $colaConsulta[]  = "SELECT nombre FROM competencia_tipo WHERE codigo LIKE '$codigoDividido[0]';";
                $colaConsulta[]  = "SELECT nombre FROM Competence_Materia WHERE codigo LIKE '$codigoDividido[1]';";
                break;
            case 3:
                $colaConsulta[]  = "SELECT nombre FROM Competence_Materia WHERE codigo LIKE '$codigoDividido[0]';";
                break;
            default:
                $resultado = false;
                break;
        }
        
        if($resultado){
           
            foreach ($colaConsulta as $consulta) {                 
                $this->execute($consulta); 

                if($this->connection->getNumRows() > 0){

                    $fila = $this->connection->fetch_array(MYSQLI_ASSOC);
                    $nombres[] = $fila['nombre'];
                }            
            }
        }
        return $nombres;
    }

}