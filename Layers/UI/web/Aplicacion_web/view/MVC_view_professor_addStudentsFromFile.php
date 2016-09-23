<?php

require_once(constant("FOLDER") . '/view/MVC_view_messages.php');
require(ROOT . 'lib/PHPExcel/PHPExcel.php');

define("PASS_COLOR", "0000FF");
define("FAIL_COLOR", "F00000");
define("ODD_ROW", "D8D8D8");
define("EVEN_ROW", "F8E0E6");
define("TITLE", "6F6F6F");
define("HEADER1", "F5F6CE");
define("HEADER2", "F5D0A9");
define("HEADER3", "E6F8E0");
define("DOCUMENT_NAME", "informe");





/**
 * Description of MVC_view_evaluationReport
 *
 * @author jorge
 */
class MVC_view_addStudentsFromFile extends MVC_view_messages {



    protected function apply_data($data) {
        $content = file_get_contents(constant("FOLDER").'/view/structure/professor_insert_students/content.html');
        
        if($data['CONTENT_MAIN']['UPLOADED_FILE']){
            if($data['CONTENT_MAIN']['INVALID_FORMAT']){
                $content =  $this->establecer('{RESULT}', '#INVALID_FORMAT#',$content);
            }else{
                if($data['CONTENT_MAIN']['INSERTIONS_OK']){
                    $content =  $this->establecer('{RESULT}', '#UPLOADED_COMPLETE_1# '.$data['CONTENT_MAIN']['INSERTIONS'].' #UPLOADED_COMPLETE_2#'.' #ADDED_STUDENT_INFO#',$content);
                }else{
                    $content =  $this->establecer('{RESULT}', '#UPLOADED_COMPLETE_3# '.$data['CONTENT_MAIN']['INSERTIONS'].' #UPLOADED_COMPLETE_4#'.' #ADDED_STUDENT_INFO#',$content);
                }
                
                if(count($data['CONTENT_MAIN']['ERROR_LIST']) > 0){
                    $salida = "#ERROR_LINES#: ";
                    foreach ($data['CONTENT_MAIN']['ERROR_LIST'] as $linea) {
                        $salida .= "$linea,";
                    }
                }
                $content =  $this->establecer('{ERROR_LIST}', $salida,$content);
            }
        }
        
        if(isset($data['CONTENT_MAIN']['NO_ID']) && $data['CONTENT_MAIN']['NO_ID']){
            $content = file_get_contents(constant("FOLDER").'/view/structure/professor_insert_students/no_permission_content.html');
            $content =  $this->establecer('{RESULT}', '#INVALID_FORMAT#',$content);
        }else{
            $content =  $this->establecer('{ID_ACTIVITY}', $data['ID_ACTIVITY'],$content);
        }
        
        $dataFilter["i"]="";
        $dataFilter["#EVALUATION#"] = ["index.php?activityEval={$data['ID_ACTIVITY']}","#EVALUATION#",constant("FOLDER")."/view/images/icons/navIcons/seo2.png"];
        $dataFilter["#STUDENT_LIST#"] = ["index.php?students={$data['ID_ACTIVITY']}","#STUDENT_LIST#",constant("FOLDER")."/view/images/icons/navIcons/crowd.png"];
        $dataFilter["#STUDENT_LIST_EVAL#"] = ["index.php?studentsEval={$data['ID_ACTIVITY']}","#STUDENT_LIST_EVAL#",constant("FOLDER")."/view/images/icons/navIcons/screen52.png"];
        $dataFilter["j"]="";
        $dataFilter["#STUDENT_LIST_REPORT#"] = ["index.php?evaluationReport={$data['ID_ACTIVITY']}","#STUDENT_LIST_REPORT#",constant("FOLDER")."/view/images/icons/navIcons/presentation15.png"];
        $dataFilter["#STUDENT_ASSISTANCE#"] = ["index.php?sessionsAsistance={$data['ID_ACTIVITY']}","#STUDENT_ASSISTANCE#",constant("FOLDER")."/view/images/icons/navIcons/silhouette75.png"];
        $dataFilter["#MANAGE_SESSIONS#"] = ["index.php?sessions={$data['ID_ACTIVITY']}","#MANAGE_SESSIONS#",constant("FOLDER")."/view/images/icons/navIcons/setting2.png"];
        $dataFilter["#INSERT_STUDENTS#"] = ["index.php?insertStudents&subject={$data['ID_ACTIVITY']}","#INSERT_STUDENTS#",constant("FOLDER")."/view/images/icons/navIcons/refresh64.png"];
        $this->apply_navmenu_filters($dataFilter);
        
        $this->establecer('{CONTENT_MAIN}', $content);
        $this->apply_help_context('#ABOUT#', "#NOLOGIN_INFO#");        
    }

}