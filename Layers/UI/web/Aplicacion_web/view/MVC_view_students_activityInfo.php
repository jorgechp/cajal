<?php
require_once(constant("FOLDER") . '/view/MVC_view.php');


/**
 * Description of MVC_view_students_activityInfo
 *
 * @author jorge
 */
class MVC_view_students_activityInfo extends MVC_view {
    protected function apply_data($data) {
        if(!$data['CONTENT_MAIN']['activity']){
            $this->establecer('{CONTENT_MAIN}', '#NO_ACTIVITY_INFORMATION#');
        }else{
            $templateActivity = file_get_contents(constant("FOLDER")."/view/structure/student_view_activity/student_view_activity_header.html");
            $templateCompetence = file_get_contents(constant("FOLDER")."/view/structure/student_view_activity/student_view_activity_competenceElement.html");
            
            $templateProfessorElement = file_get_contents(constant("FOLDER")."/view/structure/student_view_activity/activityProfessorList.html");
            
            
            $salidaCompetencias = "";
            $salidaProfessorsHtml = "";
            
            
            $salidaHtml = $this->establecer('{ACTIVITY_NAME}', $data['CONTENT_MAIN']['activity']->getNombre(), $templateActivity);
            $salidaHtml = $this->establecer('{ACTIVITY_ID}', $data['CONTENT_MAIN']['activity']->getIdActividad(), $salidaHtml);
            
           
            
            
            if($data['CONTENT_MAIN']['professors'] != false){
                foreach ($data['CONTENT_MAIN']['professors'] as $professor) {                
                    $salidaProfessorsHtml .= $this->establecer('{PROFESSOR_ID}', $professor->getIdUsuario(), $templateProfessorElement);
                    $salidaProfessorsHtml = $this->establecer('{IMAGE_FOLDER}', constant("FOLDER")."/view/images/commons/", $salidaProfessorsHtml);
                    $salidaProfessorsHtml = $this->establecer('{PROFESSOR_NAME}', $professor->getNombre().' '.$professor->getApellido1().' '.$professor->getApellido2(), $salidaProfessorsHtml);

                }
            }
            
            if(isset($data['CONTENT_MAIN']['competence'])){
                foreach ($data['CONTENT_MAIN']['competence'] as $competencia) {
                    $salidaCompetencias .= $this->establecer('{IMG_FOLDER}', constant("FOLDER")."/view/images/commons", $templateCompetence);
                    $salidaCompetencias = $this->establecer('{COMPETENCE_ID}', $competencia->getIdCompetencia(), $salidaCompetencias);
                    $salidaCompetencias = $this->establecer('{COMPETENCE_NAME}', $competencia->getName(), $salidaCompetencias);
                    $salidaCompetencias = $this->establecer('{COMPETENCE_CODE}', $competencia->getCode(), $salidaCompetencias);
                    
                    switch ($competencia->getIdType()) {
                        case 1:
                            $salidaCompetencias = $this->establecer('{COMPETENCE_TYPE}', 'basic_competence',$salidaCompetencias);
                            break;
                        case 2:
                            $salidaCompetencias = $this->establecer('{COMPETENCE_TYPE}', 'medium_competence',$salidaCompetencias);
                            break;
                        case 3:
                            $salidaCompetencias = $this->establecer('{COMPETENCE_TYPE}', 'hard_competence',$salidaCompetencias);
                            break;
                        default:
                            break;
                    }
                }
            }
           
            $salidaHtml = $this->establecer('{COMPETENCES_LIST}', $salidaCompetencias, $salidaHtml);
            $salidaHtml = $this->establecer('{PROFESSORS_LIST}', $salidaProfessorsHtml, $salidaHtml);
            
            $dataFilter["i"]="";
            $dataFilter["#CHECK_SESSIONS#"] = ["index.php?activityAssistance={$data['CONTENT_MAIN']['activity']->getIdActividad()}"
              ,"#CHECK_SESSIONS#",constant("FOLDER")."/view/images/icons/navIcons/seo2.png"];

            
            $this->establecer('{TIPOS_TITLE}', '#ACTIVITY_DESCRIPTION#');            
            $this->apply_help_context('#INFO_ACTIVITY_TITLE#', '#INFO_ACTIVITY_CONTENT#');
            $this->apply_navmenu_filters($dataFilter);            
            $this->establecer('{CONTENT_MAIN}', $salidaHtml);
        }
    }

}
