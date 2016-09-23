<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php'); 

/**
 * Description of MVC_view_messages
 *
 * @author jorge
 */
class MVC_view_admin_system extends MVC_view_messages{
    protected function apply_data($data) {
       
        $template = file_get_contents(constant("FOLDER")."/view/structure/adminSystem/contentMain.html");
        $template_list_element = file_get_contents(constant("FOLDER")."/view/structure/adminSystem/year_list.html");
        
        $salidaFinal = $template;
        
        $salidaFinal = $this->establecer('{INITIAL_YEAR}', date("Y"), $salidaFinal) ;
        $salidaFinal = $this->establecer('{FINAL_YEAR}', date("Y")+1, $salidaFinal) ;
        
        $entrada_lista = "";
        foreach ($data['CONTENT_MAIN']['CURSOS'] as $curso) {  
                
                if($curso->getIdYear() != $data['CONTENT_MAIN']['CURRENT_YEAR']){
                    $entrada_lista .= $this->establecer('{YEAR_ID}', $curso->getIdYear(), $template_list_element) ;   
                    $entrada_lista = $this->establecer('{YEAR_NAME}', $curso->getInitialYear().'/'.$curso->getFinalYear(), $entrada_lista) ; 
                }
        }
        
        
        $salidaFinal = $this->establecer('{YEAR_SELECTED}', $entrada_lista, $salidaFinal) ;
        $this->establecer('{CONTENT_MAIN}', $salidaFinal);
    }
}
