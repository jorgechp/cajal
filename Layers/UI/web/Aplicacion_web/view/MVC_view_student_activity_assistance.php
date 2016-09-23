<?php
require_once(constant("FOLDER") . '/view/MVC_view_messages.php');
/**
 * Description of MVC_view_student_activity_assistance
 *
 * @author jorge
 */
class MVC_view_student_activity_assistance extends MVC_view_messages {
    protected function apply_data($data) {
        
        if($data['SESSIONS'] != false){
            $template = file_get_contents(constant("FOLDER")."/view/structure/student_view_assistance/student_view_assistance_header.html");
            $templateTableElement = file_get_contents(constant("FOLDER")."/view/structure/student_view_assistance/student_view_assistance_table_element.html");
            $templateTipos = file_get_contents(constant("FOLDER")."/view/structure/student_view_assistance/types.html");
            $templateProfessorElement = file_get_contents(constant("FOLDER")."/view/structure/student_view_assistance/activityProfessorList.html");
            
            $salidaHtml = "";
            $salidaTipos = $templateTipos;
            $salidaElementos = "";
            $contador = 0;
            $dateFormatedStart = null;
            $dateFormatedEnd = null;
            $salidaProfessorsHtml = "";
            
            foreach ($data['SESSIONS'] as $session) {
                $dateFormatedStart = date_create($session->getDateStart());
                $dateFormatedEnd = date_create($session->getDateEnd());
                
                $salidaElementos.= $this->establecer('{ACTIVITY_DATE_START}', $dateFormatedStart->format($GLOBALS["SYSTEM_LONG_TIMEFORMAT"]), $templateTableElement);
                $salidaElementos = $this->establecer('{ACTIVITY_DATE_END}', $dateFormatedEnd->format($GLOBALS["SYSTEM_LONG_TIMEFORMAT"]), $salidaElementos);                
                $salidaElementos = $this->establecer('{IMG_FOLDER}', constant("FOLDER")."/view/images/commons", $salidaElementos);
                if($data['SESSIONS_ASSISTANCE']['isAssisted'][$contador]){
                    $salidaElementos = $this->establecer('{IS_ASSISTED}', "checked", $salidaElementos);
                }else{
                    $salidaElementos = $this->establecer('{IS_ASSISTED}', "unchecked", $salidaElementos);
                }
                
                ++$contador;
            }
            
            $salidaHtml = $this->establecer('{TABLE}', $salidaElementos, $template);
            $salidaHtml = $this->establecer('{ACTIVITY_ID}', $data['ACTIVITY_ID'] , $salidaHtml); 
            $salidaHtml = $this->establecer('{ACTIVITY_NAME}', $data['ACTIVITY_NAME'] , $salidaHtml); 
            
            if(isset($data['CHECK_TRY']) && $data['CHECK_TRY']){
                if($data['TRY']){
                    $salidaHtml = $this->establecer('{ACTIVITY_ASSISTANCE_RESULT}', '#ASSISTANCE_REGISTERED#', $salidaHtml);
                }else{
                   $salidaHtml = $this->establecer('{ACTIVITY_ASSISTANCE_RESULT}', '#NO_ASSISTANCE_REGISTERED#', $salidaHtml);
                }
            }
            

            
            if(isset($data['CURRENT_SESSIONS']) && count($data['CURRENT_SESSIONS']) > 0){
                $templateFormSession = file_get_contents(constant("FOLDER")."/view/structure/student_view_assistance/student_view_assistance_check.html");
                
                $salidaForms = "";
                foreach ($data['CURRENT_SESSIONS'] as $session) {
                    $salidaForms .= $this->establecer('{ACTIVITY_ID}', $data['ACTIVITY_ID'], $templateFormSession);
                    $salidaForms = $this->establecer('{SESSION_ID}', $session->getIdSession(), $salidaForms);      
                    $salidaForms = $this->establecer('{SESSION_DATE_START}', $session->getDateStart(), $salidaForms);
                }
                $salidaHtml = $this->establecer('{ACTIVITY_ASSISTANCE}', $salidaForms , $salidaHtml);
               
            }else{
                $salidaHtml = $this->establecer('{ACTIVITY_ASSISTANCE}', '#NO_SESSIONS_AVAILABLE#' , $salidaHtml);
            }
            
            if($data['CONTENT_MAIN']['professors'] != false){
                foreach ($data['CONTENT_MAIN']['professors'] as $professor) {                
                    $salidaProfessorsHtml .= $this->establecer('{PROFESSOR_ID}', $professor->getIdUsuario(), $templateProfessorElement);
                    $salidaProfessorsHtml = $this->establecer('{IMAGE_FOLDER}', constant("FOLDER")."/view/images/commons/", $salidaProfessorsHtml);
                    $salidaProfessorsHtml = $this->establecer('{PROFESSOR_NAME}', $professor->getNombre().' '.$professor->getApellido1().' '.$professor->getApellido2(), $salidaProfessorsHtml);

                }
            }
            
            $salidaTipos = $this->establecer('{PROFESSORS_LIST}', $salidaProfessorsHtml, $salidaTipos);
            $salidaTipos = $this->establecer('{ACTIVITY_ID}', $data['ACTIVITY_ID'], $salidaTipos);
            
            $this->establecer('{CONTENT_MAIN}', $salidaHtml);
        }else{
            $this->establecer('{CONTENT_MAIN}', '#NO_SESSIONS_AVAILABLE#');
        }
        
            $dataFilter["i"]="";
            $dataFilter["#ACTIVITY#"] = ["index.php?studentActivities&view={$data['ACTIVITY_ID']}"
              ,"#ACTIVITY#",constant("FOLDER")."/view/images/icons/navIcons/seo2.png"];

            
            $this->establecer('{TIPOS_TITLE}', '#ACTIVITY_DESCRIPTION#');            
            $this->apply_help_context('#INFO_ACTIVITY_TITLE#', '#INFO_ACTIVITY_CONTENT#');
            $this->apply_navmenu_filters($dataFilter);            
            
    }

}
