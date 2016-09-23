<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');

/**
 * Description of MVC_view_competenceInfo
 *
 * @author jorge
 */
class MVC_view_competenceInfo extends MVC_view_messages{
    
    protected function apply_data($data) {    
        $tiposTemplate= file_get_contents(constant("FOLDER").'/view/structure/competences/tiposTemplate.html');
        $entrada = file_get_contents(constant("FOLDER").'/view/structure/competences/competenceInfo.html');
        if($data['IS_COMPETENCE']){
            
            $entrada = $this->establecer('{COMPETENCE_NAME}', $data['CONTENT_MAIN']['name'],$entrada); 
            $entrada = $this->establecer('{COMPETENCE_CODE}', $data['CONTENT_MAIN']['code'],$entrada); 
            if(isset($data['CONTENT_MAIN']['observations'])){
                $entrada = $this->establecer('{COMPETENCE_OBSERVATIONS}', $data['CONTENT_MAIN']['observations'],$entrada);
            }else{
                $entrada = $this->establecer('{COMPETENCE_OBSERVATIONS}', '#NO_OBSERVATIONS_AVAILABLE#',$entrada);
            }
            $entrada = $this->establecer('{COURSE}', $data['CONTENT_MAIN']['year'],$entrada);

            if(isset($data['CONTENT_MAIN']['indicators'])){
                $lineaIndicador = file_get_contents(constant("FOLDER").'/view/structure/competences/indicator_list.html');           
                $lineaResultante = "";

                foreach ($data['CONTENT_MAIN']['indicators'] as $datosIndicador) {  
                    $lineaResultante .= $lineaIndicador;    
                    $lineaResultante = $this->establecer('{INDICATOR_CODE}', $datosIndicador['code'], $lineaResultante); 
                    $lineaResultante = $this->establecer('{INDICATOR_NAME}', $datosIndicador['name'], $lineaResultante); 
                    $lineaResultante = $this->establecer('{ID_INDICATOR}', $datosIndicador['id'], $lineaResultante); 
                    $lineaResultante = $this->establecer('{ID_COMPETENCE}', $data['CONTENT_MAIN']['id'], $lineaResultante); 
                }
                $entrada = $this->establecer('{COMPETENCE_INDICATORS_LIST}', $lineaResultante,$entrada); 
            }else{
                 $entrada = $this->establecer('{COMPETENCE_INDICATORS_LIST}', '#NO_INDICATORS_AVAILABLE#',$entrada); 
            }

            if(strlen($data['CONTENT_MAIN']['description']) > 0){
                $tiposTemplate = $this->establecer('{COMPETENCE_DESCRIPTION}', $data['CONTENT_MAIN']['description'], $tiposTemplate);
            }else{
                $tiposTemplate = $this->establecer('{COMPETENCE_DESCRIPTION}', '#NO_DESCRIPTION_AVAILABLE#', $tiposTemplate);
            }
        }else{
            $entrada = $this->establecer('{COMPETENCE_NAME}', '#ERROR_WARNING#',$entrada); 
            $entrada = '#NO_COMPETENCE#'; 
        }
        $this->establecer('{TIPOS}', $tiposTemplate);
        $this->establecer('{TIPOS_TITLE}', '#COMPETENCE_DESCRIPTION#');
        $this->establecer('{CONTENT_MAIN}', $entrada);
        
        
    }
    
    protected function apply_error($data) {
        if(isset($data['CONTENT_ERROR'])){
            $this->establecer('{CONTENT_MAIN}', $data['CONTENT_ERROR']);
        }
    }


    
}
