<?php

require_once(constant("FOLDER") . '/view/MVC_view_messages.php');

/**
 * Description of MVC_view_competenceEval
 *
 * @author jorge
 */
class MVC_view_professor_competenceEval extends MVC_view_messages {

    protected function apply_data($data) {
        $typesTemplate = file_get_contents(constant("FOLDER") . '/view/structure/professor_competence_eval/competenceTypes.html');
        if(isset($data['ERROR_CONTENT']['notAllowed'])){
            $this->establecer('{CONTENT_MAIN}', '#NOT_ALLOWED#');
            return;
        }
        if (!$data['NO_COMPETENCE_ASSOCIATED']) {
            if (!$data['NO_STUDENTS']) {
                if (!$data['NO_INDICATORS']) {
                    if (!$data['NO_SESSIONS']) {
                        $sectionTemplate = file_get_contents(constant("FOLDER") . '/view/structure/professor_competence_eval/competenceEvalSection.html');
                        $sessionTemplate = file_get_contents(constant("FOLDER") . '/view/structure/professor_competence_eval/competenceEvalSessions.html');
                        $headerContentTemplate = file_get_contents(constant("FOLDER") . '/view/structure/professor_competence_eval/competenceEvalTableHeaderContent.html');
                        $headerTemplate = file_get_contents(constant("FOLDER") . '/view/structure/professor_competence_eval/competenceEvalTableHeader.html');
                        $rowTemplate = file_get_contents(constant("FOLDER") . '/view/structure/professor_competence_eval/competenceEvalTableRow.html');
                        $rowContent = file_get_contents(constant("FOLDER") . '/view/structure/professor_competence_eval/competenceEvalTableRowContent.html');
                        $rowValue = file_get_contents(constant("FOLDER") . '/view/structure/professor_competence_eval/competenceEvalRowValue.html');                        
                        $generalIndicatorsTemplate = file_get_contents(constant("FOLDER") . '/view/structure/professor_competence_eval/competenceEvalIndicatorOptionList.html');
                        $competenceEvalAllForm = file_get_contents(constant("FOLDER") . '/view/structure/professor_competence_eval/competenceEvalIAllForm.html');
                        
                        $salidaHtml = "";
                        $salidaHtmlSessions = "";
                        $salidaHtmlIndicators = "";
                        $salidaHtmlStudents = "";
                        $salidaHtmlCelda = null;

                        $columnSize = count($data['CONTENT_MAIN']['indicators']);
                       
                        foreach ($data['CONTENT_MAIN']['sessions'] as $session) {
                            $dateFormated = date_create($session->getDateStart());
                            
                            $salidaHtmlSessions .= $sessionTemplate;
                            $salidaHtmlSessions = $this->establecer('{SESSION_ID}', $session->getIdSession(), $salidaHtmlSessions);
                            $salidaHtmlSessions = $this->establecer('{SESSION_NAME}',$dateFormated->format($GLOBALS["SYSTEM_LONG_TIMEFORMAT"]), $salidaHtmlSessions);
                            
                            if(isset($data['CONTENT_MAIN']['selectedSession']) && $data['CONTENT_MAIN']['selectedSession'] == $session->getIdSession()){
                                $salidaHtmlSessions = $this->establecer('{IS_SELECTED}', 'selected', $salidaHtmlSessions);
                            }else{
                                $salidaHtmlSessions = $this->establecer('{IS_SELECTED}', null, $salidaHtmlSessions);
                            }
                        }
                        
                        $salidaHtmlIndicators .= $this->establecer('{COMPETENCE_TABLE_DATA_HEAD_ROW_TEXT}', '#MARK#', $headerContentTemplate);
                        $salidaHtmlIndicators = $this->establecer('{COMPETENCE_TABLE_DATA_HEAD_ROW_TOOLTIP}', '#MARK#', $salidaHtmlIndicators);
                        $salidaHtmlIndicators .= $this->establecer('{COMPETENCE_TABLE_DATA_HEAD_ROW_TEXT}', '#STUDENTS_NAME#', $headerContentTemplate);
                        $salidaHtmlIndicators = $this->establecer('{COMPETENCE_TABLE_DATA_HEAD_ROW_TOOLTIP}', '#STUDENTS_NAME#', $salidaHtmlIndicators);
                        $salidaHtmlIndicators .= $this->establecer('{COMPETENCE_TABLE_DATA_HEAD_ROW_TEXT}', '#STUDENTS_REALID#', $headerContentTemplate);
                        $salidaHtmlIndicators = $this->establecer('{COMPETENCE_TABLE_DATA_HEAD_ROW_TOOLTIP}', '#STUDENTS_REALID#', $salidaHtmlIndicators);

                        foreach ($data['CONTENT_MAIN']['indicators'] as $indicator) {
                            $salidaHtmlIndicators .= $this->establecer('{COMPETENCE_TABLE_DATA_HEAD_ROW_TOOLTIP}', $indicator->getName(), $headerContentTemplate);                            
                            $salidaHtmlIndicators = $this->establecer('{COMPETENCE_TABLE_DATA_HEAD_ROW_TEXT}', $indicator->getCode(), $salidaHtmlIndicators); 
                        }


                        foreach ($data['CONTENT_MAIN']['students'] as $student) {
                            $salidaHtmlStudents .= $rowTemplate;

                            $salidaHtmlCelda = $this->establecer('{ROW_CONTENT}', "<input type='checkbox' id=check{$student->getIdUsuario()} name='studentMark[]' value='{$student->getIdUsuario()}' onclick='toggleElement(".'"singleCalificationButton"'.",false)'>", $rowContent);
                            $salidaHtmlCelda .= $this->establecer('{ROW_CONTENT}', "<a href='index.php?user=".$student->getIdUsuario()."'>".$student->getNombre().' '.$student->getApellido1().' '.$student->getApellido2().'</a>', $rowContent);
                            $salidaHtmlCelda .= $this->establecer('{ROW_CONTENT}', $student->getDNI(), $rowContent);
                            
                            for ($index = 0; $index < $columnSize; $index++) {

                                $salidaHtmlCelda .= $this->establecer('{ROW_CONTENT}', $rowValue, $rowContent);
                                $salidaHtmlCelda = $this->establecer('{ID_STUDENT}', $student->getIdUsuario(), $salidaHtmlCelda);
                                $salidaHtmlCelda = $this->establecer('{ID_INDICATOR}', $data['CONTENT_MAIN']['indicators'][$index]->getIdIndicator(), $salidaHtmlCelda);
                                $salidaHtmlCelda = $this->establecer(
                                        '{IS_SELECTED_' . $data['CONTENT_MAIN']['evaluation'][$student->getIdUsuario()][$data['CONTENT_MAIN']['indicators'][$index]->getIdIndicator()] . '}'
                                        , 'selected', $salidaHtmlCelda);
                                $salidaHtmlCelda = preg_replace('/{.*}/', '', $salidaHtmlCelda);
                            }
                            $salidaHtmlStudents = $this->establecer('{COMPETENCE_TABLE_DATA_ROW}', $salidaHtmlCelda, $salidaHtmlStudents);
                        }
                        //Rellena la optionList para aplicar indicadores a todo el grupo
                        $salidaIndicadoresAplicacionGeneral = "";
                        foreach ($data['CONTENT_MAIN']['indicators'] as $indicator) {
                            $salidaIndicadoresAplicacionGeneral .= $this->establecer('{INDICATOR_NAME}', $indicator->getName(), $generalIndicatorsTemplate); 
                            $salidaIndicadoresAplicacionGeneral = $this->establecer('{ID_INDICATOR}', $indicator->getIdIndicator(), $salidaIndicadoresAplicacionGeneral); 
                            $salidaIndicadoresAplicacionGeneral = $this->establecer('{INDICATOR_CODE}', $indicator->getCode(), $salidaIndicadoresAplicacionGeneral); 
                        } 
                        
                        $salidaHtmlIndicatorsList = $this->establecer('{INDICATOR_OPTION_LIST}', $salidaIndicadoresAplicacionGeneral, $competenceEvalAllForm);

                        $salidaHtml = $this->establecer('{COMPETENCE_TABLE_SESSIONS}', $salidaHtmlSessions, $sectionTemplate);                        
                        $salidaHtml = $this->establecer('{COMPETENCE_TABLE}', $headerTemplate, $salidaHtml);
                        $salidaHtml = $this->establecer('{COMPETENCE_TABLE_HEADER}', $salidaHtmlIndicators, $salidaHtml);
                        $salidaHtml = $this->establecer('{COMPETENCE_TABLE_DATA}', $salidaHtmlStudents, $salidaHtml);
                        $salidaHtml = $this->establecer('{COMPETENCE_ID}', $data['CONTENT_MAIN']['competenceId'], $salidaHtml);
                        $salidaHtml = $this->establecer('{ACTIVITY_ID}', $data['CONTENT_MAIN']['activityId'], $salidaHtml);
                        $salidaHtml = $this->establecer('{COMPETENCE_EVAL_ALL_FORM}', $salidaHtmlIndicatorsList, $salidaHtml);
                        $salidaHtml = $this->establecer('{COMPETENCE_NAME}', $data['CONTENT_MAIN']['competenceName'], $salidaHtml);

                        
                        
                        
                        $salidaHtml = $this->establecer('{ACTIVITY_NAME}', $data['CONTENT_MAIN']['activityName'], $salidaHtml);
                        $this->establecer('{CONTENT_MAIN}', $salidaHtml);
                    } else {

                        $this->establecer('{CONTENT_MAIN}', '#NO_SESSIONS_ASSOCIATED#');
                    }
                } else {
                    $this->establecer('{CONTENT_MAIN}', '#NO_INDICATORS_ASSOCIATED#');
                }
            } else {
                $this->establecer('{CONTENT_MAIN}', '#NO_STUDENTS_ASSOCIATED#');
            }
        } else {
            $this->establecer('{CONTENT_MAIN}', '#NO_COMPETENCE_ASSOCIATED#');
        }

        $dataFilter["i"]="";
        $dataFilter["#EVALUATION#"] = ["index.php?activityEval={$data['CONTENT_MAIN']['activityId']}","#EVALUATION#",constant("FOLDER")."/view/images/icons/navIcons/seo2.png"];
        $dataFilter["#STUDENT_LIST#"] = ["index.php?students={$data['CONTENT_MAIN']['activityId']}","#STUDENT_LIST#",constant("FOLDER")."/view/images/icons/navIcons/crowd.png"];
        $dataFilter["#STUDENT_LIST_EVAL#"] = ["index.php?studentsEval={$data['CONTENT_MAIN']['activityId']}","#STUDENT_LIST_EVAL#",constant("FOLDER")."/view/images/icons/navIcons/screen52.png"];
        $dataFilter["j"]="";
        $dataFilter["#STUDENT_LIST_REPORT#"] = ["index.php?evaluationReport={$data['CONTENT_MAIN']['activityId']}","#STUDENT_LIST_REPORT#",constant("FOLDER")."/view/images/icons/navIcons/presentation15.png"];
        $dataFilter["#STUDENT_ASSISTANCE#"] = ["index.php?sessionsAsistance={$data['CONTENT_MAIN']['activityId']}","#STUDENT_ASSISTANCE#",constant("FOLDER")."/view/images/icons/navIcons/silhouette75.png"];
        $dataFilter["#MANAGE_SESSIONS#"] = ["index.php?sessions={$data['CONTENT_MAIN']['activityId']}","#MANAGE_SESSIONS#",constant("FOLDER")."/view/images/icons/navIcons/setting2.png"];
        $dataFilter["#INSERT_STUDENTS#"] = ["index.php?insertStudents&subject={$data['CONTENT_MAIN']['activityId']}","#INSERT_STUDENTS#",constant("FOLDER")."/view/images/icons/navIcons/refresh64.png"];
        $this->apply_navmenu_filters($dataFilter);
        
    }

}
