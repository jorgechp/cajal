<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');


/**
 * Description of MVC_view_professor_students_list
 *
 * @author jorge
 */
class MVC_view_professor_students_list extends MVC_view_messages {
    protected function apply_data($data) {
        if($data['NO_ENOUGH_PRIVILEGES']){
            $this->establecer('{CONTENT_MAIN}', '#NOT_ENOUGH_PRIVILEGES#');
            return;
        }   
        $dataFilter["i"]="";
        $dataFilter["#EVALUATION#"] = ["index.php?activityEval={$data['activityID']}","#EVALUATION#",constant("FOLDER")."/view/images/icons/navIcons/seo2.png"];
        $dataFilter["#STUDENT_LIST#"] = ["index.php?students={$data['activityID']}","#STUDENT_LIST#",constant("FOLDER")."/view/images/icons/navIcons/crowd.png"];
        $dataFilter["#STUDENT_LIST_EVAL#"] = ["index.php?studentsEval={$data['activityID']}","#STUDENT_LIST_EVAL#",constant("FOLDER")."/view/images/icons/navIcons/screen52.png"];
        $dataFilter["j"]="";
        $dataFilter["#STUDENT_LIST_REPORT#"] = ["index.php?evaluationReport={$data['activityID']}","#STUDENT_LIST_REPORT#",constant("FOLDER")."/view/images/icons/navIcons/presentation15.png"];
        $dataFilter["#STUDENT_ASSISTANCE#"] = ["index.php?sessionsAsistance={$data['activityID']}","#STUDENT_ASSISTANCE#",constant("FOLDER")."/view/images/icons/navIcons/silhouette75.png"];
        $dataFilter["#MANAGE_SESSIONS#"] = ["index.php?sessions={$data['activityID']}","#MANAGE_SESSIONS#",constant("FOLDER")."/view/images/icons/navIcons/setting2.png"];
        $dataFilter["#INSERT_STUDENTS#"] = ["index.php?insertStudents&subject={$data['activityID']}","#INSERT_STUDENTS#",constant("FOLDER")."/view/images/icons/navIcons/refresh64.png"];
        $this->apply_navmenu_filters($dataFilter);
       
        if($data['CONTENT_MAIN']['isStudents']){

            $studentTemplate = file_get_contents(constant("FOLDER") . '/view/structure/professor_students_list/student_list.html');
            $studentRow = file_get_contents(constant("FOLDER") . '/view/structure/professor_students_list/student_row.html');
            
            $salidaHtml = "";
            
            foreach ($data['CONTENT_MAIN']['students'] as $student) {
                $salidaHtml .= $studentRow;
                $salidaHtml = $this->establecer('{ACTIVITY_ID}', $data['activityID'], $salidaHtml);
                $salidaHtml = $this->establecer('{STUDENT_NAME}', $student->getNombre().' '.$student->getApellido1().' '.$student->getApellido2(), $salidaHtml);
                $salidaHtml = $this->establecer('{STUDENT_ID}', $student->getIdUsuario(), $salidaHtml);
                $salidaHtml = $this->establecer('{STUDENT_MAIL}', $student->getMail(), $salidaHtml);
                $salidaHtml = $this->establecer('{SESSION_IMAGE_URL}', constant("FOLDER").'/view/images/commons', $salidaHtml);
                $salidaHtml = $this->establecer('{STUDENT_REAL_ID}', $student->getDNI() , $salidaHtml);
            }
            
            $salidaFinal = $this->establecer('{STUDENT_ROW}', $salidaHtml,$studentTemplate);
            $salidaFinal = $this->establecer('{ACTIVITY_NAME}', $data['activityNAME'], $salidaFinal);            
            $salidaFinal = $this->establecer('{ACTIVITY_ID}', $data['activityID'],$salidaFinal);
            $salidaFinal = $this->establecer('{ACTIVITY_CODE}', $data['activityCODE'],$salidaFinal);
            $this->establecer('{CONTENT_MAIN}', $salidaFinal);
            
        }else{
            $this->establecer('{CONTENT_MAIN}',  '#NO_STUDENTS#');
        }
        
        if(isset($data['insertionCorrect']) && !$data['insertionCorrect']){
            $this->establecer('{CONTENT_ERROR}',  '#USER_INVALID#');
        }else{
            $this->establecer('{CONTENT_ERROR}',  null);
        }
        
        $templateTypes = file_get_contents(constant("FOLDER").'/view/structure/professor_students_list/student_list_types.html');
        $salidaTipos = $this->establecer('{ACTIVITY_ID}', $data['activityID'], $templateTypes);
       
        $this->establecer('{TIPOS_TITLE}',  '#STUDENT_LIST_TITLE#');
        $this->establecer('{TIPOS}',  $salidaTipos);
    }

}
