<?php

require_once(constant("FOLDER") . '/view/MVC_view_messages.php');

/**
 * Description of MVC_user_contact
 *
 * @author jorge
 */
class MVC_view_user_contact extends MVC_view_messages {

    protected function apply_data($data) {
        $template_tipos = file_get_contents(constant("FOLDER") . '/view/structure/user_contact/user_contact_error_tipos_info.html');
        if ($data['USER_LOGIN']) {
            $template = file_get_contents(constant("FOLDER") . '/view/structure/user_contact/user_contact_form.html');
            $template_error = file_get_contents(constant("FOLDER") . '/view/structure/user_contact/user_contact_error_list.html');
            $salida = $template;
            $salidaError = "";
            if (isset($data['SENT_OK'])) {
                if (!$data['SENT_OK']) {
                    foreach ($data['VALIDATION'] as $key => $value) {
                        if (strcmp($key, 'CONTACT_TYPE_NOTIFICATION') == 0) {
                            $salida = $this->establecer('{' . $key . $value . '}', 'checked="checked"', $salida);
                        }
                        if ($value != false) {
                            $salida = $this->establecer('{' . $key . '}', $value, $salida);
                        } else {
                            if (strcmp($key, 'CONTACT_SUBJECT') == 0) {
                                $salidaError .= $this->establecer('{ERROR}', '#CONTACT_SUBJECT_ERROR#', $template_error);
                            } else if (strcmp($key, 'CONTACT_TEXT') == 0) {
                                $salidaError .= $this->establecer('{ERROR}', '#CONTACT_TEXT_ERROR#', $template_error);
                            } else if (strcmp($key, 'CONTACT_TYPE_NOTIFICATION') == 0) {
                                $salidaError .= $this->establecer('{ERROR}', '#CONTACT_TYPE_NOTIFICATION_ERROR#', $template_error);
                            }
                        }
                    }
                }else{
                    $salida = $this->establecer('{ERROR_LIST}', '#MESSAGE_SENT#', $salida);
                }
            }
            $salida = $this->establecer('{ERROR_LIST}', $salidaError, $salida);
            $this->establecer('{CONTENT_MAIN}', $salida);
        } else {
            $this->establecer('{CONTENT_MAIN}', '#NO_LOGIN_ERROR#');
        }
        $this->establecer('{TIPOS_TITLE}', '#CONTACT_INFO#');
        $this->establecer('{TIPOS}', $template_tipos);
    }

}
