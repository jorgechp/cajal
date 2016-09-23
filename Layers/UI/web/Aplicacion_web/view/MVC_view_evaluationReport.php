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
class MVC_view_evaluationReport extends MVC_view_messages {

    /**
     * Convierte un valor numérico en caracteres que representan columnas de una
     * hoja de Excel.
     * 
     * Cortesía de ircmaxell
     * http://stackoverflow.com/questions/3302857/algorithm-to-get-the-excel-like-column-name-of-a-numbe
     * 
     * @param int $num
     * @return String
     */
    private function getNameFromNumber($num) {
	$num = $num; //A en ASCII equivale a 65
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->getNameFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }

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

        $columnaInicialRango = 'A';
        $columnaPunteroCabecera = ord('C')-65;
        $filaPunteroCabecera = 7;
        $columnaInicialTitulo = ord('D');
        $filaInicialTitulo = 3;

        $columnaPuntero = ord($columnaInicialRango)-65;
        $filaPuntero = 10;
        $filaActiva = false;

        $dateFormated = date($GLOBALS["SYSTEM_LONG_TIMEFORMAT"]);
        require(constant("FOLDER") . "/i18n/" . $GLOBALS['UI_LANG'] . $GLOBALS['UI_LANG_VARIANT'] . 'EvaluationReport.php');
        // $evaluationReportString es la variable que recoge las cadenas traducidas

        $objPHPExcel = new PHPExcel();
        //El título puede ser muy extenso, así que se divide por espacios
        $arrayTitle = explode(' ', $this->apply_i18n($evaluationReportString, 'DOCUMENT_TITLE'));

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


        $objPHPExcel->getActiveSheet()->SetCellValue('A7', $this->apply_i18n($evaluationReportString, 'DOCUMENT_STUDENT_NAME'));
        $objPHPExcel->getActiveSheet()->SetCellValue('B4', $this->apply_i18n($evaluationReportString, 'DOCUMENT_DATE'));
        $objPHPExcel->getActiveSheet()->SetCellValue('B5', $this->apply_i18n($evaluationReportString, 'DOCUMENT_ACTIVITY'));
        $objPHPExcel->getActiveSheet()->SetCellValue('C5',  $data['nameActivity']);
        $objPHPExcel->getActiveSheet()->SetCellValue('C4', $dateFormated);
        $objPHPExcel->getActiveSheet()->SetCellValue('B7', $this->apply_i18n($evaluationReportString, 'DOCUMENT_STUDENT_DNI'));


        $objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true);




//        Generación de cabeceras

        foreach ($data['competences'] as $competencia) {
            $celda = $this->getNameFromNumber($columnaPunteroCabecera) . $filaPunteroCabecera;
	
            //Por cada competencia, obtenemos sus indicadores
            $indicadores = $data['indicators'][$competencia->getIdCompetencia()];
            $tamIndicadores = count($indicadores);


            //Primero se escribe el código de la competencia
            $objPHPExcel->getActiveSheet()->SetCellValue($celda, $competencia->getCode());
            $this->cellColor($objPHPExcel, $celda, HEADER3);
            //Se pone en negrita           
            $objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setBold(true);

            //Ahora los indicadores
            if ($tamIndicadores > 0 && $indicadores != false) {
                //Bajar una fila                    
                ++$filaPunteroCabecera;
                foreach ($indicadores as $indicador) {
                    $celda = $this->getNameFromNumber($columnaPunteroCabecera) . $filaPunteroCabecera;
			
                    $objPHPExcel->getActiveSheet()->SetCellValue($celda, $indicador->getCode());
                    $this->cellColor($objPHPExcel, $celda, HEADER2);
                    //Se pone en negrita           
                    $objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setBold(true);

                    //Cada indicador tiene sesiones
                    ++$filaPunteroCabecera;
                    foreach ($data['sessions'] as $session) {
                        $celda = $this->getNameFromNumber($columnaPunteroCabecera) . $filaPunteroCabecera;
			
                        $objPHPExcel->getActiveSheet()->SetCellValue($celda, $session);
                        $this->cellColor($objPHPExcel, $celda, HEADER1);

			$columnaPunteroCabecera++;
			
                        
                    }
                    --$filaPunteroCabecera;
                }
                //Subir una fila
                --$filaPunteroCabecera;
            }
        }



        //Una vez generadas las cabeceras, se introducen datos de estudiantes
        //$columnaInicial recupera los datos de la columna inicial
        $columnaInicial = $columnaPuntero;
        $filaInicial = $filaPuntero;
        $contadorEstudiante = 0;

        foreach ($data['students'] as $student) {

            $columnaActual = $columnaInicial;
            $filaActual = $filaInicial + $contadorEstudiante;
            $celda = $this->getNameFromNumber($columnaActual) . $filaActual;
		
            $objPHPExcel->getActiveSheet()->SetCellValue($celda, $student->getNombre() . ' ' . $student->getApellido1() . ' ' . $student->getApellido2());
            if ($filaActiva) {
                $this->cellColor($objPHPExcel, $celda, ODD_ROW);
            } else {
                $this->cellColor($objPHPExcel, $celda, EVEN_ROW);
            }
            ++$columnaActual;
            $celda = $this->getNameFromNumber($columnaActual) . $filaActual;
            $objPHPExcel->getActiveSheet()->SetCellValue($celda, $student->getDNI());
            $columnaActual++;
            if ($filaActiva) {
                $this->cellColor($objPHPExcel, $celda, ODD_ROW);
            } else {
                $this->cellColor($objPHPExcel, $celda, EVEN_ROW);
            }


            /*
             * PRIMERO, SE RELLENAN LOS DATOS DE CADA TABLA
             */

            foreach ($data['competences'] as $competencia) {

                if (is_array($data['indicators'][$competencia->getIdCompetencia()])) {
                    $contador = 0;

                    foreach ($data['indicators'][$competencia->getIdCompetencia()] as $indicator) {

                        //$historico almacena, para cada usuario, cada competencia y cada indicador, un histórico de evaluaciones por cada sesión
                        $historico = $data['evaluation'][$student->getIdUsuario()][$competencia->getIdCompetencia()][$indicator->getIdIndicator()];

                        $contador = 0;
                        foreach ($data['sessions'] as $idSession => $sessions) {
                            $celda = $this->getNameFromNumber($columnaActual) . $filaActual;
                            if (isset($historico[$contador])) {
                                $calificacion = $historico[$contador]->getEvaluacion();
                                $objPHPExcel->getActiveSheet()->SetCellValue($celda, $calificacion);
                                if ($calificacion > 2) {
                                    $objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->getColor()->applyFromArray(array("rgb" => PASS_COLOR));
                                } else {
                                    $objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->getColor()->applyFromArray(array("rgb" => FAIL_COLOR));
                                }
                                if ($filaActiva) {
                                    $this->cellColor($objPHPExcel, $celda, ODD_ROW);
                                } else {
                                    $this->cellColor($objPHPExcel, $celda, EVEN_ROW);
                                }
                            } else {
                                $objPHPExcel->getActiveSheet()->SetCellValue($celda, '-');
                                if ($filaActiva) {
                                    $this->cellColor($objPHPExcel, $celda, ODD_ROW);
                                } else {
                                    $this->cellColor($objPHPExcel, $celda, EVEN_ROW);
                                }
                            }
                            ++$columnaActual;
                            ++$contador;
                        }
                    }
                }
            }




            ++$contadorEstudiante;
            $filaActiva = !$filaActiva;
        }

        //Finalmente, se ajusta el tamaño de las columnas

        $sumaValores =  ord($columnaInicialRango) + count($data['indicators']) * count($data['sessions']) + count($data['competences']);
	
        $valorColumna = null;
        foreach (range(ord('A'), $sumaValores) as $columnID) {

            if ($columnID-65 > 25) {
                $valorColumna = $this->getNameFromNumber($columnID-65);
            } else {
                $valorColumna = chr($columnID);
            }

            $objPHPExcel->getActiveSheet()->getColumnDimension($valorColumna)->setAutoSize(true);
        }

        $filaFinal = count($data['students']) + $filaPuntero;

        foreach (range(1, $filaFinal) as $rowID) {
            $objPHPExcel->getActiveSheet()->getRowDimension($rowID)->setRowHeight(31);
        }


        $this->saveData($objPHPExcel);

        die();
    }

}