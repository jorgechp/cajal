<?php
require_once(constant("FOLDER") . '/view/MVC_view.php');

/**
 * Description of MVC_view_professor_competence_Info
 *
 * @author jorge
 */
class MVC_view_user_competence_Info extends MVC_view{
    protected function apply_data($data) {
        $tiposTemplate= file_get_contents(constant("FOLDER").'/view/structure/user_view_competence/tiposTemplate.html');
        $entrada = file_get_contents(constant("FOLDER").'/view/structure/user_view_competence/competenceInfo.html');
        $entrada = $this->establecer('{COMPETENCE_NAME}', $data['COMPETENCE']->getName(),$entrada);        
        if(strlen($data['COMPETENCE']->getObservations()) > 0){
            $entrada = $this->establecer('{COMPETENCE_OBSERVATIONS}', $data['COMPETENCE']->getObservations(),$entrada);
        }else{
            $entrada = $this->establecer('{COMPETENCE_OBSERVATIONS}', '#NO_OBSERVATIONS_AVAILABLE#',$entrada);
        }
     
        if(isset($data['INDICATORS']) && $data['INDICATORS'] != false){
            $lineaIndicador = file_get_contents(constant("FOLDER").'/view/structure/user_view_competence/indicator_list.html');           
            $lineaResultante = "";
            
            
            foreach ($data['INDICATORS'] as $indicador) {  
                $lineaResultante .= $lineaIndicador;    
                $lineaResultante = $this->establecer('{INDICATOR_NAME}', $indicador->getName(), $lineaResultante); 
                $lineaResultante = $this->establecer('{ID_INDICATOR}', $indicador->getIdIndicator(), $lineaResultante); 
                $lineaResultante = $this->establecer('{ID_COMPETENCE}', $indicador->getIdCompetence(), $lineaResultante);
                if(strlen( $indicador->getDescription()) > 0){
                    $lineaResultante = $this->establecer('{INDICATOR_DESCRIPTION}', $indicador->getDescription(), $lineaResultante); 
                }else{
                    $lineaResultante = $this->establecer('{INDICATOR_DESCRIPTION}', '#NO_DESCRIPTION#', $lineaResultante); 
                }
                
            }
            $entrada = $this->establecer('{COMPETENCE_INDICATORS_LIST}', $lineaResultante,$entrada); 
        }else{
             $entrada = $this->establecer('{COMPETENCE_INDICATORS_LIST}', '#NO_INDICATORS_AVAILABLE#',$entrada); 
        }
        
        if(strlen($data['COMPETENCE']->getDescription()) > 0){
            $tiposTemplate = $this->establecer('{COMPETENCE_DESCRIPTION}', $data['COMPETENCE']->getDescription(), $tiposTemplate);
        }else{
            $tiposTemplate = $this->establecer('{COMPETENCE_DESCRIPTION}', '#NO_DESCRIPTION#', $tiposTemplate);
        }
        $this->establecer('{TIPOS}', $tiposTemplate);
        $this->establecer('{TIPOS_TITLE}', '#COMPETENCE_DESCRIPTION#');
        $this->establecer('{CONTENT_MAIN}', $entrada);
        
    }

}
