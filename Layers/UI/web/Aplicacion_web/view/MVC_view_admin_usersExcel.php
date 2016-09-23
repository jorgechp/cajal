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
define("DOCUMENT_NAME", "listado_usuarios");
/**
 * Description of MVC_view_adminCompetencesExcel
 *
 * @author jorge
 */
class MVC_view_admin_usersExcel extends MVC_view_messages{ 
    
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
        require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'Users.php');
        $objPHPExcel = new PHPExcel();
        if(!$data['NO_USERS']){  
            $columnaInicialTitulo = ord('A');
            $filaInicialTitulo = 3;

            $areas = $data['USER_AREAS'];
            $centros = $data['USER_CENTRES'];
            
            
            $objPHPExcel->setActiveSheetIndex(0);
            
            $arrayTitle = explode(' ', $this->apply_i18n($strings_users, 'DOCUMENT_TITLE'));
      
         
            for ($index = 0; $index < count($arrayTitle); $index++) {
                $columna = chr($columnaInicialTitulo) . $filaInicialTitulo;
                $objPHPExcel->getActiveSheet()->SetCellValue($columna, $arrayTitle[$index]);


                //Estilo de la cabecera
                $objPHPExcel->getActiveSheet()->getStyle($columna)->getFont()->setBold(true)
                        ->setName('Verdana')
                        ->setSize(24)
                        ->getColor()->setRGB(TITLE);

                $columnaInicialTitulo = $columnaInicialTitulo + 1;
            }
            // $strings_competences es la variable que recoge las cadenas traducidas
            
            $objPHPExcel->getActiveSheet()->SetCellValue('A5', $this->apply_i18n($strings_users, 'DOCUMENT_HEADER_NAME'));
            $this->cellColor($objPHPExcel, 'A5', HEADER1);
            $objPHPExcel->getActiveSheet()->SetCellValue('B5', $this->apply_i18n($strings_users, 'DOCUMENT_HEADER_LASTNAME1'));
            $this->cellColor($objPHPExcel, 'B5', HEADER1);            
            $objPHPExcel->getActiveSheet()->SetCellValue('C5', $this->apply_i18n($strings_users, 'DOCUMENT_HEADER_LASTNAME2'));
            $this->cellColor($objPHPExcel, 'C5', HEADER1);
            $objPHPExcel->getActiveSheet()->SetCellValue('D5', $this->apply_i18n($strings_users, 'DOCUMENT_HEADER_REALID'));
            $this->cellColor($objPHPExcel, 'D5', HEADER1);     
            $objPHPExcel->getActiveSheet()->SetCellValue('E5', $this->apply_i18n($strings_users, 'DOCUMENT_HEADER_MAIL'));
            $this->cellColor($objPHPExcel, 'E5', HEADER1); 
            $objPHPExcel->getActiveSheet()->SetCellValue('F5', $this->apply_i18n($strings_users, 'DOCUMENT_HEADER_PHONE'));
            $this->cellColor($objPHPExcel, 'F5', HEADER1); 
            $objPHPExcel->getActiveSheet()->SetCellValue('G5', $this->apply_i18n($strings_users, 'DOCUMENT_HEADER_CENTRE'));
            $this->cellColor($objPHPExcel, 'G5', HEADER1);         
            $objPHPExcel->getActiveSheet()->SetCellValue('H5', $this->apply_i18n($strings_users, 'DOCUMENT_HEADER_AREA'));
            $this->cellColor($objPHPExcel, 'H5', HEADER1);  
            
            
            $filaInicial = 6;   
            $rowCount = $filaInicial;
            foreach ($data['CONTENT_MAIN'] as $user) {                
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $user->getNombre());
                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $user->getApellido1());
                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $user->getApellido2());
                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $user->getDNI());
                $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $user->getMail());
                $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $user->getPhone());
                
      
            
                foreach ($areas as $area) {
                    if($area->getIdArea() == $user->getIdArea()){
                        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $area->getNombre()); 
                    }
                }
                
                foreach ($centros as $centro) {
                    if($centro->getIdCentre() == $user->getIdCentro()){
                        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $centro->getNombre()); 
                    }
                }          
                
                
            
                
 
                ++$rowCount;
     
            }
            
            foreach(range('A','I') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
 
            

              foreach (range(1, $rowCount) as $rowID) {
                    $objPHPExcel->getActiveSheet()->getRowDimension($rowID)->setRowHeight(31);
            }

             
            
        }else{
            $objPHPExcel->getActiveSheet()->SetCellValue('A1',$this->apply_i18n($strings_users, 'NO_USERS_ERROR'));
        }
        
     
        $this->saveData($objPHPExcel);
        
    }

}
