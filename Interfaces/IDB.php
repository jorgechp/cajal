<?php

/**
 * Interfaz para el acceso a una Base de Datos
 * @author Jorge
 */
interface IDB {

    
    /**
     * Conecta a un Servidor de Base de Datos
     */
    public function connect();
    
     /**
     * Ejecuta una orden en el servidor de base de datos
     */
    public function execute($query);
    
    /**
     * Desconecta del servidor de base de datos
     */
    public function close();            
    
    /**
     * Comprueba que existe una conexión existente a un SGBD
     */
    public function isConnected();
    
    
    /**
     * Devuelve el número de filas
     */
    public function getNumRows();
    
    /**
     * Devuelve el número de columnas
     */
    public function getNumColumns();
 
     /**
     * Devuelve el identificador de la última inserción realizada
     */
    public function getIdInserted();
}
