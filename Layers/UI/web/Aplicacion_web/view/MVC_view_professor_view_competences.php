<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');

/**
 * Description of MVC_view_professor_view_competences
 *
 * @author jorge
 */
class MVC_view_professor_view_competences extends MVC_view_messages{
    protected function apply_data($data) {
        $tiposTemplate = file_get_contents(constant("FOLDER") . '/view/structure/professor_view_competences/professor_view_competence_tipos_info.html');
        $this->establecer('{TIPOS_TITLE}', '#COMPETENCE_TABLE#');
        $this->establecer('{TIPOS}', $tiposTemplate);
        if($data['NO_ACTIVITIES']){
            $this->establecer('{CONTENT_MAIN}', '#NOT_ENOUGH_ACTIVITES#');
            return;
        }else{
            //Sección general
            $sectionTemplate = file_get_contents(constant("FOLDER") . '/view/structure/professor_view_competences/professor_view_competence_header.html');
            $sectionTableRow = file_get_contents(constant("FOLDER") . '/view/structure/professor_view_competences/professor_view_competence_activity_element.html');
            $sectionTableCompetenceList = file_get_contents(constant("FOLDER") . '/view/structure/professor_view_competences/professor_view_competence_activity_competence_list_element.html');
            $sectionTableCompetenceIndicatorRow = file_get_contents(constant("FOLDER") . '/view/structure/professor_view_competences/professor_view_competence_activity_indicator_row.html');
            
            $salidaFinal = "";
            
            $salidaActividad = "";
            foreach ($data['activities'] as $activity) {
                $salidaActividad .= $this->establecer('{ACTIVITY_NAME}', $activity->getNombre(), $sectionTableRow);
                //Hay que rellenar una lista de competencias
                
                $salidaCompetencias = "";
                if(is_array($data['competences'][$activity->getIdActividad()])){
                    foreach ($data['competences'][$activity->getIdActividad()] as $competencia) {
                        $salidaCompetencias .= $this->establecer('{COMPETENCE_NAME}', $competencia->getName(), $sectionTableCompetenceList);
                        $salidaCompetencias = $this->establecer('{COMPETENCE_ID}', $competencia->getIdCompetencia(), $salidaCompetencias);
                        $salidaCompetencias = $this->establecer('{COMPETENCE_CODE}', $competencia->getCode(), $salidaCompetencias);

                        $salidaIndicadores = "";
                        if(is_array($data['indicators'][$competencia->getIdCompetencia()] )){
                            foreach ($data['indicators'][$competencia->getIdCompetencia()] as $indicador) {                           
                                $salidaIndicadores .= $this->establecer('{INDICATOR_NAME}', $indicador->getName(), $sectionTableCompetenceIndicatorRow);
                                $salidaIndicadores = $this->establecer('{INDICATOR_CODE}', $indicador->getCode(), $salidaIndicadores);
                            }
                        }
                        $salidaCompetencias = $this->establecer('{INDICATOR_LIST}', $salidaIndicadores, $salidaCompetencias);
                    }
                }
                
                
                $salidaActividad = $this->establecer('{COMPETENCE_LIST}', $salidaCompetencias, $salidaActividad);
            }
            
            $salidaFinal = $this->establecer('{ACTIVITY_ELEMENT}', $salidaActividad, $sectionTemplate);
            

            $this->establecer('{CONTENT_MAIN}', $salidaFinal);
            
        }
    }

}
