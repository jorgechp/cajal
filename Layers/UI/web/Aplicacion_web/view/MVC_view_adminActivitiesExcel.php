<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');

require_once 'lib/PHPExcel/PHPExcel.php';
require_once (ROOT . 'PasswordManager.php');


define("PASS_COLOR", "0000FF");
define("FAIL_COLOR", "F00000");
define("ODD_ROW", "D8D8D8");
define("EVEN_ROW", "F8E0E6");
define("TITLE", "6F6F6F");
define("HEADER1", "F5F6CE");
define("HEADER2", "F5D0A9");
define("HEADER3", "E6F8E0");
define("DOCUMENT_NAME", "listado_actividades");
/**
 * Description of MVC_view_adminActivities
 *
 * @author jorge
 */
class MVC_view_adminActivitiesExcel extends MVC_view_messages{ 
    
    private function saveData($objPHPExcel) {
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        ob_end_clean();

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=' . DOCUMENT_NAME . date('dmy_hms') . '.xls');
        
        $objWriter->save('php://output');
        //$objWriter->save(ROOT . 'tmp/' . DOCUMENT_NAME.date('dmy_hms').'.xls');
    }
    
    private function cellColor($objPHPExcel, $cells, $color) {
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()
                ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array('rgb' => $color)
        ));
    }
    
    protected function apply_i18n($diccionarios) {
        return $diccionarios[func_get_arg(1)];
    }
    
    protected function apply_data($data) {
        
        $objPHPExcel = new PHPExcel();
        if(!$data['NO_ACTIVITIES']){  
            $columnaInicialTitulo = ord('A');
            $filaInicialTitulo = 3;

            $areaActividad = $data['ACTIVITIES_AREA'];
            $areaCategoria = $data['ACTIVITIES_CATEGORY'];
            $areaCompetencia = $data['COMPETENCES_AREA'];
            


                    
            $objPHPExcel->setActiveSheetIndex(0);
            
            require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Activities.php');
//            $arrayTitle = explode(' ', $this->apply_i18n($activities_list, 'DOCUMENT_TITLE'));
//
//            for ($index = 0; $index < count($arrayTitle); $index++) {
//                $columna = chr($columnaInicialTitulo) . $filaInicialTitulo;
//                $objPHPExcel->getActiveSheet()->SetCellValue($columna, $arrayTitle[$index]);
//
//
//                //Estilo de la cabecera
//                $objPHPExcel->getActiveSheet()->getStyle($columna)->getFont()->setBold(true)
//                        ->setName('Verdana')
//                        ->setSize(24)
//                        ->getColor()->setRGB(TITLE);
//
//                $columnaInicialTitulo = $columnaInicialTitulo + 1;
//            }
            // $activities_list es la variable que recoge las cadenas traducidas
            
            if(isset($data['INCLUDE_COMPETENCES'])){
                $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Inserción');
                $this->cellColor($objPHPExcel, 'A1', HEADER1);
                $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Nombre');
                $this->cellColor($objPHPExcel, 'B1', HEADER1);            
                $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Código');
                $this->cellColor($objPHPExcel, 'C1', HEADER1);
                $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Descripción');
                $this->cellColor($objPHPExcel, 'D1', HEADER1);                
                $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Tipo');
                $this->cellColor($objPHPExcel, 'E1', HEADER1);
                $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Observaciones');
                $this->cellColor($objPHPExcel, 'F1', HEADER1);  
                    
            }else{
                $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Nombre');
                $this->cellColor($objPHPExcel, 'A1', HEADER1);            
                $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Código');
                $this->cellColor($objPHPExcel, 'B1', HEADER1);
                $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Descripción');
                $this->cellColor($objPHPExcel, 'C1', HEADER1);             
                $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Tipo');
                $this->cellColor($objPHPExcel, 'D1', HEADER1);                    
            }


            
            
            $filaInicial = 2;   
            $rowCount = $filaInicial;
            foreach ($data['CONTENT_MAIN'] as $activity) {     
                if(isset($data['INCLUDE_COMPETENCES'])){
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'actividad');
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $activity->getNombre());
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $activity->getCodigo());
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $activity->getDescripcion());
                    
                    
                    foreach ($data['ACTIVITIES_CATEGORY'] as $area) {
                        if($activity->getIdCategoria() == $area->getIdCategoria()){
                            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $area->getNombreCategoria());
                        }
                    }

                
                }else{                    
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $activity->getNombre());
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $activity->getCodigo());
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $activity->getDescripcion());   
                    
 
                    foreach ($areaCategoria as $area) {
                        if($area->getIdCategoria() == $activity->getIdCategoria()){
                            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $area->getNombreCategoria());
                        }
                    }                         
                }
                
                ++$rowCount;
                
                if(isset($data['INCLUDE_COMPETENCES'])){
                    if(isset($data['ACTIVITY_LIST']['COMPETENCES'][$activity->getIdActividad()])){
                        $array = $data['ACTIVITY_LIST']['COMPETENCES'][$activity->getIdActividad()];
                        if($array != null && count($array) > 0){
                            foreach ($array as $competencia){
                                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'competencia');
                                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $competencia->getName());
                                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $competencia->getCode());
                                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $competencia->getDescription());
                                $tipo = $competencia->getIdType();
                                
                                if($tipo == 1){
                                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'básica');
                                }
                                else if($tipo == 2){
                                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'intermedia');
                                }
                                else if($tipo == 3){
                                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'avanzada');
                                }
                                $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $competencia->getObservations());
                                
                                 
                                
                                ++$rowCount;

                                if(isset($data['INCLUDE_INDICATORS'])){
                                    if(isset($data['ACTIVITY_LIST']['INDICATORS'][$competencia->getIdCompetencia()])){
                                        $array = $data['ACTIVITY_LIST']['INDICATORS'][$competencia->getIdCompetencia()];
                                        if($array != null && count($array) > 0){
                                            foreach ($data['ACTIVITY_LIST']['INDICATORS'][$competencia->getIdCompetencia()] as $indicador){
                                                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'indicador');
                                                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $indicador->getName());
                                                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $indicador->getCode());
                                                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $indicador->getDescription());                                
                                                ++$rowCount;
                                            }
                                        }

                                    }
                                }
                            }
                        }
                    }

                    
                }
            }
            
            foreach(range('A','G') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
 
            

              foreach (range(1, $rowCount) as $rowID) {
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowID)->setRowHeight(31);
            }

             
            
        }else{
            $objPHPExcel->getActiveSheet()->SetCellValue('A1','NO HAY ACTIVIDADES CON EL CRITERIO DE BÚSQUEDA ESPECIFICADO');
        }
        
     
        $this->saveData($objPHPExcel);
        
    }

}
