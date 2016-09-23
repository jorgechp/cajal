<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php'); 

/**
 * Description of MVC_view_messages
 *
 * @author jorge
 */
class MVC_view_admin_file extends MVC_view_messages{
    protected function apply_data($data) {
       
        $template = file_get_contents(constant("FOLDER")."/view/structure/adminUploadFile/content_main.html");
        $template_list = file_get_contents(constant("FOLDER")."/view/structure/adminUploadFile/list.html");
        $template_list_element = file_get_contents(constant("FOLDER")."/view/structure/adminUploadFile/list_element.html");
        
        $salidaFinal = $template;
     
        
        if($data['IS_UPLOAD']){
            if($data['RESULT'] == -1){
                $salidaFinal = $this->establecer('{RESULT_INFORMATION}', '#INVALID_TYPE#',$salidaFinal);
            }else{
                
                $resultado = $data['RESULT'];
                if($resultado['IS_ERRORS']){
                    $salidaFinal = $this->establecer('{RESULT_INFORMATION}', '#UPLOAD_INCOMPLETE# ',$salidaFinal);
                    $salidaGenerada = "";
                    
                    foreach ($resultado['ERROR_LIST'] as $inserciones) {
                        $elemento = $template_list_element;
                        $elemento = $this->establecer('{LINE NUMBER}', $inserciones,$elemento);
                        $salidaGenerada .= $elemento;
                    }
                    
                    $salida_lista = $this->establecer('{LIST_ELEMENT}', $salidaGenerada,$template_list);
                    $salidaFinal = $this->establecer('{LIST_RESULT}', $salida_lista,$salidaFinal);
                    $salidaFinal = $this->establecer('{LINE}', '<hr/>',$salidaFinal);
                }else{
                    $salidaFinal = $this->establecer('{RESULT_INFORMATION}', '#UPLOAD_OK#',$salidaFinal);
                }
            }
        }
        $this->establecer('{CONTENT_MAIN}', $salidaFinal);
        

    }
}