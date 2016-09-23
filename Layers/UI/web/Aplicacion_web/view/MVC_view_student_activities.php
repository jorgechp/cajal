<?php
require_once(constant("FOLDER") . '/view/MVC_view_messages.php');


class MVC_view_student_activities extends MVC_view_messages {
    protected function apply_data($data) {        
        $template_header = file_get_contents(constant("FOLDER")."/view/structure/student_view_activities/student_activities_list_header.html");
        $template_element = file_get_contents(constant("FOLDER")."/view/structure/student_view_activities/student_activities_list_element.html");
        
        $salidaHtml = "";
        $contadorColor = 0;
        $codigoGenerado = "";
        if($data['CONTENT_MAIN']['activities_available']){
            foreach ($data['CONTENT_MAIN']['activities'] as $activity) {
                $salidaHtml .= $this->establecer('{ACTIVITY_NAME}', $activity->getNombre(), $template_element);   
                $salidaHtml = $this->establecer('{ACTIVITY_ID}', $activity->getIdActividad(), $salidaHtml);  
                $salidaHtml = $this->establecer('{ACTIVITY_CODE}', $activity->getCodigo(), $salidaHtml);  
                $salidaHtml = $this->establecer('{IMAGE_FOLDER}', constant("FOLDER")."/view/images/commons", $salidaHtml); 
                $salidaHtml = $this->establecer('{ACTIVITY_DESCRIPTION}', $activity->getDescripcion(), $salidaHtml); 

                switch ($contadorColor) {
                    case 0:
                        $salidaHtml = $this->establecer('{BUTTON_COLOR}', 'GreenButton.png', $salidaHtml); 
                        break;
                    case 1:
                        $salidaHtml = $this->establecer('{BUTTON_COLOR}', 'RedButton.png', $salidaHtml); 
                        break;
                    case 2:
                        $salidaHtml = $this->establecer('{BUTTON_COLOR}', 'GreyButton.png', $salidaHtml); 
                        break;
                    default:
                        break;
                }
                $contadorColor = ($contadorColor + 1) % 3;
            }

            $codigoGenerado = $this->establecer('{ACTIVITIES_LIST}', $salidaHtml, $template_header);
        }else{
            $codigoGenerado = '#NO_ACTIVITIES_TO_SHOW#';
        }
        $this->establecer('{TIPOS_TITLE}', '#ACTIVITIES_TITLE#');
        $this->apply_help_context('#ACTIVITIES#', '#ACTIVITIES_INFO_STUDENT#');        
        $this->establecer('{CONTENT_MAIN}', $codigoGenerado);
        
    }

}
