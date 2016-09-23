<?php
require_once(constant("FOLDER") . '/view/MVC_view_messages.php');

/**
 * Description of MVC_view_user_my_profile
 *
 * @author jorge
 */
class MVC_view_user_my_profile extends MVC_view_messages{
    protected function apply_data($data) {
        $template_my_profile = file_get_contents(constant("FOLDER").'/view/structure/user_view_my_profile/content.html');
        $template_types = file_get_contents(constant("FOLDER").'/view/structure/user_view_my_profile/typos_content.html');        
        if($data['IS_USER_VALID']){
            $user = $data['CONTENT_MAIN'];
            $template_role = file_get_contents(constant("FOLDER").'/view/structure/user_view_my_profile/role_link.html'); 

            
            
            $salidaHtml = $this->establecer('{NAME}', $user->getNombre(), $template_my_profile);
            $salidaHtml = $this->establecer('{LASTNAME1}', $user->getApellido1(), $salidaHtml);
            $salidaHtml = $this->establecer('{LASTNAME2}', $user->getApellido2(), $salidaHtml);
            $salidaHtml = $this->establecer('{MAIL}', $user->getMail(), $salidaHtml);
            $salidaHtml = $this->establecer('{REALID}', $user->getDNI(), $salidaHtml);
            $salidaHtml = $this->establecer('{ROOT_FOLDER}', '/', $salidaHtml);            
            
            $rolEstudiante = null;
            $rolProfesor = null;
            $rolAdministrador = null;
            
            if($data['IS_STUDENT'] && 2 !=  $data['USER_CURRENT_ROLE']){
                $rolEstudiante = $this->establecer('{ROLE_NUMBER}', 1, $template_role);
                $rolEstudiante = $this->establecer('{ROLE_NAME}', 'STUDENT', $rolEstudiante);
            }
            if($data['IS_PROFESSOR'] && 3 !=  $data['USER_CURRENT_ROLE']){
                $rolProfesor = $this->establecer('{ROLE_NUMBER}', 2, $template_role);
                $rolProfesor = $this->establecer('{ROLE_NAME}', 'PROFESSOR', $rolProfesor);
            }
            if($data['IS_ADMIN'] && 4 !=  $data['USER_CURRENT_ROLE']){
                $rolAdministrador = $this->establecer('{ROLE_NUMBER}', 3, $template_role);
                $rolAdministrador = $this->establecer('{ROLE_NAME}', 'ADMIN', $rolAdministrador);
            }            
            
            $salidaHtml = $this->establecer('{ROLE_LINK_STUDENT}', $rolEstudiante, $salidaHtml);
            $salidaHtml = $this->establecer('{ROLE_LINK_PROFESSOR}', $rolProfesor, $salidaHtml);
            $salidaHtml = $this->establecer('{ROLE_LINK_ADMIN}', $rolAdministrador, $salidaHtml);
            
            if(strlen($user->getImagenPerfil()) > 0){
                $salidaHtml = $this->establecer('{IMG_NAME}',$user->getImagenPerfil(), $salidaHtml);
            }else{
                $salidaHtml = $this->establecer('{IMG_NAME}','0', $salidaHtml);
            }

            //Comprobación del resultado de modificar una contraseña
            if($data['CHANGE_PASSWORD']){
                if(isset($data['INVALID_PASSWORD']) && $data['INVALID_PASSWORD']){
                    $salidaHtml = $this->establecer('{CONTENT_INFO}', '#INVALID_PASSWORD#', $salidaHtml);
                }
                if(isset($data['PASSWORD_ERROR']) && $data['PASSWORD_ERROR']){
                    $salidaHtml = $this->establecer('{CONTENT_INFO}', '#PASSWORD_ERROR#', $salidaHtml);
                }else{
                    $salidaHtml = $this->establecer('{CONTENT_INFO}', '#PASSWORD_CHANGED#', $salidaHtml);
                }
            }
            
            if($data['UPLOAD_PHOTO']){
                if(isset($data['UPLOAD_PHOTO_ERROR']) && $data['UPLOAD_PHOTO_ERROR']){
                    $salidaHtml = $this->establecer('{CONTENT_INFO}', '#IMAGE_ERROR_MESSAGE#'.$data['UPLOAD_PHOTO_ERROR_MESSAGE'], $salidaHtml);
                }
                if(isset($data['UPLOAD_PHOTO_ERROR_TYPE']) && $data['UPLOAD_PHOTO_ERROR_TYPE']){
                    $salidaHtml = $this->establecer('{CONTENT_INFO}', '#IMAGE_ERROR_TYPE#', $salidaHtml);
                }
                if(isset($data['UPLOAD_PHOTO_REMOVED']) && $data['UPLOAD_PHOTO_REMOVED']){
                    $salidaHtml = $this->establecer('{CONTENT_INFO}', '#IMAGE_REMOVED#', $salidaHtml);
                }                
                if(isset($data['UPLOAD_PHOTO_RESULT']) && $data['UPLOAD_PHOTO_RESULT']){
                    $salidaHtml = $this->establecer('{CONTENT_INFO}', '#UPLOAD_PHOTO_RESULT#', $salidaHtml);
                }
            }
            
            $this->establecer('{CONTENT_MAIN}', $salidaHtml);
        }else{
           $this->establecer('{CONTENT_MAIN}', '#NOT_ALLOWED#'); 
        }
        $this->establecer('{TIPOS}', $template_types);
        $this->establecer('{TIPOS_TITLE}', '#YOUR_DATA#');
        
    }

}
