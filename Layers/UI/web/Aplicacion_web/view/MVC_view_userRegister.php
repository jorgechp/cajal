<?php
require_once(constant("FOLDER").'/view/MVC_view.php');
/**
 * Description of MVC_view_userRegister
 *
 * @author jorge
 */
class MVC_view_userRegister extends MVC_view{
    protected function apply_i18n($diccionarios) {        
        foreach ($diccionarios as $diccionario){
            parent::apply_i18n($diccionario);
        }
    }
    
    protected function apply_data($data) {
        $salidaHtml = "";
        $registroOK = false;
        $register_template = file_get_contents(constant("FOLDER").'/view/structure/register/userRegisterData.html');
        $register_template = $this->establecer('{IMG_FOLDER}',constant("FOLDER").'/view/images/commons', $register_template);
        if(isset($data['REGISTER']) && $data['REGISTER']){
            if(isset($data['CONTENT_MAIN']['REGISTER_SUCCESS'])){ // Se produjo un intento de registro
               if($data['CONTENT_MAIN']['REGISTER_SUCCESS']){
                   $salidaHtml .= '<p>#REGISTER_OK#</p>';
                   $registroOK = true;
               }else{
                   
                   
                   $salidaHtml .= '<ol>';
                   foreach ($data['CONTENT_MAIN']['REGISTERERROR'] as $value) {
                      $salidaHtml .= "<li>#{$value}#</li>";
                   }
                   $salidaHtml .= '</ol>';
                   
                   if(!isset($data['CONTENT_MAIN']['DUPLICATE_ENTRY'])){
                        $register_template = $this->establecer('#DUPLICATE_ENTRY#', null, $register_template);
                   }
                   
                   if(isset($data['CONTENT_MAIN']['CORRECTDATA']['USERNAME'])){
                       $register_template = $this->establecer('{USER_REGISTER_DATA_NAME}', $data['CONTENT_MAIN']['CORRECTDATA']['USERNAME'], $register_template);
                   }                   
                   if(isset($data['CONTENT_MAIN']['CORRECTDATA']['USERLASTNAME1'])){
                       $register_template = $this->establecer('{USER_REGISTER_DATA_LASTNAME1}', $data['CONTENT_MAIN']['CORRECTDATA']['USERLASTNAME1'], $register_template);
                   }                   
                   if(isset($data['CONTENT_MAIN']['CORRECTDATA']['USERLASTNAME2'])){
                       $register_template = $this->establecer('{USER_REGISTER_DATA_LASTNAME2}', $data['CONTENT_MAIN']['CORRECTDATA']['USERLASTNAME2'], $register_template);
                   }                   
                   if(isset($data['CONTENT_MAIN']['CORRECTDATA']['USERID'])){
                       $register_template = $this->establecer('{USER_REGISTER_DATA_REALID}', $data['CONTENT_MAIN']['CORRECTDATA']['USERID'], $register_template);
                   }                  
                   if(isset($data['CONTENT_MAIN']['CORRECTDATA']['USERMAIL'])){
                       $register_template = $this->establecer('{USER_REGISTER_DATA_MAIL}', $data['CONTENT_MAIN']['CORRECTDATA']['USERMAIL'], $register_template);
                   }                  
                   if(isset($data['CONTENT_MAIN']['CORRECTDATA']['USERPHONE'])){
                       $register_template = $this->establecer('{USER_REGISTER_DATA_PHONE}', $data['CONTENT_MAIN']['CORRECTDATA']['USERPHONE'], $register_template);
                   } 
                   
                   $register_template = preg_replace('/{.*}/', '', $register_template);
                   $salidaHtml .= $register_template;
                   $registroOK = true;
               }
            }

        }
        
        if($registroOK){
            $this->establecer('{CONTENT_MAIN}', $salidaHtml); 
        }else{  
            
            $register_template = preg_replace('/{.*}/', '', $register_template);            
            $this->establecer('{CONTENT_MAIN}', $register_template);           
        }
        $register_types = file_get_contents(constant("FOLDER").'/view/structure/register/userRegisterData_types.html');
        $this->establecer('{TIPOS_TITLE}', '#USER_REGISTER#');    
        $this->establecer('{TIPOS}', '#DATA_INFO#');    
    }

}
