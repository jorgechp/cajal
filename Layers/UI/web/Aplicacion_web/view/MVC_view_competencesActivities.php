<?php

require_once(constant("FOLDER").'/view/MVC_view_messages.php');
/**
 * Description of MVC_view_competencesActivities
 *
 * @author jorge
 */
class MVC_view_competencesActivities extends MVC_view_messages{
    protected function apply_data($data) {
        $tiposTemplate = file_get_contents(constant("FOLDER")."/view/structure/adminCompetenceActivities/typesTemplate.html");
        $this->establecer('{TIPOS_TITLE}', '#ADMIN_COMPETENCES_ACTIVITIES_TITLE#');
        $this->establecer('{TIPOS}', $tiposTemplate);
        unset($tiposTemplate);
        
        
        if(!$data['NO_ACTIVITY']){
            $template = file_get_contents(constant("FOLDER")."/view/structure/adminCompetenceActivities/contentMain.html");            
            $templateCompetenceElement = file_get_contents(constant("FOLDER")."/view/structure/adminCompetenceActivities/competenceElement.html");
            
            
            $salida = $this->establecer('{ACTIVITY_NAME}', $data['ACTIVITY']->getNombre(), $template);
            
            if(!$data['NO_COMPETENCES']){
                $salidaHtml = "";
                foreach ($data['COMPETENCES'] as $competence) {
                    $salidaHtml .= $this->establecer('{COMPETENCE_NAME}', $competence->getName(), $templateCompetenceElement);
                    $salidaHtml = $this->establecer('{COMPETENCE_DESCRIPTION}', $competence->getDescription(), $salidaHtml);
                    $salidaHtml = $this->establecer('{COMPETENCE_ID}', $competence->getIdCompetencia(), $salidaHtml);
                    $salidaHtml = $this->establecer('{ACTIVITY_ID}', $data['ACTIVITY']->getIdActividad(), $salidaHtml);
                    $salidaHtml = $this->establecer('{IMG_FOLDER}', constant('FOLDER'), $salidaHtml);
                }
                
                $salida = $this->establecer('{COMPETENCE_LIST}', $salidaHtml, $salida);
            }
            $salida = $this->establecer('{ACTIVITY_ID}', $data['ACTIVITY']->getIdActividad(), $salida);
            
            //BUSCADOR
            
            if(isset($data['NO_COMPETENCES_FOUND'])){
                $templateCompetenceTable = file_get_contents(constant("FOLDER")."/view/structure/adminCompetenceActivities/competenceTable.html");
                $templateCompetenceFoundElement = file_get_contents(constant("FOLDER")."/view/structure/adminCompetenceActivities/competenceFoundElement.html");
                if(!$data['NO_COMPETENCES_FOUND']){
                    $salidaBuscador = "";
                    foreach ($data['COMPETENCES_FOUND'] as $competenceFound) {
                        $salidaBuscador .= $this->establecer('{COMPETENCE_NAME}', $competenceFound->getName(), $templateCompetenceFoundElement);
                        $salidaBuscador = $this->establecer('{COMPETENCE_DESCRIPTION}', $competenceFound->getDescription(), $salidaBuscador);
                        $salidaBuscador = $this->establecer('{ACTIVITY_ID}', $data['ACTIVITY']->getIdActividad(), $salidaBuscador);
                        $salidaBuscador = $this->establecer('{COMPETENCE_ID}', $competenceFound->getIdCompetencia(), $salidaBuscador);
                        $salidaBuscador = $this->establecer('{IMG_FOLDER}', constant('FOLDER'), $salidaBuscador);
                    }
                    
                    $salidaTablaBuscador = $this->establecer('{COMPETENCE_FOUND_LIST}', $salidaBuscador, $templateCompetenceTable);
                    $salida = $this->establecer('{COMPETENCE_SEARCH_LIST}', $salidaTablaBuscador, $salida);
                }else{
                   
                    $salida = $this->establecer('{COMPETENCE_SEARCH_LIST}', '<p>#NO_COMPETENCES_FOUND#</p>', $salida);
                }
                
                $salida = $this->establecer('{VALUE_INPUT_SEARCH}', $data['SEARCH_TEXT'], $salida);
            }
            
            
            
            
            
            
            
            $this->establecer('{CONTENT_MAIN}', $salida);
        }else{
            $this->establecer('{CONTENT_MAIN}', '#NO_ACTIVITY#');
        }
    }

}
