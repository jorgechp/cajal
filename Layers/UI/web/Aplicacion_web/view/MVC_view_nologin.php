<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');

/**
 * Description of MVC_view_nologin
 *
 * @author jorge
 */
class MVC_view_nologin extends MVC_view_messages{
    protected function apply_data($data) {
        $login_template = file_get_contents(constant("FOLDER").'/view/structure/login/login.html');
        
        $salida = $login_template;
        
        if(isset($data['CONTENT_MAIN']['LOGIN_TRY']) && $data['CONTENT_MAIN']['LOGIN_TRY']){
            if($data['CONTENT_MAIN']['RESULT'] == false){
               $salida =  $this->establecer('{LOGIN_RES}', '#LOGIN_ERROR#',$salida);
	 	
            }else{
                //Refrescar página
                header( 'refresh: 0;' ); 
                die(); // http://thedailywtf.com/Articles/WellIntentioned-Destruction.aspx
            }
        }
        
         if(isset($data['PASSWORD_RECOVERY'])){
             if($data['PASSWORD_RECOVERY']){
                 if($data['PASSWORD_RECOVERY_MAIL']){
                    $salida =  $this->establecer('{LOGIN_RES}', '#REMEMBER_PASSWORD_OK#',$salida);
                 }else{
                     $salida =  $this->establecer('{LOGIN_RES}', '#REMEMBER_PASSWORD_MAIL_SENT_FAILED#',$salida);
                 }
             }else{
                 $salida =  $this->establecer('{LOGIN_RES}', '#REMEMBER_PASSWORD_FAIL#',$salida);
             }
              
           }
	$salida =  $this->establecer('{IMG_FOLDER}', constant("FOLDER").'/view/images/commons',$salida);
        
        $this->establecer('{CONTENT_MAIN}', $salida);
        $this->apply_help_context('#ABOUT#', "#NOLOGIN_INFO#");

    }

}
