<?php

/**
 * La definición de las variables globales del sistema se realiza en el archivo
 * globals.php
 */
include './globals.php';

/**
 * Ruta del directorio raíz de la Web
 */
define('ROOT', __DIR__ .'/');


/**
 * Nombre del esquema de la base de datos
 */
$GLOBALS["DB_NOMBRE"] = 'pCompetencias';

/**
 * Nombre del esquema de la base de datos
 */
$GLOBALS['DB_HOST'] = 'localhost';


/**
 * Nombre del esquema de la base de datos
 */
$GLOBALS["DB_PORT"] = 3306;

/**
 * Nombre del esquema de la base de datos
 */
$GLOBALS["DB_USER"] = 'root';


/**
 * Nombre del esquema de la base de datos
 */
$GLOBALS["DB_PASSWORD"] = null;


/**
 * Lenguaje por defecto de la interfaz de usuario *
 * http://www.w3schools.com/tags/ref_language_codes.asp 
 */


$GLOBALS["UI_LANG"] = 'es';

/**
 * Variante regional del lenguaje de la interfaz de usuario
 */

$GLOBALS["UI_LANG_VARIANT"] = 'ES';



/**
 * Nombre del sistema
 */

$GLOBALS["SYSTEM_NAME"] = "PRAXIS-Medicina";

/**
 * Información de copyright (en formato html)
 */

$GLOBALS["SYSTEM_COPYRIGHT"] = "Copyright © 2014 Facultad de Medicina - ETSIIT - UGR ";

/**
 * Zona horaria
 */

$GLOBALS["SYSTEM_TIMEZONE"] = "Europe/Madrid";

/**
 * Formato de fecha que mostrará la interfaz
 * Ver http://www.php.net/manual/en/dateinterval.format.php
 * @global string $GLOBALS['SYSTEM_SHORT_TIMEFORMAT']
 * @name $SYSTEM_SHORT_TIMEFORMAT 
 */
$GLOBALS["SYSTEM_SHORT_TIMEFORMAT"] = "d/m/y";


/**
 * Formato de fecha y hora que mostrará la interfaz
 * Ver http://www.php.net/manual/en/dateinterval.format.php
 * @global string $GLOBALS['SYSTEM_SHORT_TIMEFORMAT']
 * @name $SYSTEM_SHORT_TIMEFORMAT 
 */
$GLOBALS["SYSTEM_LONG_TIMEFORMAT"] = "d/m/Y H:i:s";

/**
 * Formato de fecha y hora utilizado por el SGDB
 * Ver http://www.php.net/manual/en/dateinterval.format.php
 * @global string $GLOBALS['SYSTEM_DATABASE_TIMEFORMAT']
 * @name $SYSTEM_DATABASE_TIMEFORMAT 
 */
$GLOBALS["SYSTEM_DATABASE_TIMEFORMAT"] = "Y-m-d H:i:s";

/**
 * Codificación con la que se obtendrán los datos desde la Base de Datos
 *  
 * @global string $GLOBALS['SYSTEM_CHARSET']
 * @name $SYSTEM_CHARSET 
 */
$GLOBALS["DB_CHARSET"] = "utf8";

/**
 *Codificación con la que se generará el código html
 * @global string $GLOBALS['SYSTEM_CHARSET']
 * @name $SYSTEM_CHARSET 
 */
$GLOBALS["SYSTEM_CHARSET"] = "utf-8";

/**
 * Identificador del lugar donde se cursan estudios
 * @global int $GLOBALS['SYSTEM_CENTRE']
 * @name $SYSTEM_CENTRE 
 */
$GLOBALS["SYSTEM_CENTRE"] = 1;

/**
 * Habilita o deshabilita la muestra de errores por pantalla
 * @global boolean $GLOBALS['SYSTEM_DEBUGGER_MODE']
 * @name $SYSTEM_DEBUGGER_MODE 
 */
$GLOBALS["SYSTEM_DEBUGGER_MODE"] = true;

/**
 * Correo electrónico de contacto
 * @global string $GLOBALS['GENERAL_MAIL_CONTACT']
 * @name $GENERAL_MAIL_CONTACT 
 */
$GLOBALS["GENERAL_MAIL_CONTACT"] = 'jorgechp@correo.ugr.es';


/**
 * Número de evaluaciones que se tienen en cuenta para determinar
 * la evaluación de un estudiante
 * @global integer $GLOBALS['GENERAL_NUMBER_OF_EVALUATIONS']
 * @name $GLOBALS["GENERAL_NUMBER_OF_EVALUATIONS"]
 */
$GLOBALS["GENERAL_NUMBER_OF_EVALUATIONS"] = 5;

/**
 * Tamaño máximo de la imagen de perfil a subir (en bytes)
 * @global integer $GLOBALS['GENERAL_MAX_PHOTO_SIZE']
 * @name $GLOBALS["GENERAL_MAX_PHOTO_SIZE"
 */                                   
$GLOBALS["GENERAL_MAX_PHOTO_SIZE"] = 750000;

/**
 * Expresión regular para determinar los dominios de correo electrónico
 * admitidos en el registro
 * @global String $GLOBALS['GENERAL_MAIL_DOMAIN_RESTRICTION']
 * @name $GLOBALS["GENERAL_MAIL_DOMAIN_RESTRICTION"]
 */                                   
$GLOBALS["GENERAL_MAIL_DOMAIN_RESTRICTION"] = '/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])*(\.)?[ugr.es]$/';

/**
 * Dominios válidos, para mostrar como mensaje humano en la interfaz
 * 
 * @global String $GLOBALS['GENERAL_MAIL_DOMAIN_RESTRICTION']
 * @name $GLOBALS["GENERAL_MAIL_DOMAIN_RESTRICTION"]
 */                                   
$GLOBALS["GENERAL_MAIL_DOMAIN_RESTRICTION_HUMAN"] = 'ugr.es';

/**
 * Nombre del logotipo de la aplicación
 * @global string $GLOBALS['GENERAL_LOGO']
 * @name $GENERAL_LOGO 
 */
$GLOBALS["GENERAL_LOGO"] = 'Stethoscopius2.png';

/**
 * Nota media mínima considerada como aprobado
 * @global float $GLOBALS['GENERAL_EVALUATION_PASS']
 * @name $GENERAL_EVALUATION_PASS 
 */
$GLOBALS["GENERAL_EVALUATION_PASS"] = 2.5;
