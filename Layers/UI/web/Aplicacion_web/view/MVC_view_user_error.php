<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');
/**
 * Gestiona los mensajes de error aparecidos
 *
 * @author jorge
 */
class MVC_view_user_error extends MVC_view_messages{
    protected function apply_data($data) {
        $template_my_profile = file_get_contents(constant("FOLDER").'/view/structure/user_error/content_main.html');
        
        $salida = $this->establecer('{IMG_FOLDER}', constant("FOLDER").'/view/images/commons',$template_my_profile);
        
        $this->establecer('{CONTENT_MAIN}', $salida);
    }


}
