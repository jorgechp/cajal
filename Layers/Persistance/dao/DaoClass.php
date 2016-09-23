<?php



/**
 * Description of daoClass
 *
 * @author jorge
 */
class DaoClass {
    /**
     * Objeto conexión
     * 
     */
    protected $connection;
    
    /**
     * Almacena el último error devuelto por una consulta
     * @var integer 
     */
    protected $erroNo;
    
    /**
     * 
     * @param DB_MySQL $conection
     */
    function __construct($conection) {
        $this->connection = $conection;   
        
    }
    
    public function getErroNo() {
        return $this->erroNo;
    }


}
