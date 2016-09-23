<?php


/**
 *
 * @author jorge
 */
interface IValidadorCodigo{
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
    public function getTipoCodigo($codigo);
    public function validarCodigo($codigo,$tipoCodigo);    
    public function validar($codigo);
    
    public function getNombres($codigo);

    
    /**
     * Ejecuta una orden en la Base de Datos
     */
    public function execute($query);
    
}