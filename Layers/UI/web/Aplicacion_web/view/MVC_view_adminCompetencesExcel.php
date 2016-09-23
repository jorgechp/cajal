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
define("DOCUMENT_NAME", "listado_competencias");
/**
 * Description of MVC_view_adminCompetencesExcel
 *
 * @author jorge
 */
class MVC_view_adminCompetencesExcel extends MVC_view_messages{ 
    
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
        if(!$data['NO_COMPETENCES']){  
            $columnaInicialTitulo = ord('A');
            $filaInicialTitulo = 3;
            
            $areas = $data['COMPETENCE_AREAS'];
            $materias = $data['COMPETENCE_MATERIAS'];

            
            
            $objPHPExcel->setActiveSheetIndex(0);
            
            require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Competences.php');
//            $arrayTitle = array($this->apply_i18n($strings_competences,'DOCUMENT_TITLE'));
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
            // $strings_competences es la variable que recoge las cadenas traducidas
            if(isset($data['INCLUDE_INDICATORS'])){
                $objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->apply_i18n($strings_competences, 'DOCUMENT_HEADER_ENTRY'));
                $this->cellColor($objPHPExcel, 'A1', HEADER1);                
                $objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->apply_i18n($strings_competences, 'DOCUMENT_HEADER_NAME'));
                $this->cellColor($objPHPExcel, 'B1', HEADER1);
                $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->apply_i18n($strings_competences, 'DOCUMENT_HEADER_CODE'));
                $this->cellColor($objPHPExcel, 'C1', HEADER1);            
                $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->apply_i18n($strings_competences, 'DOCUMENT_HEADER_DESCRIPTION'));
                $this->cellColor($objPHPExcel, 'D1', HEADER1);
                $objPHPExcel->getActiveSheet()->SetCellValue('E1', $this->apply_i18n($strings_competences, 'DOCUMENT_HEADER_TYPE'));
                $this->cellColor($objPHPExcel, 'E1', HEADER1);   

           
            }else{
                $objPHPExcel->getActiveSheet()->SetCellValue('A1', $this->apply_i18n($strings_competences, 'DOCUMENT_HEADER_NAME'));
                $this->cellColor($objPHPExcel, 'A1', HEADER1);
                $objPHPExcel->getActiveSheet()->SetCellValue('B1', $this->apply_i18n($strings_competences, 'DOCUMENT_HEADER_CODE'));
                $this->cellColor($objPHPExcel, 'B1', HEADER1);            
                $objPHPExcel->getActiveSheet()->SetCellValue('C1', $this->apply_i18n($strings_competences, 'DOCUMENT_HEADER_DESCRIPTION'));
                $this->cellColor($objPHPExcel, 'C1', HEADER1);
                $objPHPExcel->getActiveSheet()->SetCellValue('D1', $this->apply_i18n($strings_competences, 'DOCUMENT_HEADER_TYPE'));
                $this->cellColor($objPHPExcel, 'D1', HEADER1);   

                    
            }

            
            
            $filaInicial = 2;   
            $rowCount = $filaInicial;
            foreach ($data['CONTENT_MAIN'] as $competence) {    
                if(isset($data['INCLUDE_INDICATORS'])){
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'competencia');
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $competence->getName());
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $competence->getCode());
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $competence->getDescription());
                
                
                    $tipo = $competence->getIdType();
                    if($tipo == 1){
                        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,  $this->apply_i18n($strings_competences, 'DOCUMENT_COMPETENCE_0'));
                    }
                    if($tipo == 2){
                        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,  $this->apply_i18n($strings_competences, 'DOCUMENT_COMPETENCE_1'));
                    }
                    if($tipo == 3){
                        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,  $this->apply_i18n($strings_competences, 'DOCUMENT_COMPETENCE_2'));
                    }     
                    
           

                    
                }else{
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $competence->getName());
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $competence->getCode());
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $competence->getDescription());
                
                
                    $tipo = $competence->getIdType();
                    if($tipo == 1){
                        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,  $this->apply_i18n($strings_competences, 'DOCUMENT_COMPETENCE_0'));
                    }
                    if($tipo == 2){
                        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,  $this->apply_i18n($strings_competences, 'DOCUMENT_COMPETENCE_1'));
                    }
                    if($tipo == 3){
                        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,  $this->apply_i18n($strings_competences, 'DOCUMENT_COMPETENCE_2'));
                    }  
                    
                   
                }
                ++$rowCount;
                
                if(isset($data['INCLUDE_INDICATORS'])){

                    $array = $data['ACTIVITY_LIST']['INDICATORS'][$competence->getIdCompetencia()];
                    

                    if(is_array($array)){
   
                        foreach ($array as $indicador) {
                            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'indicador');
                            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $indicador->getName());
                            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $indicador->getCode());
                            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $indicador->getDescription());
                            ++$rowCount;
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
            $objPHPExcel->getActiveSheet()->SetCellValue('A1',$this->apply_i18n($strings_competences, 'NO_COMPETENCES_ERROR'));
        }
        
     
        $this->saveData($objPHPExcel);
        
    }

}
