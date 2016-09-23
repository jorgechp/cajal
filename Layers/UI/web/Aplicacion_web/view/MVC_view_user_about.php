<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');

/**
 * Description of MVC_view_admin_view_only
 *
 * @author jorge
 */
class MVC_view_user_about  extends MVC_view_messages{
    protected function apply_data($data) {
        $templateAbout = file_get_contents(constant("FOLDER").'/view/structure/user_about/content_main.html');
        $salidaAbout = "";
        $salidaAbout = $this->establecer('{IMG_FOLDER}', constant("FOLDER").'/view/images/commons', $templateAbout);    
        $this->establecer('{CONTENT_MAIN}', $salidaAbout);
    }

}
