<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');

/**
 * Description of MVC_view_admin_view_only
 *
 * @author jorge
 */
class MVC_view_admin  extends MVC_view_messages{
    protected function apply_data($data) {
        
        if(isset($data['CONTENT_MAIN']['notifications']) && count($data['CONTENT_MAIN']['notifications']) > 0){  
            $templateTableHeader = file_get_contents(constant("FOLDER").'/view/structure/admin_view_notifications/notificationsContent.html');
            $templateTableCell = file_get_contents(constant("FOLDER").'/view/structure/admin_view_notifications/element.html');
            $typesInfo = file_get_contents(constant("FOLDER").'/view/structure/admin_view_notifications/typesInfo.html');
            
            $salidaNotification = "";
            
            if($data['CONTENT_MAIN']['notifications'] != false){
                foreach ($data['CONTENT_MAIN']['notifications'] as $notification) {

                    $salidaNotification .= $this->establecer('{ASUNTO}', $notification->getSubject(), $templateTableCell);
                    $salidaNotification = $this->establecer('{MESSAGE}', $notification->getText(), $salidaNotification);

                    $salidaNotification = $this->establecer('{DATE}', $notification->getDate(), $salidaNotification);
                    $salidaNotification = $this->establecer('{ID_USER}', $notification->getIdSender(), $salidaNotification);
                    $salidaNotification = $this->establecer('{USER_NAME}', $data['CONTENT_MAIN']['users'][$notification->getIdSender()], $salidaNotification);
                    $type = null;
                    if($notification->getIsAnError()){
                        $type = '#ERROR#';
                    }else{
                        $type = '#OTHER#';
                    }
                    $salidaNotification = $this->establecer('{TYPE}', $type, $salidaNotification);
                    $salidaNotification = $this->establecer('{STATUS}', ($notification->getStatus()==0)?'<img src="{IMG_FOLDER}/Red_triangle_alert_icon.png" alt="#NO_SOLVED#"/>':'<img src="{IMG_FOLDER}/approve_icon.png" alt="#SOLVED#"/>', $salidaNotification);
                    $salidaNotification = $this->establecer('{SOLVED}', ($notification->getStatus()==0)?'<img src="{IMG_FOLDER}/approve_icon.png" alt="#DOSOLVED#"/>':'<img src="{IMG_FOLDER}/Red_triangle_alert_icon.png" alt="#UNSOLVED#"/>', $salidaNotification);
                    $salidaNotification = $this->establecer('{REPORT_ID}', $notification->getIdReport(), $salidaNotification);    
                    $salidaNotification = $this->establecer('{IMG_FOLDER}', constant("FOLDER").'/view/images/commons', $salidaNotification);    
                }
            }else{
                 $salidaNotification = "#NO_NOTIFICATIONS#";
            }
            
            $salida = $this->establecer('{REPORT_ELEMENT}', $salidaNotification, $templateTableHeader);
            $this->establecer('{TIPOS_TITLE}', '#NOTIFICATION_TITLE#');
            $this->establecer('{TIPOS}', $typesInfo);
            $this->establecer('{CONTENT_MAIN}', $salida);
            
        }
        $this->establecer('{TIPOS}', null);
    }

}
