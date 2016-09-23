<?php

require(ROOT . 'lib/tcpdf/tcpdf_include.php');
require_once(constant("FOLDER") . '/view/MVC_view_messages.php');

define("GREEN_COLOR", "#00FF00");
define("YELLOW_COLOR", "#FFFF99");
define("RED_COLOR", "#FF9966");

/**
 * Description of MVC_view_student_report
 *
 * @author jorge
 */
class MVC_view_student_report extends MVC_view_messages {

    protected function apply_i18n($diccionarios) {
        $html = func_get_arg(1);
        foreach ($diccionarios as $key => $value) {
            $html = str_replace('#'.$key.'#', $value, $html);
        }    
        return $html;
    }


    protected function apply_data($data) {
        require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'StudentReport.php');
        $template = file_get_contents(constant("FOLDER") . '/view/structure/studentDownloadReport/mainContent.html');
        $newRow_template = file_get_contents(constant("FOLDER") . '/view/structure/studentDownloadReport/newRow_template.html');
        $cell_template = file_get_contents(constant("FOLDER") . '/view/structure/studentDownloadReport/cell_template.html');
        $table_template = file_get_contents(constant("FOLDER") . '/view/structure/studentDownloadReport/table_template.html');
        $competencesFrame = file_get_contents(constant("FOLDER") . '/view/structure/studentDownloadReport/competenceDetailedFrame.html');
        $competence_info = file_get_contents(constant("FOLDER") . '/view/structure/studentDownloadReport/competence.html');
        $indicator_info = file_get_contents(constant("FOLDER") . '/view/structure/studentDownloadReport/indicator.html');


        $dateFormated = date($GLOBALS["SYSTEM_LONG_TIMEFORMAT"]);
        
        $salidaHtml = $template;
        $salidaHtml = $this->establecer('{STUDENT_NAME}', $data['CONTENT_MAIN']['STUDENT']->getNombre(), $salidaHtml);
        $salidaHtml = $this->establecer('{STUDENT_LASTNAME}', $data['CONTENT_MAIN']['STUDENT']->getApellido1() . ' ' . $data['CONTENT_MAIN']['STUDENT']->getApellido2(), $salidaHtml);
        $salidaHtml = $this->establecer('{STUDENT_REALID}', $data['CONTENT_MAIN']['STUDENT']->getDNI(), $salidaHtml);

        //Generando html para tabla de competencias
        $listaCompetenciasPorTipo = array();
        $nombresTiposCompetencia = array();
        
        foreach ($data['CONTENT_MAIN']['COMPETENCE_TYPES'] as $tipoCompetencia) {
            $listaCompetenciasPorTipo[$tipoCompetencia->getIdTypeCompetence()] = array();
            $nombresTiposCompetencia[$tipoCompetencia->getIdTypeCompetence()] = $tipoCompetencia->getName();
            foreach ($data['CONTENT_MAIN']['COMPETENCES'] as $competencia) {               
                if($competencia->getIdType() == $tipoCompetencia->getIdTypeCompetence()){                    
                    $listaCompetenciasPorTipo[$tipoCompetencia->getIdTypeCompetence()][] = $competencia;                    
                }
            }
        }
       
                    
        
        $competences_per_row = 2;       
        $salidaFila = "";
        $salidaCelda = "";
        $salidaTabla = array();
        
        foreach ($listaCompetenciasPorTipo as $tipoCompetencia => $listaTipo)             
         {
            $tamCompetencias = count($listaTipo);   
            
            if($tamCompetencias > 0){
                $color = 0;
                switch ($tipoCompetencia) {
                    case 1:
                        $color = GREEN_COLOR;
                        break;
                    case 2:
                        $color = YELLOW_COLOR;
                        break;
                    case 3:
                        $color = RED_COLOR;
                        break;                
                    default:
                        break;
                }
                if(isset($listaCompetenciasPorTipo[$tipoCompetencia])){

                    $contadorCompetenciasTipo = 0; 
                    $salidaFila = "";
                    while($contadorCompetenciasTipo < $tamCompetencias){
                        $salidaCelda = "";
                        for ($fila = 0; $fila < $competences_per_row && $contadorCompetenciasTipo < $tamCompetencias; $fila++) {                             
                            $competencia = $listaTipo[$contadorCompetenciasTipo];
                            
                            $salidaCelda .= $this->establecer('{COMPETENCE_NAME}', $competencia->getName(), $cell_template);
                            $salidaCelda = $this->establecer('{BACKGROUND_COLOR}', $color, $salidaCelda); 
                            ++$contadorCompetenciasTipo;
                        }
                        $salidaFila .= $this->establecer('{TABLE_ROW}', $salidaCelda, $newRow_template);
                    }                    
                }
                $salidaTabla[$tipoCompetencia] = $this->establecer('{TABLE_CONTENT}', $salidaFila, $table_template);                
                $salidaTabla[$tipoCompetencia] = $this->establecer('{COMPETENCE_TYPE}',$nombresTiposCompetencia[$competencia->getIdType()].'s' , $salidaTabla[$tipoCompetencia]);
            }
        }
        
        $salidaCompetencias = "";
        
        foreach ($salidaTabla as $salida) {
            $salidaCompetencias .= $salida;
        }
        
        
        
        $salidaDetallesCompetencia = "";
        foreach ($listaCompetenciasPorTipo as $tipoCompetencia => $listaTipo)             
         {
            $tamCompetencias = count($listaTipo);   

            if($tamCompetencias > 0){
                $color = 0;

                if(isset($listaCompetenciasPorTipo[$tipoCompetencia])){

                    $contadorCompetenciasTipo = 0; 
                    $salidaFila = "";
                    
                    while($contadorCompetenciasTipo < $tamCompetencias){
                        $salidaCelda = "";
                             
                            $competencia = $listaTipo[$contadorCompetenciasTipo];
                      
                            $salidaCelda = $this->establecer('{COMPETENCE_NAME}', $competencia->getName(), $competence_info);
                            $salidaCelda = $this->establecer('{COMPETENCE_DESCRIPTION}', $competencia->getDescription(), $salidaCelda); 
                            $salidaCelda = $this->establecer('{COMPETENCE_CODE}', $competencia->getCode(), $salidaCelda);               
                            $salidaCelda = $this->establecer('{COMPETENCE_AREA}',  $data['CONTENT_MAIN']['COMPETENCE_NAMES'][$competencia->getIdCompetencia()]['AREA'] , $salidaCelda); 
                            $salidaCelda = $this->establecer('{COMPETENCE_MATERY}', $data['CONTENT_MAIN']['COMPETENCE_NAMES'][$competencia->getIdCompetencia()]['MATERIA'], $salidaCelda); 
                         

                            $salidaCelda = $this->establecer('{COMPETENCE_CALIFICATION}', $data['CONTENT_MAIN']['COMPETENCES_CALIFICATION'][$competencia->getIdCompetencia()], $salidaCelda); 

                            
                                $indicadores = $data['CONTENT_MAIN']['INDICATORS'][$competencia->getIdCompetencia()];
                                
                                if(is_array($indicadores) & !empty($indicadores)){
                              
                                    foreach ($indicadores as $indicador) {   

                                        $is_passed = $data['CONTENT_MAIN']['INDICATORS_PASSED'][$competencia->getIdCompetencia()][$indicador->getIdIndicator()];
                                        $calification = $data['CONTENT_MAIN']['INDICATORS_CALIFICATION'][$competencia->getIdCompetencia()][$indicador->getIdIndicator()];
                                        $calification = (int) $calification;

                                        if(isset($salidaIndicadores)){
                                            $salidaIndicadores .= $this->establecer('{INDICATOR_NAME}', $indicador->getName(), $indicator_info);   
                                        }else{
                                            $salidaIndicadores = $this->establecer('{INDICATOR_NAME}', $indicador->getName(), $indicator_info);   
                                        }
                                        if($is_passed){
                                            $salidaIndicadores = $this->establecer('{IS_APPROVED}', "#PASSED# ({$calification}/5)", $salidaIndicadores);  
                                        }else{
                                            $salidaIndicadores = $this->establecer('{IS_APPROVED}', '#NO_PASSED#', $salidaIndicadores); 
                                        }
                                        
                                 
                                        $salidaIndicadores = $this->establecer('{INDICATOR_MATERY}',  $data['CONTENT_MAIN']['INDICATORS_MATERY'][$competencia->getIdCompetencia()][$indicador->getIdIndicator()], $salidaIndicadores);  
                               
                                        $salidaIndicadores = $this->establecer('{INDICATOR_PLACE}', $data['CONTENT_MAIN']['INDICATORS_PLACE'][$competencia->getIdCompetencia()][$indicador->getIdIndicator()]->getNombre(), $salidaIndicadores);  

                                    }
                                }


                                if(isset($salidaIndicadores)){
                                    $salidaCelda = $this->establecer('{INDICATORS}', $salidaIndicadores, $salidaCelda); 
                                    unset($salidaIndicadores);
                                }else{
                                    $salidaCelda = $this->establecer('{INDICATORS}', null, $salidaCelda); 
                                }
              
                                
                                
                                
                                ++$contadorCompetenciasTipo;
                            
                        
                        $salidaFila .= $salidaCelda;
                    }  
                    
                }
                
                
                
          
                $salidaTipo = $this->establecer("{COMPETENCES_TYPE}", $nombresTiposCompetencia[$tipoCompetencia].'s', $competencesFrame);
                $salidaTipo = $this->establecer("{COMPETENCE_INFO}", $salidaFila, $salidaTipo);
                $salidaDetallesCompetencia .= $salidaTipo;
            }
    
           
        }
      
        
     
        
        $salidaHtml = $this->establecer('{COMPETENCES_TABLE}', $salidaCompetencias, $salidaHtml);
        $salidaHtml = $this->establecer('{COMPETENCE_DETAILED_FRAME}', $salidaDetallesCompetencia, $salidaHtml);
        $salidaHtml = $this->establecer('{SYSTEM_DATE}', $dateFormated, $salidaHtml);
       
  
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($GLOBALS["SYSTEM_NAME"]);
        $pdf->SetTitle('Historial de competencias');    
// set default header data
        $pdf->SetHeaderData('../../../../'.constant("FOLDER").'/view/images/commons/logo_report.png', 35, null,null);
// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/esp.php')) {
            require_once(dirname(__FILE__) . '/lang/esp.php');
            $pdf->setLanguageArray($l);
        }
        // set font
        $pdf->SetFont('helvetica', 'B', 20);
// add a page
        $pdf->AddPage();
        //       $pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);

        $pdf->SetFont('helvetica', '', 8);
        $salidaHtml = $this->apply_i18n($reports_list,$salidaHtml);
        $pdf->writeHTML($salidaHtml, true, false, false, false, '');  
        ob_end_clean();
        $pdf->Output('informe.pdf','D');
    }

}
