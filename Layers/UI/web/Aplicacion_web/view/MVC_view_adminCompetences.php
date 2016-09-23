<?php

require_once(constant("FOLDER") . '/view/MVC_view_messages.php');

class MVC_view_adminCompetences extends MVC_view_messages {

    protected function apply_data($data) {
        $template = file_get_contents(constant("FOLDER") . "/view/structure/adminCompetences/contentMain.html");
        $tiposTemplate = file_get_contents(constant("FOLDER") . "/view/structure/adminCompetences/typesTemplate.html");
        $optionTemplate = file_get_contents(constant("FOLDER") . "/view/structure/adminCompetences/selectOption.html");
        $table = file_get_contents(constant("FOLDER") . "/view/structure/adminCompetences/table.html");

        $this->establecer('{TIPOS_TITLE}', '#ADMIN_COMPETENCES_TITLE#');
        $this->establecer('{TIPOS}', $tiposTemplate);
        unset($tiposTemplate);
        
        
        $salidaOpciones = "";
        $salidaFinal = null;
        
        
        if(is_array($data['COMPETENCE_AREAS'])){
            $tam = count($data['COMPETENCE_AREAS']);
            for ($index = 0; $index < $tam; $index++) {
  
                $salidaOpciones .= $this->establecer('{TYPE_ID}', $data['COMPETENCE_AREAS'][$index]->getIdTipo(), $optionTemplate);
                $salidaOpciones = $this->establecer('{VALUE_NAME}', $data['COMPETENCE_AREAS'][$index]->getNombre(), $salidaOpciones);
                $salidaOpciones = $this->establecer('{IS_SELECTED}', null, $salidaOpciones);
            }
        }

        $salidaFinal = $this->establecer('{OPTIONS_AREA}', $salidaOpciones, $template);
        $salidaOpciones = "";
        if(is_array($data['COMPETENCE_MATERIAS'])){
            $tam = count($data['COMPETENCE_MATERIAS']);
            for ($index = 0; $index < $tam; $index++) {
                $salidaOpciones .= $this->establecer('{TYPE_ID}', $data['COMPETENCE_MATERIAS'][$index]->getIdMateria(), $optionTemplate);
                $salidaOpciones = $this->establecer('{VALUE_NAME}', $data['COMPETENCE_MATERIAS'][$index]->getNombre(), $salidaOpciones);
                $salidaOpciones = $this->establecer('{IS_SELECTED}', null, $salidaOpciones);
            }
        }
        $salidaFinal = $this->establecer('{COMPETECENCE_TYPE_LIST}', $salidaOpciones, $salidaFinal);
        $salidaFinal = $this->establecer('{OPTIONS_MATERY}', $salidaOpciones, $salidaFinal);
        
        $salidaOpciones = "";
        if(is_array($data['COMPETENCE_AREAS'])){
            $tam = count($data['COMPETENCE_AREAS']);

            for ($index = 0; $index < $tam; $index++) {
               
                $salidaOpciones .= $this->establecer('{TYPE_ID}', $data['COMPETENCE_AREAS'][$index]->getIdTipo(), $optionTemplate);
                $salidaOpciones = $this->establecer('{VALUE_NAME}', $data['COMPETENCE_AREAS'][$index]->getNombre(), $salidaOpciones);
                $salidaOpciones = $this->establecer('{IS_SELECTED}', null, $salidaOpciones);
            }
        }
        
        $salidaFinal = $this->establecer('{COMPETECENCE_AREA_LIST}', $salidaOpciones, $salidaFinal);
        
         if (isset($data['INSERT'])) {          
               
                if ($data['INSERT']) {
                    $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#INSERTION_OK#', $salidaFinal);
                } else {
                    if(isset($data['ALREADY_EXISTS']) && $data['ALREADY_EXISTS']){
                        $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#ALREADY_EXISTS#', $salidaFinal);
                    }else{
                        $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#INSERTION_FAIL#', $salidaFinal);
                    }

                    
                    foreach ($data['VALIDATION_DATA'] as $clave => $campo) {
                        if ($campo != false) {
                            $salidaFinal = $this->establecer('{' . $clave . '}', $campo, $salidaFinal);
                        }
                    }
                }
            }
            if (isset($data['CHANGE'])) {
                if ($data['CHANGE']) {
                    $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#CHANGE_OK#', $salidaFinal);
                } else {
                    $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#CHANGE_FAIL#', $salidaFinal);
                }
            }
            if (isset($data['DELETE'])) {
                if ($data['DELETE']) {
                    $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#DELETE_OK#', $salidaFinal);
                } else {
                    $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#DELETE_FAIL#', $salidaFinal);
                }
            }
        
        if (!$data['NO_COMPETENCES']) {

            $templateElement = file_get_contents(constant("FOLDER") . "/view/structure/adminCompetences/tableElement.html");

            $salida = "";
           
            foreach ($data['CONTENT_MAIN'] as $competence) {
                $salida .= $this->establecer('{COMPETENCE_IDENTIFICATOR}', $competence->getIdCompetencia(), $templateElement);
                $salida = $this->establecer('{COMPETENCE_NAME}', $competence->getName(), $salida);
                $salida = $this->establecer('{COMPETENCE_DESCRIPTION}', $competence->getDescription(), $salida);
                $salida = $this->establecer('{COMPETENCE_CODE}', $competence->getCode(), $salida);
                $salida = $this->establecer('{IMG_FOLDER}', constant("FOLDER") . "/view/images/commons", $salida);
                if ($competence->getIsActive()) {
                    $salida = $this->establecer('{COMPETENCE_ISCHECKED}', 'checked', $salida);
                } else {
                    $salida = $this->establecer('{COMPETENCE_ISCHECKED}', null, $salida);
                }
                $salidaOpciones = "";
                $tam = count($data['COMPETENCE_TYPES']);
                for ($index = 0; $index < $tam; $index++) {
                    $salidaOpciones .= $this->establecer('{TYPE_ID}', $data['COMPETENCE_TYPES'][$index]->getIdTypeCompetence(), $optionTemplate);
                    $salidaOpciones = $this->establecer('{VALUE_NAME}', $data['COMPETENCE_TYPES'][$index]->getName(), $salidaOpciones);
                    if ($competence->getIdType() == $data['COMPETENCE_TYPES'][$index]->getIdTypeCompetence()) {
                        $salidaOpciones = $this->establecer('{IS_SELECTED}', ' selected', $salidaOpciones);
                    } else {
                        $salidaOpciones = $this->establecer('{IS_SELECTED}', null, $salidaOpciones);
                    }
                }
                $salida = $this->establecer('{COMPETECENCE_TYPES}', $salidaOpciones, $salida);
                $salidaOpciones = "";

        
                $salida = $this->establecer('{COMPETECENCE_AREA_LIST}', $salidaOpciones, $salida);                
            }
            
            $salidaFinal = $this->establecer('{TABLE}', $table, $salidaFinal);
            $salidaFinal = $this->establecer('{COMPETECENCE_TYPE_LIST}', $salidaOpciones, $salidaFinal);
            $salidaFinal = $this->establecer('{COMPETENCE_LIST}', $salida, $salidaFinal);
            $salidaFinal = $this->establecer('{EXCEL_CHAIN}', $data['EXCEL_CHAIN'], $salidaFinal);
            $salidaFinal = $this->establecer('{EXCEL_AREA}', $data['EXCEL_AREA'], $salidaFinal);
            $salidaFinal = $this->establecer('{EXCEL_MATERIA}', $data['EXCEL_MATERIA'], $salidaFinal);



            $this->establecer('{CONTENT_MAIN}', $salidaFinal);
        } else {
            
 
            $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#NO_COMPETENCES#', $salidaFinal);
            $this->establecer('{CONTENT_MAIN}', $salidaFinal);
        }
    }

}
