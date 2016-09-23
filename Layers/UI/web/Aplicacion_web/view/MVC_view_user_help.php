<?php

require_once(constant("FOLDER") . '/view/MVC_view_messages.php');

/**
 * Gestiona la presentación de la ayuda al usuario
 *
 * @author jorge
 */
class MVC_view_user_help extends MVC_view_messages {

    protected function apply_data($data) {
        $templates_help = file_get_contents(constant("FOLDER") . '/view/structure/user_help/content_main.html');
        $template_types = file_get_contents(constant("FOLDER") . "/view/structure/user_help/types_{$data['USER_ROL']}.html");
        $template_content = null;
        $salida = null;
        
        switch ($data['CONTENT_MAIN']) {
            
            //ANÓNIMO
            case 0:
                $salida = $this->establecer('{HELP_TITLE}', '#HELP_COMMON_TITLE#', $templates_help);
                $salida = $this->establecer('{HELP_DESCRIPTION}', '#HELP_DESCRIPTION_COMMON#', $salida);
                break;
            case 1:
                $template_content = file_get_contents(constant("FOLDER") . "/view/structure/user_help/templates/login.html");
                $salida = $this->establecer('{HELP_TITLE}', '#HELP_LOGIN_TITLE#', $templates_help);
                break;
            case 2:
                $template_content = file_get_contents(constant("FOLDER") . "/view/structure/user_help/templates/register.html");
                $salida = $this->establecer('{HELP_TITLE}', '#HELP_REGISTER_TITLE#', $templates_help);
                break;
            
            
            //ESTUDIANTES
            case 3:
                $template_content = file_get_contents(constant("FOLDER") . "/view/structure/user_help/templates/studentCompetences.html");
                $salida = $this->establecer('{HELP_TITLE}', '#HELP_COMPETENCES_TITLE#', $templates_help);
                break;
            case 4:
                $template_content = file_get_contents(constant("FOLDER") . "/view/structure/user_help/templates/studentActivities.html");
                $salida = $this->establecer('{HELP_TITLE}', '#HELP_ACTIVITIES_TITLE#', $templates_help);                
                break;
            
            //PROFESORES
            case 5:
                $template_content = file_get_contents(constant("FOLDER") . "/view/structure/user_help/templates/professorActivities.html");
                $salida = $this->establecer('{HELP_TITLE}', '#HELP_PROFESSORACTIVITIES_TITLE#', $templates_help);                
                break;
            case 6:
                $template_content = file_get_contents(constant("FOLDER") . "/view/structure/user_help/templates/professorCompetences.html");
                $salida = $this->establecer('{HELP_TITLE}', '#HELP_PROFESSORCOMPETENCES_TITLE#', $templates_help);
                
                break;
            
            
            //ADMINISTRADORES
            case 7:
                $template_content = file_get_contents(constant("FOLDER") . "/view/structure/user_help/templates/administration.html");
                $salida = $this->establecer('{HELP_TITLE}', '#HELP_ADMINISTRATION_TITLE#', $templates_help);                
                break;
            case 8:
                $salida = $this->establecer('{HELP_TITLE}', '#HELP_ADMINCOMPETENCES_TITLE#', $templates_help);
                $salida = $this->establecer('{HELP_DESCRIPTION}', '#HELP_DESCRIPTION_ADMINCOMPETENCES#', $salida);
                break;
            case 9:
                $salida = $this->establecer('{HELP_TITLE}', '#HELP_ADMINACTIVITIES_TITLE#', $templates_help);
                $salida = $this->establecer('{HELP_DESCRIPTION}', '#HELP_DESCRIPTION_ADMINACTIVITIES#', $salida);
                break;
            case 10:
                $salida = $this->establecer('{HELP_TITLE}', '#HELP_ADMINUSERS_TITLE#', $templates_help);
                $salida = $this->establecer('{HELP_DESCRIPTION}', '#HELP_DESCRIPTION_ADMINUSERS#', $salida);
                break;
            
            
            //COMUNES
            case 11:
                $template_content = file_get_contents(constant("FOLDER") . "/view/structure/user_help/templates/contact.html");
                $salida = $this->establecer('{HELP_TITLE}', '#HELP_CONTACT_TITLE#', $templates_help);
                break;
            case 12:
                $template_content = file_get_contents(constant("FOLDER") . "/view/structure/user_help/templates/messages.html");
                $salida = $this->establecer('{HELP_TITLE}', '#HELP_MESSAGES_TITLE#', $templates_help);
                
                break;
            default:
                break;
        }

        $salida = $this->establecer('{HELP_DESCRIPTION}', $template_content, $salida);


        //Se procesan determinadas etiquetas comunes



        $this->establecer('{CONTENT_MAIN}', $salida);
        $this->establecer('{TIPOS_TITLE}', '#HELP#');
        $this->establecer('{TIPOS}', $template_types);
    }

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


        //Añadido a la funcionalidad original de la clase.
        $this->html = $this->establecer('{BIG_IMG_FOLDER}', constant("FOLDER") . '/view/images/big_images', $this->html);
        $this->html = $this->establecer('{IMG_FOLDER}', constant("FOLDER") . '/view/images/commons', $this->html);
        //Elimina los tag de sistema que no han sido eliminados
        $this->html = preg_replace('/{.*}/', '', $this->html);
        print $this->html;
    }

}
