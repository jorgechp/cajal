<?php

require_once(constant("FOLDER") . '/view/MVC_view_messages.php');

/**
 * Description of MVC_view_professor_student_eval
 *
 * @author jorge
 */
class MVC_view_professor_student_eval extends MVC_view_messages {

    protected function apply_data($data) {       
        $dataFilter["i"]="";
        $dataFilter["#EVALUATION#"] = ["index.php?activityEval={$data['idActivity']}","#EVALUATION#",constant("FOLDER")."/view/images/icons/navIcons/seo2.png"];
        $dataFilter["#STUDENT_LIST#"] = ["index.php?students={$data['idActivity']}","#STUDENT_LIST#",constant("FOLDER")."/view/images/icons/navIcons/crowd.png"];
        $dataFilter["#STUDENT_LIST_EVAL#"] = ["index.php?studentsEval={$data['idActivity']}","#STUDENT_LIST_EVAL#",constant("FOLDER")."/view/images/icons/navIcons/screen52.png"];
        $dataFilter["j"]="";
        $dataFilter["#STUDENT_LIST_REPORT#"] = ["index.php?evaluationReport={$data['idActivity']}","#STUDENT_LIST_REPORT#",constant("FOLDER")."/view/images/icons/navIcons/presentation15.png"];
        $dataFilter["#STUDENT_ASSISTANCE#"] = ["index.php?sessionsAsistance={$data['idActivity']}","#STUDENT_ASSISTANCE#",constant("FOLDER")."/view/images/icons/navIcons/silhouette75.png"];
        $dataFilter["#MANAGE_SESSIONS#"] = ["index.php?sessions={$data['idActivity']}","#MANAGE_SESSIONS#",constant("FOLDER")."/view/images/icons/navIcons/setting2.png"];
        $dataFilter["#INSERT_STUDENTS#"] = ["index.php?insertStudents&subject={$data['idActivity']}","#INSERT_STUDENTS#",constant("FOLDER")."/view/images/icons/navIcons/refresh64.png"];
        $this->apply_navmenu_filters($dataFilter);
        //En primer lugar, se comprueba que tenemos permisos
        if ($data['NO_DATA']) {
            $this->establecer('{CONTENT_MAIN}', '#NOT_ENOUGH_DATA#');
            return;
        }

        if ($data['IS_ALLOWED']) {
            //Cargamos en memoria las plantillas de datos de la vista
            //$template_header almacena el contenido principal que reemplazará a {CONTENT_MAIN}
            $template_header = file_get_contents(constant("FOLDER") . "/view/structure/professor_student_eval/professor_student_eval_header.html");
            //$template_indicator_header_row carga el contenido de una cabecera con el nombre de la competencia
            $template_session_header_row = file_get_contents(constant("FOLDER") . "/view/structure/professor_student_eval/professor_student_eval_session_header_row.html");
            //$template_row carga cada elemento que será insertado en una fila
            $template_row = file_get_contents(constant("FOLDER") . "/view/structure/professor_student_eval/professor_student_eval_row.html");
            //$template_session_row es similar a $template_indicator_header_row pero para cada fila de la tabla que no sea cabecera
            $template_session_row = file_get_contents(constant("FOLDER") . "/view/structure/professor_student_eval/professor_student_eval_row_competences_values.html");
            //$template_session_row_nameList guarda un elemento de la lista para cada competencia que se desea mostrar
            $template_session_row_nameList = file_get_contents(constant("FOLDER") . "/view/structure/professor_student_eval/professor_student_eval_row_competences_list.html");
            //$template_session_row_nameList_indicator guarda un elemento para la lista de nombres de indicadores
            $template_session_row_nameList_indicator = file_get_contents(constant("FOLDER") . "/view/structure/professor_student_eval/professor_student_eval_row_competences_list_indicator.html");
            //$template_session_row_nameList_indicator_list Almacena un elemento de una lista de indicadores
            $template_session_row_nameList_indicator_list = file_get_contents(constant("FOLDER") . "/view/structure/professor_student_eval/professor_student_eval_row_competences_list_indicator_list.html");
            //Muestra el formulario para ocultar o activar columnas de sesión
            $template_show_columns = file_get_contents(constant("FOLDER") . "/view/structure/professor_student_eval/professor_student_eval_show_columns.html");
            

            $salidaFinal = "";
            //Cargamos en la cabecera de la tabla de evaluación los nombres de cada sesion
            $salidaSessionHeaderRow = "";
            $dateFormated = null;
            $contadorSesiones = 0;
            foreach ($data['sessions'] as $idSession => $sessions) {
                $dateFormated = date_create($sessions);
                $salidaSessionHeaderRow .= $this->establecer('{SESSION_NAME}', $dateFormated->format($GLOBALS["SYSTEM_LONG_TIMEFORMAT"]), $template_session_header_row);
                $salidaSessionHeaderRow = $this->establecer('{SESSION_NUMBER}', $contadorSesiones, $salidaSessionHeaderRow);
                ++$contadorSesiones;
            }

            $salidaSessionList = "";
            $contadorSesiones = 0;
            $tamSessions = count($data['sessions']);
            foreach ($data['sessions'] as $idSession => $sessions) {
                $dateFormated = date_create($sessions);
                $salidaSessionList .= $this->establecer('{SESSION_NUMBER}', $contadorSesiones, $template_show_columns);
                $salidaSessionList = $this->establecer('{SESSION_NAME}', $dateFormated->format($GLOBALS["SYSTEM_LONG_TIMEFORMAT"]), $salidaSessionList);
                $count = $contadorSesiones+1;
                if($count < 10){
                    $count = '0'.$count;
                }
                $salidaSessionList = $this->establecer('{SESSION_NUMBER_COUNT}', $count, $salidaSessionList);
                
                if (($contadorSesiones % 4) == 0) {
                    $salidaSessionList = $this->establecer('{LI_OPEN}', '<li>', $salidaSessionList);
                    $salidaSessionList = $this->establecer('{LI_CLOSE}', null, $salidaSessionList);
                } else if (($contadorSesiones % 4) == 3 || $contadorSesiones == $tamSessions - 1) {
                    $salidaSessionList = $this->establecer('{LI_OPEN}', null, $salidaSessionList);
                    $salidaSessionList = $this->establecer('{LI_CLOSE}', '</li>', $salidaSessionList);
                } else {
                    $salidaSessionList = $this->establecer('{LI_OPEN}', null, $salidaSessionList);
                    $salidaSessionList = $this->establecer('{LI_CLOSE}', null, $salidaSessionList);
                }

                ++$contadorSesiones;
            }



            //Ya tenemos todos los elementos necesarios en la cabecera de la tabla, ahora hay que ir rellenando fila a fila
            //Se carga el nombre junto a la lista de competencias
            $salidaNameCompetenceRow = "";
//            foreach ($data['competences'] as $competencia) {
//                $salidaIndicator = "";
//                if(is_array($data['indicators'][$competencia->getIdCompetencia()])){
//                    foreach ($data['indicators'][$competencia->getIdCompetencia()] as $indicator) {
//                        $salidaIndicator .= $this->establecer('{ELEMENT}', $indicator->getCode(), $template_session_row_nameList_indicator_list);
//                        $salidaIndicator = $this->establecer('{ELEMENT_TOOLTIP}', $indicator->getName(), $salidaIndicator);
//                        }
//                }
//                $salidaNameCompetenceRow .= $this->establecer('{COMPETENCE_NAME}', $competencia->getName(), $template_session_row_nameList);
//                $salidaNameCompetenceRow = $this->establecer('{INDICATOR_LIST}', $salidaIndicator, $salidaNameCompetenceRow);
//                $salidaNameCompetenceRow = $this->establecer('{COMPETENCE_CODE}', $competencia->getCode(), $salidaNameCompetenceRow);
//            }
            
            
            
            /**
             * AÑADIR FILAS  A LA TABLA
             */
            $salidaRow = "";
            foreach ($data['students'] as $student) {

                $salidaRow .= $this->establecer('{COMPETENCES_LIST}', $salidaNameCompetenceRow, $template_row);
                $salidaRow = $this->establecer('{STUDENT_NAME}', $student->getNombre() . ' ' . $student->getApellido1() . ' ' . $student->getApellido2(), $salidaRow);
                $salidaRow = $this->establecer('{STUDENT_REAL_ID}', $student->getDNI(), $salidaRow);


                //Pero hay que cargar todas las sesiones

                $salidaSessionRow = "";
                $contador = 0;
                foreach ($data['sessions'] as $idSession => $sessions) {
                    /*
                     * PRIMERO, SE RELLENAN LOS DATOS DE CADA TABLA
                     */
                    $salidaCompetencias = "";
                    foreach ($data['competences'] as $competencia) {
                        $salidaIndicator = "";                            
                        if(is_array($data['indicators'][$competencia->getIdCompetencia()] )){
                            foreach ($data['indicators'][$competencia->getIdCompetencia()] as $indicator) {                                
                                //$historico almacena, para cada usuario, cada competencia y cada indicador, un histórico de evaluaciones por cada sesión
                                $historico = $data['evaluation'][$student->getIdUsuario()][$competencia->getIdCompetencia()][$indicator->getIdIndicator()];
                                if (isset($historico[$contador])) {
                                    $calificacion = $historico[$contador]->getEvaluacion();

                                    $salidaIndicator .= $this->establecer('{ELEMENT}', $calificacion, $template_session_row_nameList_indicator_list);
                                    $salidaIndicator = $this->establecer('{INDICATOR_NAME}', $indicator->getName(), $salidaIndicator);
                                }
                            }
                        }
                        
                        $salidaCompetencias .= $this->establecer('{INDICATOR_LIST}', $salidaIndicator, $template_session_row_nameList_indicator);
                    $salidaCompetencias = $this->establecer('{COMPETENCE_NAME}', $competencia->getName(), $salidaCompetencias);
                    $salidaCompetencias = $this->establecer('{COMPETENCE_CODE}', $competencia->getCode(), $salidaCompetencias);
                    }
                    
                    $salidaSessionRow .= $this->establecer('{SESSION_NAME}', $salidaCompetencias, $template_session_row);
                    $salidaSessionRow = $this->establecer('{SESSION_NUMBER}', $contador, $salidaSessionRow);
                    
                    
                    ++$contador;
                }


                $salidaRow = $this->establecer('{COMPETENCES_VALUES}', $salidaSessionRow, $salidaRow);

                //Se calcula la media para cada indicador
                $salidaCompetenciasMedias = "";
                foreach ($data['competences'] as $competencia) {
                    $salidaIndicatorMedias = "";
                    if(is_array($data['indicators'][$competencia->getIdCompetencia()])){
                        foreach ($data['indicators'][$competencia->getIdCompetencia()] as $indicator) {


                            $salidaIndicatorMedias .= $this->establecer('{ELEMENT}', number_format($data['evaluation'][$student->getIdUsuario()]['MEAN'][$competencia->getIdCompetencia()][$indicator->getIdIndicator()], 2, '.', ''), // AQUÍ SE CALCULA LA MEDIA DE CADA INDICADOR
                                    $template_session_row_nameList_indicator_list);
                            $salidaIndicatorMedias = $this->establecer('{INDICATOR_NAME}', $indicator->getCode(), $salidaIndicatorMedias);
                            $salidaIndicatorMedias = $this->establecer('{INDICATOR_NAME_TOOLTIP}', $indicator->getName(), $salidaIndicatorMedias);
                        }
                    }
                    $salidaCompetenciasMedias .= $this->establecer('{INDICATOR_LIST}', $salidaIndicatorMedias, $template_session_row_nameList_indicator);
                    $salidaCompetenciasMedias = $this->establecer('{COMPETENCE_NAME}', $competencia->getName(), $salidaCompetenciasMedias);
                    $salidaCompetenciasMedias = $this->establecer('{COMPETENCE_CODE}', $competencia->getCode(), $salidaCompetenciasMedias);
                }


                $salidaRow = $this->establecer('{STUDENT_MEAN}', $salidaCompetenciasMedias, $salidaRow);

                //Se calcula la media para cada indicador
                $salidaCompetenciasMax = "";
                foreach ($data['competences'] as $competencia) {
                    $salidaIndicatorMax = "";
                    if(is_array($data['indicators'][$competencia->getIdCompetencia()] )){
                        foreach ($data['indicators'][$competencia->getIdCompetencia()] as $indicator) {
                            $salidaIndicatorMax .= $this->establecer('{ELEMENT}', $data['evaluation'][$student->getIdUsuario()]['MAX'][$competencia->getIdCompetencia()][$indicator->getIdIndicator()], // AQUÍ SE CALCULA LA MEDIA DE CADA INDICADOR
                                    $template_session_row_nameList_indicator_list);
                            $salidaIndicatorMax = $this->establecer('{INDICATOR_NAME}', $indicator->getCode(), $salidaIndicatorMax);
                            $salidaIndicatorMax = $this->establecer('{INDICATOR_NAME_TOOLTIP}', $indicator->getName(), $salidaIndicatorMax);
                        }
                    }
                    $salidaCompetenciasMax .= $this->establecer('{INDICATOR_LIST}', $salidaIndicatorMax, $template_session_row_nameList_indicator);
                    $salidaCompetenciasMax = $this->establecer('{COMPETENCE_NAME}', $competencia->getName(), $salidaCompetenciasMax);
                    $salidaCompetenciasMax = $this->establecer('{COMPETENCE_CODE}', $competencia->getCode(), $salidaCompetenciasMax);
                }


                $salidaRow = $this->establecer('{STUDENT_MAX}', $salidaCompetenciasMax, $salidaRow);
            }
            //Se añaden todos los códigos generados a la plantilla final, y esta su vez, a {CONTENT_MAIN}
            $salidaFinal = $this->establecer('{SESSION_LIST}', $salidaSessionHeaderRow, $template_header);
            $salidaFinal = $this->establecer('{SESSIONS_SHOW_COLUMNS}', $salidaSessionList, $salidaFinal);
            $salidaFinal = $this->establecer('{EVAL_TABLE}', $salidaRow, $salidaFinal);
            $salidaFinal = $this->establecer('{ACTIVITY_NAME}', $data['nameActivity'], $salidaFinal);
            $salidaFinal = $this->establecer('{ACTIVITY_ID}', $data['idActivity'], $salidaFinal);

            $this->establecer('{CONTENT_MAIN}', $salidaFinal);
        } else {
            $this->establecer('{CONTENT_MAIN}', '#NOT_ENOUGH_PRIVILEGES#');
        }
        

    }

}
