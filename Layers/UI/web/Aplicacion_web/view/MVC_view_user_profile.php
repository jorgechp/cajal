<?php
require_once(constant("FOLDER") . '/view/MVC_view_messages.php');


/**
 * Description of MVC_USER_PROFILE
 *
 * @author jorge
 */
class MVC_view_user_profile  extends MVC_view_messages{
    protected function apply_data($data) {
        $template = file_get_contents(constant("FOLDER").'/view/structure/user_view_profile/user_profile.html');
        $salidaHtml = $this->establecer('{USER_NAME}', $data['CONTENT_MAIN']->getNombre().' '.$data['CONTENT_MAIN']->getApellido1().' '.$data['CONTENT_MAIN']->getApellido2(), $template);        
        $salidaHtml = $this->establecer('{USER_ID}', $data['CONTENT_MAIN']->getIdUsuario(), $salidaHtml);
        $salidaHtml = $this->establecer('{IMG_FOLDER}', constant("FOLDER").'/view/images/commons', $salidaHtml);
        
        
        
        $this->establecer('{TIPOS_TITLE}', '#USER_PROFILE#');
        $this->establecer('{TIPOS}', '#USER_PROFILE_INFORMATION#');
        $this->establecer('{CONTENT_MAIN}', $salidaHtml);
    }

}
