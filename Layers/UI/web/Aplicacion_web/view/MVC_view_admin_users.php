<?php

/**
 * Description of MVC_view_admin_users
 *
 * @author jorge
 */
class MVC_view_admin_users extends MVC_view_messages {

    protected function apply_data($data) {
        $tiposTemplate = file_get_contents(constant("FOLDER") . "/view/structure/adminUsers/typesTemplate.html");
        $template = file_get_contents(constant("FOLDER") . "/view/structure/adminUsers/contentMain.html");
        $template_list = file_get_contents(constant("FOLDER") . "/view/structure/adminUsers/list.html");
        $template_list_element = file_get_contents(constant("FOLDER") . "/view/structure/adminUsers/element.html");
        $centre_element= file_get_contents(constant("FOLDER") . "/view/structure/adminUsers/centros.html");
        $area_element = file_get_contents(constant("FOLDER") . "/view/structure/adminUsers/areas.html");

        

        $this->establecer('{TIPOS_TITLE}', '#ADMIN_USERS_TITLE#');
        $this->establecer('{TIPOS}', $tiposTemplate);
        unset($tiposTemplate);
        $salidaFinal = $template;
        
   
        
        
    
        if (isset($data['INSERTION_DATA'])) {
           
            if ($data['INSERTION_DATA']['CONTENT_MAIN']['UPLOADED_FILE']) {
                if (array_key_exists('INSERTIONS_OK', $data['INSERTION_DATA']['CONTENT_MAIN'])) {
                    if ($data['INSERTION_DATA']['CONTENT_MAIN']['INSERTIONS_OK']) {
                        $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#INSERTION_STUDENTS_OK1# ' . $data['INSERTION_DATA']['CONTENT_MAIN']['INSERTIONS'] . ' #INSERTION_STUDENTS_OK1#', $salidaFinal);
                    } else {
                        if(count($data['INSERTION_DATA']['CONTENT_MAIN']['ERROR_LIST']) == 0){
                            $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#INSERTION_STUDENTS_FAILED_1# ' . $data['INSERTION_DATA']['CONTENT_MAIN']['INSERTIONS'] . ' #INSERTION_STUDENTS_FAILED_2#', $salidaFinal);
                        }else{
                           $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#INSERTION_STUDENTS_FAILED_1# ' . $data['INSERTION_DATA']['CONTENT_MAIN']['INSERTIONS'] . ' #INSERTION_STUDENTS_FAILED_3#', $salidaFinal);
                           $salidaGenerada = "";
                           foreach ($data['INSERTION_DATA']['CONTENT_MAIN']['ERROR_LIST'] as $linea) {
                               $linea_Error = $this->establecer('{ELEMENT}', $linea, $template_list_element);
                               $salidaGenerada .= $linea_Error;
                           }                     
                            $salida_lista = $this->establecer('{LIST_ELEMENT}', $salidaGenerada, $template_list);
                            $salidaFinal = $this->establecer('{LIST_ELEMENT}', $salida_lista, $salidaFinal);
                        }
                        
                        

                       

                        $salidaFinal = $this->establecer('{LINE}', '<hr>', $salidaFinal);
                    }
                } else if (array_key_exists('INVALID_FORMAT', $data['INSERTION_DATA']['CONTENT_MAIN'])) {
                    if ($data['INSERTION_DATA']['CONTENT_MAIN']['INVALID_FORMAT']) {
                        $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#INVALID_FORMAT#', $salidaFinal);
                    }
                }
            }
        } else if (isset($data['INSERT'])) {
            if ($data['INSERT']) {
                $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#INSERTION_OK#', $salidaFinal);
            } else {
                $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#INSERTION_FAIL#', $salidaFinal);


                foreach ($data['VALIDATION_DATA'] as $clave => $campo) {
                    if ($campo != false) {
                        $salidaFinal = $this->establecer('{' . $clave . '}', $campo, $salidaFinal);
                    }
                }
            }
        } else {
            $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#NO_USERS#', $template);
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
        if (!$data['NO_USERS']) {


            $templateElement = file_get_contents(constant("FOLDER") . "/view/structure/adminUsers/tableElement.html");
            $table = file_get_contents(constant("FOLDER") . "/view/structure/adminUsers/table.html");

            $salida = "";

           
            foreach ($data['CONTENT_MAIN'] as $user) {
                
                $salida .= $this->establecer('{USER_IDENTIFICATOR}', $user->getIdUsuario(), $templateElement);
                $salida = $this->establecer('{USER_NAME}', $user->getNombre(), $salida);
                $salida = $this->establecer('{USER_LASTNAME1}', $user->getApellido1(), $salida);
                $salida = $this->establecer('{USER_LASTNAME2}', $user->getApellido2(), $salida);
                $salida = $this->establecer('{USER_REALID}', $user->getDNI(), $salida);
                $salida = $this->establecer('{USER_MAIL}', $user->getMail(), $salida);
                $salida = $this->establecer('{USER_PASSWORD}', null, $salida);
                $salida = $this->establecer('{USER_PHONE}', $user->getPhone(), $salida);
                $salida = $this->establecer('{USER_ROL}', $user->getRol(), $salida);
                $salida = $this->establecer('{USER_ROL}', $user->getRol(), $salida);
                $salida = $this->establecer('{USER_ROL}', $user->getRol(), $salida);
                
                $listaAreas = "";
                foreach ($data['AREAS']  as $areas) {
                    $nueva_area = $this->establecer('{AREA_ID}',$areas->getIdArea(),$area_element);
                    $nueva_area = $this->establecer('{AREA_NAME}',$areas->getNombre(),$nueva_area);
                    if($areas->getIdArea() == $user->getIdArea()){
                        $nueva_area = $this->establecer('{SELECT}','selected',$nueva_area);
                    }else{
                        $nueva_area = $this->establecer('{SELECT}',null,$nueva_area);
                    }
                        
                    $listaAreas.=$nueva_area;            
                }

                $listaCentros = "";
                foreach ($data['CENTROS']  as $centres) {
                    $nuevo_centro = $this->establecer('{CENTRO_ID}',$centres->getIdCentre(),$centre_element);
                    $nuevo_centro = $this->establecer('{CENTRO_NAME}',$centres->getNombre(),$nuevo_centro);
                    if($centres->getIdCentre() == $user->getIdCentro()){                        
                        $nuevo_centro = $this->establecer('{SELECT}','selected',$nuevo_centro);
                    }else{
                        $nuevo_centro = $this->establecer('{SELECT}',null,$nuevo_centro);
                    }
                        
                    $listaCentros.=$nuevo_centro;            
                }               
                
                $salida = $this->establecer('{LIST_USER_AREAS}', $listaAreas, $salida);  
                $salida = $this->establecer('{LIST_USER_CENTRES}', $listaCentros, $salida);
            }

            $salidaFinal = $this->establecer('{TABLE}', $table, $template);
            $salidaFinal = $this->establecer('{USER_LIST}', $salida, $salidaFinal);
            $salidaFinal = $this->establecer('{DNICHAIN}', $data['EXCEL_CHAIN'], $salidaFinal);
            if(isset($data['EXCEL_CHAIN_PARAM'])){
                if(strcmp('CENTRE', $data['EXCEL_CHAIN']) == 0){
                    $salidaFinal = $this->establecer('{DNICENTRE}', $data['EXCEL_CHAIN_PARAM'], $salidaFinal);    
                }            
                if(strcmp('AREA', $data['EXCEL_CHAIN']) == 0){
                    $salidaFinal = $this->establecer('{DNIAREA}', $data['EXCEL_CHAIN_PARAM'], $salidaFinal);    
                }
            }
            if(isset($data['EXCEL_CHAIN_PARAM_AREA']) && isset($data['EXCEL_CHAIN_PARAM_CENTRE'])){
                if(strcmp('FILTER', $data['EXCEL_CHAIN']) == 0){
                    $salidaFinal = $this->establecer('{DNIAREA}', $data['EXCEL_CHAIN_PARAM_AREA'], $salidaFinal); 
                    $salidaFinal = $this->establecer('{DNICENTRE}', $data['EXCEL_CHAIN_PARAM_CENTRE'], $salidaFinal);    
                }
            }
  

            
        } 
            
        $listaAreas = "";
        if(is_array($data['AREAS'])){
            foreach ($data['AREAS']  as $areas) {
                $nueva_area = $this->establecer('{AREA_ID}',$areas->getIdArea(),$area_element);
                $nueva_area = $this->establecer('{AREA_NAME}',$areas->getNombre(),$nueva_area);
                $nueva_area = $this->establecer('{SELECT}',null,$nueva_area);
                $listaAreas.=$nueva_area;            
            }
        }
        
        $listaCentros = "";
        if(is_array($data['CENTROS'])){
            foreach ($data['CENTROS']  as $centros) {
                $nueva_area = $this->establecer('{CENTRO_ID}',$centros->getIdCentre(),$centre_element);
                $nueva_area = $this->establecer('{CENTRO_NAME}',$centros->getNombre(),$nueva_area);
                $nueva_area = $this->establecer('{SELECT}',null,$nueva_area);
                $listaCentros.=$nueva_area;            
            }
        }
        

        
        
        $salidaFinal = $this->establecer('{LIST_AREAS}', $listaAreas, $salidaFinal);  
        $salidaFinal = $this->establecer('{LIST_CENTRES}', $listaCentros, $salidaFinal);
        $this->establecer('{CONTENT_MAIN}', $salidaFinal);
        
    }

}
