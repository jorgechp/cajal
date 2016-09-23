<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');
/**
 * Description of MVC_view_competencesIndicator
 *
 * @author jorge
 */
class MVC_view_admin_competencesIndicator extends MVC_view_messages {
    protected function apply_data($data) {
        $tiposTemplate = file_get_contents(constant("FOLDER")."/view/structure/adminCompetenceActivities/typesTemplate.html");
        $this->establecer('{TIPOS_TITLE}', '#ADMIN_COMPETENCES_INDICATORS_TITLE#');
        $this->establecer('{TIPOS}', $tiposTemplate);
        unset($tiposTemplate);
        

        
        if(!$data['NO_COMPETENCE']){
            $template = file_get_contents(constant("FOLDER")."/view/structure/adminCompetencesIndicators/contentMain.html");            
            $templateIndicatorElement = file_get_contents(constant("FOLDER")."/view/structure/adminCompetencesIndicators/indicatorElement.html");
            
            
            $salida = $this->establecer('{COMPETENCE_NAME}', $data['COMPETENCE']->getName(), $template);
            
            if(!$data['NO_INDICATORS']){
                $salidaHtml = "";
                foreach ($data['INDICATORS'] as $indicator) {
                    $salidaHtml .= $this->establecer('{INDICATOR_NAME}', $indicator->getName(), $templateIndicatorElement);
                    $salidaHtml = $this->establecer('{INDICATOR_CODE}', $indicator->getCode(), $salidaHtml);
                    $salidaHtml = $this->establecer('{INDICATOR_DESCRIPTION}', $indicator->getDescription(), $salidaHtml);
                    $salidaHtml = $this->establecer('{COMPETENCE_ID}', $data['COMPETENCE']->getIdCompetencia(), $salidaHtml);
                    $salidaHtml = $this->establecer('{INDICATOR_ID}', $indicator->getIdIndicator(), $salidaHtml);
                    $salidaHtml = $this->establecer('{IMG_FOLDER}', constant('FOLDER'), $salidaHtml);
                    
                }
                
                $salida = $this->establecer('{INDICATOR_LIST}', $salidaHtml, $salida);
            }
            $salida = $this->establecer('{COMPETENCE_ID}', $data['COMPETENCE']->getIdCompetencia(), $salida);
            
            if(isset($data['REMOVE_RESULT']) && $data['REMOVE_RESULT'] == false){
                $salida = $this->establecer('{RESULT}', '#CANT_DELETE_INDICATOR#', $salida);
            }
            
            $this->establecer('{CONTENT_MAIN}', $salida);
        }else{
            $this->establecer('{CONTENT_MAIN}', '#NO_COMPETENCE#');
        }
    }

}
