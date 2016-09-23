<?php

require_once(constant("FOLDER").'/view/MVC_view_messages.php');
/**
 * Description of MVC_view_professorsActivities
 *
 * @author jorge
 */
class MVC_view_adminProfessorsActivities extends MVC_view_messages{
    protected function apply_data($data) {
        
        $tiposTemplate = file_get_contents(constant("FOLDER")."/view/structure/adminProfessorActivities/typesTemplate.html");
        $this->establecer('{TIPOS_TITLE}', '#ADMIN_PROFESSORS_TITLE#');
        $this->establecer('{TIPOS}', $tiposTemplate);
        unset($tiposTemplate);
        
        
        if(!$data['NO_ACTIVITY']){
            $template = file_get_contents(constant("FOLDER")."/view/structure/adminProfessorActivities/contentMain.html");            
            $templateProfessorElement = file_get_contents(constant("FOLDER")."/view/structure/adminProfessorActivities/professorElement.html");
            
            
            $salida = $this->establecer('{ACTIVITY_NAME}', $data['ACTIVITY']->getNombre(), $template);
            
            if(!$data['NO_PROFESSORS']){
                $salidaHtml = "";
              
                foreach ($data['PROFESSORS'] as $professor) {
                    $salidaHtml .= $this->establecer('{PROFESSOR_NAME}', $professor->getNombre().' '.$professor->getApellido1().' '.$professor->getApellido2(), $templateProfessorElement);
                    $salidaHtml = $this->establecer('{PROFESSOR_REALID}', $professor->getDNI(), $salidaHtml);
                    $salidaHtml = $this->establecer('{PROFESSOR_ID}', $professor->getIdUsuario(), $salidaHtml);
                    $salidaHtml = $this->establecer('{ACTIVITY_ID}', $data['ACTIVITY']->getIdActividad(), $salidaHtml);
                    $salidaHtml = $this->establecer('{IMG_FOLDER}', constant('FOLDER'), $salidaHtml);
                    
                }
                
                $salida = $this->establecer('{PROFESSOR_LIST}', $salidaHtml, $salida);
            }
            $salida = $this->establecer('{ACTIVITY_ID}', $data['ACTIVITY']->getIdActividad(), $salida);
            
            //BUSCADOR
            
            if(isset($data['NO_PROFESSORS_FOUND'])){
                $templateProfessorTable = file_get_contents(constant("FOLDER")."/view/structure/adminProfessorActivities/professorTable.html");
                $templateProfessorFoundElement = file_get_contents(constant("FOLDER")."/view/structure/adminProfessorActivities/professorFoundElement.html");
                if(!$data['NO_PROFESSORS_FOUND']){
                    $salidaBuscador = "";
                    foreach ($data['PROFESSORS_FOUND'] as $professorFound) {
                        $salidaBuscador .= $this->establecer('{PROFESSOR_NAME}', $professorFound->getNombre(), $templateProfessorFoundElement);
                        $salidaBuscador = $this->establecer('{PROFESSOR_REALID}', $professorFound->getDNI(), $salidaBuscador);
                        $salidaBuscador = $this->establecer('{ACTIVITY_ID}', $data['ACTIVITY']->getIdActividad(), $salidaBuscador);
                        $salidaBuscador = $this->establecer('{PROFESSOR_ID}', $professorFound->getIdUsuario(), $salidaBuscador);
                        $salidaBuscador = $this->establecer('{IMG_FOLDER}', constant('FOLDER'), $salidaBuscador);
                    }
                    
                    $salidaTablaBuscador = $this->establecer('{PROFESSOR_FOUND_LIST}', $salidaBuscador, $templateProfessorTable);
                    $salida = $this->establecer('{PROFESSOR_SEARCH_LIST}', $salidaTablaBuscador, $salida);
                }else{
                   
                    $salida = $this->establecer('{PROFESSOR_SEARCH_LIST}', '<p>#NO_PROFESSORS_FOUND#</p>', $salida);
                }
                
                $salida = $this->establecer('{VALUE_INPUT_SEARCH}', $data['SEARCH_TEXT'], $salida);
            }
            
            
            
            
            
            
            
            $this->establecer('{CONTENT_MAIN}', $salida);
        }else{
            $this->establecer('{CONTENT_MAIN}', '#NO_ACTIVITY#');
        }
        
              
        
    }

}
