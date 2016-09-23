<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');

/**
 * Description of MVC_view_professor_assistance_list
 *
 * @author jorge
 */
class MVC_view_professor_assistance_list extends MVC_view_messages{
    protected function apply_data($data) {
        $templateGeneral = file_get_contents(constant("FOLDER")."/view/structure/professor_assistance_list/professor_assistance_list.html");
        $templateRow = file_get_contents(constant("FOLDER")."/view/structure/professor_assistance_list/table_element.html");
        $templateOptionRow = file_get_contents(constant("FOLDER")."/view/structure/professor_assistance_list/session_list.html");
       
        
        if($data['NO_PRIVILEGES']){
            $this->establecer('{CONTENT_MAIN}', '#NO_PRIVILEGES#');
        }else{
            
            
            if(isset($data['STUDENTS_ASSISTED'])){
                $salidaStudentsAssisted = "";
                if($data['STUDENTS_ASSISTED'] != false){
                    foreach ($data['STUDENTS_ASSISTED'] as $student) {
                        $salidaStudentsAssisted .= $this->establecer('{STUDENT_NAME}', $student->getNombre().' '.$student->getApellido1().' '.$student->getApellido2(), $templateRow);
                        $salidaStudentsAssisted = $this->establecer('{STUDENT_REAL_ID}', $student->getDNI(), $salidaStudentsAssisted);
                        $salidaStudentsAssisted = $this->establecer('{IMG_FOLDER}', constant("FOLDER")."/view/images/commons", $salidaStudentsAssisted);
                        $salidaStudentsAssisted = $this->establecer('{ASSISTED_IMAGE}', 'checked', $salidaStudentsAssisted);
                        $salidaStudentsAssisted = $this->establecer('{ACTIVITY_ID}', $data['ACTIVITY_ID'], $salidaStudentsAssisted);
                        $salidaStudentsAssisted = $this->establecer('{SESSION_ID}', $data['SESSIONS_ID'], $salidaStudentsAssisted);
                        $salidaStudentsAssisted = $this->establecer('{STUDENT_ID}', $student->getIdUsuario(), $salidaStudentsAssisted);
                    }
                }
            }
            
             if(isset($data['STUDENTS_NOASSISTED'])){
                $salidaStudentsNoAssisted = "";
                if($data['STUDENTS_NOASSISTED'] != false){
                    foreach ($data['STUDENTS_NOASSISTED'] as $student) {
                        $salidaStudentsNoAssisted .= $this->establecer('{STUDENT_NAME}', $student->getNombre().' '.$student->getApellido1().' '.$student->getApellido2(), $templateRow);
                        $salidaStudentsNoAssisted = $this->establecer('{STUDENT_REAL_ID}', $student->getDNI(), $salidaStudentsNoAssisted);
                        $salidaStudentsNoAssisted = $this->establecer('{IMG_FOLDER}', constant("FOLDER")."/view/images/commons", $salidaStudentsNoAssisted);
                        $salidaStudentsNoAssisted = $this->establecer('{ASSISTED_IMAGE}', 'delete_content', $salidaStudentsNoAssisted);
                        $salidaStudentsNoAssisted = $this->establecer('{ACTIVITY_ID}', $data['ACTIVITY_ID'], $salidaStudentsNoAssisted);
                        $salidaStudentsNoAssisted = $this->establecer('{SESSION_ID}', $data['SESSIONS_ID'], $salidaStudentsNoAssisted);
                        $salidaStudentsNoAssisted = $this->establecer('{STUDENT_ID}', $student->getIdUsuario(), $salidaStudentsNoAssisted);
                    }
                }
            }

            
            
             if(isset($data['SESSIONS'])){
                $sessions = "";
                if($data['SESSIONS'] != false){
                    foreach ($data['SESSIONS'] as $session) {
                        $dateFormated = date_create($session->getDateStart());
                        $sessions .= $this->establecer('{ID_SESSION}', $session->getIdSession(), $templateOptionRow);
                        $sessions = $this->establecer('{SESSION_DATE}', $dateFormated->format($GLOBALS["SYSTEM_LONG_TIMEFORMAT"]), $sessions);
                        if($session->getIdSession() != $data['SESSIONS_ID']){
                            $sessions = $this->establecer('{SELECTED}', null, $sessions);
                        }else{
                            $sessions = $this->establecer('{SELECTED}', 'selected', $sessions);
                        }

                    }
                }
            }
            
            
            $salida = $this->establecer('{SESSION_LIST}', $sessions, $templateGeneral);
            $salida = $this->establecer('{ACTIVITY_NAME}', $data['ACTIVITY_NAME'], $salida);
            $salida = $this->establecer('{ASSISTED_LIST}', $salidaStudentsAssisted, $salida);
            $salida = $this->establecer('{NO_ASSISTED_LIST}', $salidaStudentsNoAssisted, $salida);
            $salida = $this->establecer('{ACTIVITY_ID}', $data['ACTIVITY_ID'], $salida);
            $salida = $this->establecer('{ACTIVITY_CODE}', $data['ACTIVITY_CODE'], $salida);
            $salida = $this->establecer('{CONTENT_MAIN}', $salida);
        }
        
        $dataFilter["i"]="";
        $dataFilter["#EVALUATION#"] = ["index.php?activityEval={$data['ACTIVITY_ID']}","#EVALUATION#",constant("FOLDER")."/view/images/icons/navIcons/seo2.png"];
        $dataFilter["#STUDENT_LIST#"] = ["index.php?students={$data['ACTIVITY_ID']}","#STUDENT_LIST#",constant("FOLDER")."/view/images/icons/navIcons/crowd.png"];
        $dataFilter["#STUDENT_LIST_EVAL#"] = ["index.php?studentsEval={$data['ACTIVITY_ID']}","#STUDENT_LIST_EVAL#",constant("FOLDER")."/view/images/icons/navIcons/screen52.png"];
        $dataFilter["j"]="";
        $dataFilter["#STUDENT_LIST_REPORT#"] = ["index.php?evaluationReport={$data['ACTIVITY_ID']}","#STUDENT_LIST_REPORT#",constant("FOLDER")."/view/images/icons/navIcons/presentation15.png"];
        $dataFilter["#STUDENT_ASSISTANCE#"] = ["index.php?sessionsAsistance={$data['ACTIVITY_ID']}","#STUDENT_ASSISTANCE#",constant("FOLDER")."/view/images/icons/navIcons/silhouette75.png"];
        $dataFilter["#MANAGE_SESSIONS#"] = ["index.php?sessions={$data['ACTIVITY_ID']}","#MANAGE_SESSIONS#",constant("FOLDER")."/view/images/icons/navIcons/setting2.png"];
        $dataFilter["#INSERT_STUDENTS#"] = ["index.php?insertStudents&subject={$data['ACTIVITY_ID']}","#INSERT_STUDENTS#",constant("FOLDER")."/view/images/icons/navIcons/refresh64.png"];
        $this->apply_navmenu_filters($dataFilter);
    }

}
