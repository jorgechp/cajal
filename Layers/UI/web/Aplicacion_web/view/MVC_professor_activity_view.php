<?php
require_once(constant("FOLDER").'/view/MVC_view.php');
/**
 * Description of MVC_professor_activity_view
 *
 * @author jorge
 */
class MVC_professor_activity_view extends MVC_view{
    
    protected function apply_data($data) {
        $dataFilter["i"]="";
        if($data['is_activity_available']){
            $actividad = $data['CONTENT_MAIN']['activity'];
            $activityViewTemplate = file_get_contents(constant("FOLDER").'/view/structure/professor_view_activity/activity_view.html');
            $salidaHtml = $activityViewTemplate;
            $salidaHtml = $this->establecer('{ACTIVITY_NAME}', $actividad->getNombre(), $salidaHtml);
            $salidaHtml = $this->establecer('{ACTIVITY_CODE}', $actividad->getCodigo(), $salidaHtml);
            
            if($data['CONTENT_MAIN']['isCompetencesAvailables']){
                $competenceListHeaderTemplate = file_get_contents(constant("FOLDER").'/view/structure/professor_view_activity/activityCompetenceListHeader.html');
                $competenceListTemplate = file_get_contents(constant("FOLDER").'/view/structure/professor_view_activity/activity_competence_list.html');
                $salidaHtmlListaCompetencias = "";
                if(is_array($data['CONTENT_MAIN']['competences'])){
                    foreach ($data['CONTENT_MAIN']['competences'] as $competencia) {
                        $salidaHtmlListaCompetencias .=  $this->establecer('{COMPETENCE_ID}', $competencia->getIdCompetencia(), $competenceListTemplate);
                        $salidaHtmlListaCompetencias =  $this->establecer('{COMPETENCE_NAME}', $competencia->getName(), $salidaHtmlListaCompetencias);
                        $salidaHtmlListaCompetencias =  $this->establecer('{COMPETENCE_CODE}', $competencia->getCode(), $salidaHtmlListaCompetencias);
                        $salidaHtmlListaCompetencias =  $this->establecer('{ACTIVITY_ID}', $actividad->getIdActividad(), $salidaHtmlListaCompetencias);
                        $salidaHtmlListaCompetencias =  $this->establecer('{IMG_FOLDER}', constant("FOLDER")."/view/images/commons", $salidaHtmlListaCompetencias);

                        switch ($competencia->getIdType()) {

                            case 1:
                                 $salidaHtmlListaCompetencias =  $this->establecer('{COMPETENCE_TYPE}', 'basic', $salidaHtmlListaCompetencias);
                                break;
                            case 2:
                                 $salidaHtmlListaCompetencias =  $this->establecer('{COMPETENCE_TYPE}', 'medium', $salidaHtmlListaCompetencias);
                                break;
                            case 3:
                                 $salidaHtmlListaCompetencias =  $this->establecer('{COMPETENCE_TYPE}', 'hard', $salidaHtmlListaCompetencias);
                                break;                        
                            default:
                                break;
                        }
                    }
                }
                $listaCompetencias = $this->establecer('{COMPETENCE_LIST}', $salidaHtmlListaCompetencias, $competenceListHeaderTemplate);
                $salidaHtml = $this->establecer('{ACTIVITY_COMPETENCES}', $listaCompetencias, $salidaHtml);
                $salidaHtml = $this->establecer('{ACTIVITY_ID}',  $actividad->getIdActividad(), $salidaHtml);
            }else{
                $salidaHtml = $this->establecer('{ACTIVITY_COMPETENCES}', '#NO_COMPETENCES_AVAILABLE#', $salidaHtml);
            }
            
            $this->establecer('{CONTENT_MAIN}', $salidaHtml);
        
        $dataFilter["i"]="";
        $dataFilter["#EVALUATION#"] = ["index.php?activityEval={$actividad->getIdActividad()}","#EVALUATION#",constant("FOLDER")."/view/images/icons/navIcons/seo2.png"];
        $dataFilter["#STUDENT_LIST#"] = ["index.php?students={$actividad->getIdActividad()}","#STUDENT_LIST#",constant("FOLDER")."/view/images/icons/navIcons/crowd.png"];
        $dataFilter["#STUDENT_LIST_EVAL#"] = ["index.php?studentsEval={$actividad->getIdActividad()}","#STUDENT_LIST_EVAL#",constant("FOLDER")."/view/images/icons/navIcons/screen52.png"];
        $dataFilter["j"]="";
        $dataFilter["#STUDENT_LIST_REPORT#"] = ["index.php?evaluationReport={$actividad->getIdActividad()}","#STUDENT_LIST_REPORT#",constant("FOLDER")."/view/images/icons/navIcons/presentation15.png"];
        $dataFilter["#STUDENT_ASSISTANCE#"] = ["index.php?sessionsAsistance={$actividad->getIdActividad()}","#STUDENT_ASSISTANCE#",constant("FOLDER")."/view/images/icons/navIcons/silhouette75.png"];
        $dataFilter["#MANAGE_SESSIONS#"] = ["index.php?sessions={$actividad->getIdActividad()}","#MANAGE_SESSIONS#",constant("FOLDER")."/view/images/icons/navIcons/setting2.png"];
        $dataFilter["#INSERT_STUDENTS#"] = ["index.php?insertStudents&subject={$actividad->getIdActividad()}","#INSERT_STUDENTS#",constant("FOLDER")."/view/images/icons/navIcons/refresh64.png"];
        
            
        }else{
            $this->establecer('{CONTENT_MAIN}', '#NO_COMPETENCES_TO_SHOW#');
        }
        

        
        

        
        $this->apply_navmenu_filters($dataFilter);
    }

}
