<?php
/**
 * index.php
 * 
 * ESTA PÁGINA SIRVE PARA PERMITIR LA ENTRADA AL SERVIDOR DESDE DIFERENTES INTERFACES
 * Y TRABAJA A NIVEL LÓGICO. NO ES UN ARCHIVO QUE SE OCUPE DE LA INTERFAZ DE
 * USUARIO, QUE SERÁ LLAMADA DESDE MÁS ADELANTE
 */
function fatal_handler() {
    
    $error = error_get_last();
    
    // Descomentar para depuracion de errores
       print_r($error);        
        die();
        
        
        if ($error['type'] == E_ERROR) {
            if (headers_sent()) {
                echo '<p><b>REMEMBER: WE ARE IN DEBUG MODE -> HEADERS SENT. YOU CAN CHANGE (YOU MUST CHANGE IF IN PRODUCTION MODE) IN CONFIG.PHP</b></p>';
            }else{ 
                if(strpos($error['message'], '#NOT_CURRENT_YEAR_SET#')){
                    echo 'No se ha establecido un año actual en la Base de Datos, el sistema no puede continuar';
                }else{
                    header('Location: index.php?error');
                }                
                die();
            }
        } 
}

register_shutdown_function( "fatal_handler" );

require './config.php';
include_once (ROOT .'/objects/UserSession.php');

if($GLOBALS["SYSTEM_DEBUGGER_MODE"]){    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}else{
    error_reporting(0);
}

/* 
 * Incluye todos los objetos necesarios en la capa lógica
 */

include './Layers/Logic/IncludeObjects.php';

session_start();

if( ! ini_get('date.timezone') )
{
    date_default_timezone_set($GLOBALS["SYSTEM_TIMEZONE"]);
}

/**
 * Conexión con la interfaz de persistencia
 * @global DB_MySQL $GLOBALS['SYSTEM_CONNECTION']
 * @name $SYSTEM_CONNECTION 
 */
$GLOBALS["SYSTEM_CONNECTION"] = new DB_MySQL(
            $GLOBALS['DB_HOST'],
            $GLOBALS["DB_PORT"],
            $GLOBALS["DB_USER"],
            $GLOBALS["DB_PASSWORD"],
            $GLOBALS["DB_NOMBRE"]
        );



function actualizarCursoActual($settings){
    $programSettingsDao = new ProgramSettingsMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
    $cursoDAO = new CursoMySQLDAO($GLOBALS["SYSTEM_CONNECTION"]);
    $currentIdYear = $programSettingsDao->getCurrentIdYear();
    
    $cursoActual = $cursoDAO->get($currentIdYear);
    
    
    if(!is_numeric($currentIdYear)){        
        throw new Exception('#NOT_CURRENT_YEAR_SET#', 7);
    }
    $settings->setIdCurrentYear($currentIdYear);
 
    $settings->setNameCurrentYear($cursoActual->getInitialYear().'/'.$cursoActual->getFinalYear());
    return $settings;
}

/**
 * Configuración de los parámetros de sesión
 */
if(isset($_SESSION['login']) && $_SESSION['login']){
    
    $sessionObject = $_SESSION['sessionObject'];
}
else{
    $settings = new ProgramSettings();   
    $settings = actualizarCursoActual($settings);   
    $_SESSION['login'] = 0;
    $_SESSION['PROGRAM_SETTINGS'] = $settings;
    
}


if(isset($_SESSION['UPDATE_REQUIRED'])){
    $settings = $_SESSION['PROGRAM_SETTINGS']; 
    $settings = actualizarCursoActual($settings);  
    $_SESSION['PROGRAM_SETTINGS'] = $settings;
    unset($_SESSION['UPDATE_REQUIRED']);
}







/**
 * LLamada a la interfaz de usuario
 */
$SYSTEM_UI_MODE = 0;
if($SYSTEM_UI_MODE == 0){
    include './Layers/UI/web/Aplicacion_web/index.php';
}
else{
    include './Layers/UI/mobile/';
    throw new Exception("NOT IMPLEMENTED YET", 1, null);
    
}



?>