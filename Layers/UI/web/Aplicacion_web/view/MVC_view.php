<?php

require(constant("FOLDER") . '/view/MVC_Abstract_View.php');

/**
 * Vista estandar
 *
 * @author jorge
 */
class MVC_view extends MVC_Abstract_View {

    function __construct() {
        $this->templateURL = constant("FOLDER") . '/view/structure/Vindex.php';
        $this->headerURL = constant("FOLDER") . '/view/structure/head.php';
        $this->navMenuFiltersURL = constant("FOLDER") . '/view/structure/navMenuFilters.php';
        $this->newsURL = constant("FOLDER") . '/view/structure/news.php';
        $this->searchURL = constant("FOLDER") . '/view/structure/search.php';
        $this->footerURL = constant("FOLDER") . '/view/structure/footer.php';
        $this->loginNavUrl = constant("FOLDER") . '/view/structure/login/loginNav.html';
        $this->changeRolURL = constant("FOLDER") . '/view/structure/changeRolMenu.html';
        $this->imgFolder = constant("FOLDER") . '/view/images/commons/';
        $this->imgFolderRoot = constant("FOLDER") . '/view/images/';

        $this->navMenuURL = array();
        $this->navMenuURL[0] = constant("FOLDER") . '/view/structure/navMenuLogin.php';
        $this->navMenuURL[1] = constant("FOLDER") . '/view/structure/navMenuStudents.php';
        $this->navMenuURL[2] = constant("FOLDER") . '/view/structure/navMenuProfessors.php';
        $this->navMenuURL[3] = constant("FOLDER") . '/view/structure/navMenuAdmins.php';

        $this->logoURL = $GLOBALS["GENERAL_LOGO"];
    }

    protected function load_template() {
        $this->html = file_get_contents($this->templateURL);
    }

    protected function apply_i18n($diccionario) {
        foreach ($diccionario as $key => $value) {
            $this->html = str_replace('#' . $key . '#', $value, $this->html);
        }
    }

    protected function executeContent($fileURL) {
        ob_start();
        include $fileURL;
        return ob_get_clean();
    }

    protected function apply_SystemContent($systemDictyonary, $rol, $numberOfMessages = null) {
        $header = $this->executeContent($this->headerURL);
        $search = $this->executeContent($this->searchURL);        
        $news = $this->executeContent($this->newsURL);
        $footer = $this->executeContent($this->footerURL);

        $loginNav = "";

        if ($_SESSION['login']) {
            $rolMenu = file_get_contents($this->changeRolURL);
            $loginNav = file_get_contents($this->loginNavUrl);
            $navMenu = $this->executeContent($this->navMenuURL[$rol - 1]);
            $navMenu = str_replace('{IMG_FOLDER}', constant("FOLDER"), $navMenu);
            $this->html = str_replace('{CHANGE_ROL}', $rolMenu, $this->html);
            $this->html = str_replace('{IS_ACTIVE_' . ($rol - 1) . '}', 'class="linkInactive"', $this->html);
        } else {            
            $navMenu = $this->executeContent($this->navMenuURL[0]);
            $navMenu = str_replace('{IMG_FOLDER}', constant("FOLDER"), $navMenu);
        }

        if ($numberOfMessages != 0) {
            $navMenu = str_replace('{SYSTEM_NUMBER_MESSAGES}', '(' . $numberOfMessages . ')', $navMenu);
        } else {
            $navMenu = str_replace('{SYSTEM_NUMBER_MESSAGES}', null, $navMenu);
        }


        $this->html = str_replace('{CONTENT_HEAD}', $header, $this->html);
        $this->html = str_replace('{CONTENT_NAVMENU}', $navMenu, $this->html);        
        $this->html = str_replace('{CONTENT_SEARCH}', $search, $this->html);
        $this->html = str_replace('{CONTENT_NEWS}', $news, $this->html);
        $this->html = str_replace('{CONTENT_FOOTER}', $footer, $this->html);
        $this->html = str_replace('{CONTENT_LOGIN}', $loginNav, $this->html);
        $this->html = str_replace('{LOGO_URL}', $this->logoURL, $this->html);
        if ($_SESSION['login']) {
            $this->html = str_replace('{AVATAR_URL}', "avatarGenerator/AvatarGenerator.php?name={$_SESSION['sessionObject']->getAvatar()}", $this->html);
        }else{
            $this->html = str_replace('{AVATAR_URL}', $this->logoURL, $this->html);
        }
        $this->html = str_replace('{IMG_FOLDER_ROOT}', $this->imgFolderRoot, $this->html);

        foreach ($systemDictyonary as $key => $value) {
            $this->html = str_replace('{' . $key . '}', $value, $this->html);
        }
        
        //Aplicar roles de usuario
        $this->html = str_replace('{USER_ROL}', $rol-1, $this->html);
   
        //Curso actual
        $text = $_SESSION['PROGRAM_SETTINGS']->getNameCurrentYear();
        if(isset($_SESSION['VIRTUAL_YEAR']) && $_SESSION['VIRTUAL_YEAR']){
            $text .=' #MODIFIED#';
        }
        $this->html = str_replace('{CURRENT_YEAR}',$text , $this->html);
        
    }

    protected function apply_data($data) {
        foreach ($data as $key => $value) {
            $this->html = str_replace('{' . $key . '}', $value, $this->html);
        }
        $cadenaGenerada = "";
       
        if (isset($data['TIPOS'])) {
            foreach ($data['TIPOS'] as $tipo) {
                $cadenaGenerada .= "<li><span><a href='#'>{$tipo->getName()}</a></span></li>";
            }
        }
        if (isset($data['TIPOS'])) {
            foreach ($data['TIPOS'] as $tipo) {
                $cadenaGenerada .= "<li><span><a href='#'>{$tipo->getName()}</a></span></li>";
            }
        }        



        $this->establecer('{TIPOS}', $cadenaGenerada);
    }

    protected function apply_error($data) {
        if (isset($data['CONTENT_ERROR'])) {
            $this->establecer('{CONTENT_ERROR}', $data['CONTENT_ERROR']);
        } else {
            $this->establecer('{CONTENT_ERROR}', null);
        }
    }

    /**
     * Reemplaza una etiqueta html por su valor y actualiza el código html
     * a mostrar.
     * @param String $etiqueta
     * @param String $valor
     * @param String $output si se asigna un valor a esta variable, 
     * la transformación se realiza sobre $output y se retorna el resultado
     * @return String cadena
     */
    protected function establecer($etiqueta, $valor, $output = null) {
        if (is_null($output)) {
            $this->html = str_replace($etiqueta, $valor, $this->html);
        } else {
            return str_replace($etiqueta, $valor, $output);
        }
    }

    /**
     * Genera y muestra el html de la vista
     * @param String[] $diccionario
     * @param String[] $systemDictyonary
     * @param String[] $data
     */
    public function viewHTML($diccionario, $systemDictyonary, $rol, $data = null, $idSelectedOption = null) {

        $this->load_template();
        if (isset($data['NUMBER_OF_MESSAGES'])) {
            $this->apply_SystemContent($systemDictyonary, $rol, $data['NUMBER_OF_MESSAGES']);
        } else {
            $this->apply_SystemContent($systemDictyonary, $rol);
        }

        $this->apply_data($data);
        $this->apply_error($data);
        $this->apply_i18n($diccionario);
        if ($idSelectedOption != null) {
            $this->apply_navMenu($idSelectedOption);
        }

   
        //Elimina los tag de sistema que no han sido eliminados
        $this->html = preg_replace('/{.*}/', '', $this->html);
        //Cambia el texto entre !# por  {
        $this->html = preg_replace('/!#/', '{', $this->html);
        //Cambia el texto entre !# por  {
        $this->html = preg_replace('/#!/', '}', $this->html);     
        
        print $this->html;
    }

    protected function apply_navMenu($idSelectedOption) {    
        
        $this->establecer('{IS_SELECTED_' . $idSelectedOption . '}', 'class="active"');
    }

    /**
     * Aplica información sobre ayuda en el contexto
     * @param String $title
     * @param String $content
     */
    protected function apply_help_context($title, $content) {
        $this->establecer('{HELP_CONTEXT_TITLE}', $title);
        $this->establecer('{HELP_CONTEXT}', $content);
    }

    /**
     * Aplica información para el menú de filtrado
     * Se requiere un Array con la siguiente estructura:
     *  - Cada elemento del array es un vector con estructura clave-valor con la siguiente información:
     *      [nombreEnlace] => [LinkEnlace,titleText]
     *  -   ['||'] => [nombreSeparador]
     *  - Si $data == null, el menú de filtrado de información es suprimido de la interfaz de usuario
     * @param array $data
     */
    protected function apply_navmenu_filters($data) {
        if(is_null($data) == false){
            $cadenaGenerada = $this->executeContent($this->navMenuFiltersURL);
            $lista = "";
            
            foreach ($data as $key => $value) {
                if(count($value) == 2){                    
                    $lista .= '<li class="road_li">'."<a href='$value[0]' title='$value[1]'>$key</a></li>";
                }
                else if(count($value) == 3){                    
                    $lista .= '<li class="road_li">'."<a href='$value[0]' title='$value[1]'><img src='$value[2]' alt='$value[1]'/> $key</a></li>";
                }                
                else{                    
                    $lista .= "<li class='road_li_separador'><span class='separador'>$value</a></li>";
                }
            }
            $cadenaGenerada = $this->establecer('{LIST_NAVMENUFILTERS}',$lista, $cadenaGenerada);
            $this->html = str_replace('{CONTENT_NAVMENUFILTERS}', $cadenaGenerada, $this->html);
        }
    }

}

?>
