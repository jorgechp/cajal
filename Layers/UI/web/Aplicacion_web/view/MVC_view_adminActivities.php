<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');

/**
 * Description of MVC_view_adminActivities
 *
 * @author jorge
 */
class MVC_view_adminActivities extends MVC_view_messages{ 
    protected function apply_data($data) {
        $tiposTemplate = file_get_contents(constant("FOLDER")."/view/structure/adminActivities/typesTemplate.html");
        $template = file_get_contents(constant("FOLDER")."/view/structure/adminActivities/contentMain.html");
        $templateElement = file_get_contents(constant("FOLDER")."/view/structure/adminActivities/tableElement.html");
        $table = file_get_contents(constant("FOLDER")."/view/structure/adminActivities/table.html");
        $optionTemplate = file_get_contents(constant("FOLDER")."/view/structure/adminActivities/selectOption.html");
        $this->establecer('{TIPOS_TITLE}', '#ADMIN_ACTIVITIES_TITLE#');
        $this->establecer('{TIPOS}', $tiposTemplate);
        unset($tiposTemplate);
        
   
        $salidaOpciones = "";
        $salidaFinal =  $template;
        
        if(isset($data['ASSOCIATION_INSERTIONS'])){
            $salidaFinal = $this->establecer('{CONTENT_INFORMATION}','#INSERT_ASSOCIATIONS# '.$data['ASSOCIATION_INSERTIONS'], $salidaFinal);
        }
        if(is_array($data['ACTIVITIES_AREA'])){
            $tam = count($data['ACTIVITIES_AREA']);
            for ($index = 0; $index < $tam; $index++) {
  
                $salidaOpciones .= $this->establecer('{TYPE_ID}', $data['ACTIVITIES_AREA'][$index]->getIdActividadTipo(), $optionTemplate);
                $salidaOpciones = $this->establecer('{VALUE_NAME}', $data['ACTIVITIES_AREA'][$index]->getNombre(), $salidaOpciones);
                $salidaOpciones = $this->establecer('{IS_SELECTED}', null, $salidaOpciones);
            }
        }
        
        $salidaFinal = $this->establecer('{OPTIONS_TITLE}', $salidaOpciones, $salidaFinal);
        
        
        $salidaOpciones = "";
        if(is_array($data['ACTIVITIES_CATEGORY'])){
            $tam = count($data['ACTIVITIES_CATEGORY']);
            for ($index = 0; $index < $tam; $index++) {
  
                $salidaOpciones .= $this->establecer('{TYPE_ID}', $data['ACTIVITIES_CATEGORY'][$index]->getIdCategoria(), $optionTemplate);
                $salidaOpciones = $this->establecer('{VALUE_NAME}', $data['ACTIVITIES_CATEGORY'][$index]->getNombreCategoria(), $salidaOpciones);
                $salidaOpciones = $this->establecer('{IS_SELECTED}', null, $salidaOpciones);
            }
        }
        
        $salidaFinal = $this->establecer('{ACTIVITIES_CATEGORY_LIST}', $salidaOpciones, $salidaFinal);
        $salidaFinal = $this->establecer('{OPTIONS_TYPE}', $salidaOpciones, $salidaFinal);
             
            if(isset($data['INSERT'])){
                if($data['INSERT']){
                    $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#INSERTION_OK#', $salidaFinal);
                }else{
                    $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#INSERTION_FAIL#', $salidaFinal);
                    
                    
                    foreach ($data['VALIDATION_DATA'] as $clave => $campo) {
                        if($campo != false){                            
                            $salidaFinal = $this->establecer('{'.$clave.'}', $campo, $salidaFinal);
                        }
                    }
                }
            }
            if(isset($data['CHANGE'])){
                if($data['CHANGE']){
                    $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#CHANGE_OK#', $salidaFinal);
                }else{
                    $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#CHANGE_FAIL#', $salidaFinal);
                }
            }
            if(isset($data['DELETE'])){
                if($data['DELETE']){
                   $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#DELETE_OK#', $salidaFinal); 
                }else{
                   $salidaFinal = $this->establecer('{CONTENT_INFORMATION}', '#DELETE_FAIL#', $salidaFinal); 
                }
            }
        
        if(!$data['NO_ACTIVITIES']){  

            
            $salida = "";
            
            $tam = count($data['ACTIVITIES_AREA']);
            $tamCat = count($data['ACTIVITIES_CATEGORY']);
            foreach ($data['CONTENT_MAIN'] as $activity) {
                $salida .= $this->establecer('{ACTIVITY_IDENTIFICATOR}', $activity->getIdActividad(), $templateElement) ;
                $salida = $this->establecer('{ACTIVITY_NAME}', $activity->getNombre(), $salida) ;
                $salida = $this->establecer('{ACTIVITY_DESCRIPTION}', $activity->getDescripcion(), $salida) ;
                $salida = $this->establecer('{ACTIVITY_CODE}', $activity->getCodigo(), $salida) ;
                $salida = $this->establecer('{IMG_FOLDER}', constant("FOLDER")."/view/images/commons", $salida) ;
                if($activity->getIsActive()){
                    $salida = $this->establecer('{ACTIVITY_ISCHECKED}', 'checked', $salida) ;
                }else{
                    $salida = $this->establecer('{ACTIVITY_ISCHECKED}', null, $salida) ;
                }
               

              
                $salidaOpciones = "";
                for ($index = 0; $index < $tamCat; $index++) {
                     $salidaOpciones .= $this->establecer('{TYPE_ID}', $data['ACTIVITIES_CATEGORY'][$index]->getIdCategoria(), $optionTemplate);
                     $salidaOpciones = $this->establecer('{VALUE_NAME}', $data['ACTIVITIES_CATEGORY'][$index]->getNombreCategoria(), $salidaOpciones);     
                     if($activity->getIdCategoria() == $data['ACTIVITIES_CATEGORY'][$index]->getIdCategoria()){
                        $salidaOpciones = $this->establecer('{IS_SELECTED}', 'selected', $salidaOpciones);  
                     }  else {
                         $salidaOpciones = $this->establecer('{IS_SELECTED}', null, $salidaOpciones);  
                     }
                }                
                
                $salida = $this->establecer('{ACTIVITIES_CATEGORY_LIST}', $salidaOpciones, $salida);
                
            }
            
            $salidaFinal = $this->establecer('{FORM_CONTENT}', $table, $salidaFinal); 
            $salidaFinal = $this->establecer('{ACTIVITY_LIST}', $salida, $salidaFinal);
            $salidaFinal = $this->establecer('{EXCEL_CHAIN}', $data['SEARCH_CHAIN'], $salidaFinal);
            $salidaFinal = $this->establecer('{EXCEL_TITLE}', $data['EXCEL_TITLE'], $salidaFinal);
            $salidaFinal = $this->establecer('{EXCEL_TYPE}', $data['EXCEL_TYPE'], $salidaFinal);
            

        
            
            $this->establecer('{CONTENT_MAIN}', $salidaFinal);
        }else{
            
            $salidaFinal = $this->establecer('{CONTENT_INFORMATION}','#NO_ACTIVITIES#', $salidaFinal);
    
            $this->establecer('{CONTENT_MAIN}', $salidaFinal);
        }
    }

}
