<?php
include (ROOT .'/Interfaces/IDB.php');

/**
 * Clase para la conexión y manejo a un SGBD MYSQL
 *
 * @author Jorge
 */
class DB_MySQL implements IDB{
    
    
    private $host;
    private $port;
    private $user;
    private $password;
    private $schema;
    
    private $link; //Objeto que guarda la conexión al SGBD
    private $sentencia; // Guarda la información de la última sentencia ejecutada
    
    /**
     * 
     * Crea el objeto DB con los parámetros de conexión de un SGBD
     * 
     * @param string $host
     * @param int $port
     * @param string $user
     * @param string $password
     * @param string $schema
     */
    function __construct($host, $port, $user, $password, $schema) {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->schema = $schema;
    }

    
    /**
     * Desconecta con el SGBD, devuelve
     *   TRUE si la desconexión tuvo éxito.
     *   FALSE en caso contrario
     * @return boolean 
     */
    public function close() {       
        return $this->link->close();
    }

    /**
     * 
     * Conecta a un SGBD
     * Devuele:
     *  TRUE -> La conexión se realizó correctamente
     *  FALSE -> Hubo un error al conectar
     * 
     * @return boolean
     */
    public function connect() {
        $this->link = new mysqli($this->host, $this->user, $this->password, $this->schema,$this->port);
        
   
        if ($this->link->connect_errno) { //Si se produjo un error, el número de error se almacena aquí
            return false;
        }
        $this->link->set_charset($GLOBALS["DB_CHARSET"]);
        $this->execute("USE {$this->schema}");
        return true;
    }

    /**
     * 
     * Ejecuta una orden en el servidor de base de datos
     * Devuelve:
     *  Objeto de la clase mysqli_result -> La consulta se realizó correctamente.
     *  null -> Se realizó una orden que no devuelve objetos (borrado, inserción...) de forma correcta.
     *  FALSE -> hubo un error al ejecutar la orden
     * 
     * @param string $query
     * @return mysqli_result
     * 
     */
    public function execute($query) {

    $this->sentencia = $this->link->query($query);
        if (!$this->sentencia) {            
            return false;
        }
    
        return true;

    }
    
    /**
     * Obtiene una tupla del dataset de la última consulta realizada
     * Ver  http://us1.php.net/manual/es/mysqli-result.fetch-array.php
     * @param int $resulttype
     * @return String[]
     */
    public function fetch_array($resulttype = MYSQLI_BOTH){
        return $this->sentencia->fetch_array($resulttype);
    }
    
 

    /**
     * 
     * Comprueba si existe una conexión activa al servidor.
     * NOTA: Si mysqli.reconnect está activada. Intentará reconectar en caso
     * de desconexión. http://www.php.net/manual/es/mysqli.configuration.php#ini.mysqli.reconnect
     * 
     * Devuelve:
     *  TRUE -> Si la conexión está activa
     *  FALSE -> En caso contrario
     * @return boolean
     */
    public function isConnected() {
        if (is_null($this->link)) {
            return false;
        }
        return $this->link->ping();
    }
    
    /**
     * Devuelve el código de error de la última función llamada, o 0 si no se
     * produjo error alguno.
     * @return int
     */
    public function getErrorNo(){
        return $this->link->errno;       
    }

    public function getNumColumns() {
        return $this->sentencia->num_columns;
        
    }

    public function getNumRows() {
        return $this->sentencia->num_rows;
    }

    public function getIdInserted() {
        return $this->link->insert_id;
    }

}
