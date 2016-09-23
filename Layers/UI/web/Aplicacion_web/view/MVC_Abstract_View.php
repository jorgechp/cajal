<?php

/**
 *
 * @author jorge
 */
abstract class MVC_Abstract_View {
    /**
     * Almacena la web final, con todas las transformaciones que se vayan aplicando
     * @var String 
     */
    protected $html;
    
    /**
     * Ruta de la web que servirá como plantilla
     * @var String 
     */
    protected $templateURL;
    
    /**
     * Ruta de la cabecera de la plantilla
     * @var String 
     */
    protected $headerURL;
    
    /**
     * Array de string con los n valores que definen las n plantillas de la barra de menú
     * para cada login
     * @var String
     */
    protected $navMenuURL;
    
    /**
     * Ruta de la plantilla que define el menú de filtros
     * @var type 
     */
    protected $navMenuFiltersURL;
    
    /**
     * Ruta de la plantilla que define el menú de noticias
     * @var type 
     */
    protected $newsURL;
    
    /**
     * Ruta de la plantilla que define el menú de búsqueda
     * @var type 
     */
    protected $searchURL;
    
    /**
     * Ruta de la plantilla que define el pie de página
     * @var type 
     */
    protected $footerURL;
    
    /**
     * Ruta de la plantilla que define el menú de login para un usuario que
     * ha iniciado sesión
     * @var string 
     */
    protected $loginNavUrl;
    
    /**
     * Ruta de la plantilla que define el menú de login para un usuario que
     * NO ha iniciado sesión
     * @var string 
     */
    protected $noLoginNavUrl;
    

    /**
     * Ruta de la plantilla que define los diferentes roles de usuario
     * @var String 
     */
    protected $changeRolURL;
    
    /**
     * Ruta del logotipo de la aplicación
     * @var String
     */
    protected $logoURL;
    
    /**
     * Directorio con imágenes de la aplicación
     * @var String 
     */
    protected $imgFolder;
    
       /**
     * Directorio princopal de imágenes
     * @var String 
     */ 
    protected $imgFolderRoot;
    

    
    abstract protected function load_template();
    abstract protected function apply_i18n($diccionario);
    abstract protected function executeContent($fileURL);
    abstract protected function apply_SystemContent($systemDictyonary, $rol, $numberOfMessages = null);
    abstract protected function apply_data($data);
    abstract protected function apply_help_context($title,$content);
    abstract protected function apply_navmenu_filters($data);
    abstract protected function apply_error($data);
    abstract protected function apply_navMenu($idSelectedOption);
    abstract public function viewHTML($diccionario,$systemDictyonary, $rol, $data = null, $idSelectedOption = null);
}
?>