<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');

/**
 * Description of MVC_view_message_send
 *
 * @author jorge
 */
class MVC_send_message extends MVC_view_messages {
    protected function apply_data($data) {
        $template = file_get_contents(constant("FOLDER")."/view/structure/messages/sendMessage.html");
        
        
        if($data['CONTENT_MAIN']['sendTry'] == false){            
            $template = $this->establecer('{MESSAGE_RESULT}', null,$template); 
        }else{
            
            if(!$data['CONTENT_MAIN']['isError']){                
                $template = $this->establecer('{MESSAGE_RESULT}', '#SEND_OK#',$template);  
            }else{               
                $templateErrorsInfo = file_get_contents(constant("FOLDER")."/view/structure/messages/messageError.html");
                $templateErrorRecipient = file_get_contents(constant("FOLDER")."/view/structure/messages/recipientErrorMessage.html"); 
                $template = $this->establecer('{MESSAGE_RESULT}', $templateErrorsInfo,$template);  
                $salidaError = "";
                if(isset( $data['CONTENT_MAIN']['ERRORS']['recipients']) &&  $data['CONTENT_MAIN']['ERRORS']['recipients']){
                      if(isset($data['CONTENT_MAIN']['ERRORS']['recipientsId'])){ // Error de id de usuarios
                          
                          foreach ($data['CONTENT_MAIN']['ERRORS']['recipientsId'] as $idRecipient) {
                              $salidaError .= $this->establecer('{ERROR_TYPE}', '#ID_ERROR# '.$idRecipient,$templateErrorRecipient);                              
                          }
                      }else{
                          $salidaError .= $this->establecer('{ERROR_TYPE}', '#RECIPIENT_FORMAT_ERROR# ',$templateErrorRecipient);   
                      }
                }
                
                if(isset( $data['CONTENT_MAIN']['ERRORS']['subject']) &&  $data['CONTENT_MAIN']['ERRORS']['subject']){
                    $salidaError .= $this->establecer('{ERROR_TYPE}', '#SUBJECT_ERROR# ',$templateErrorRecipient);      
                }
                
                if(isset( $data['CONTENT_MAIN']['ERRORS']['message']) &&  $data['CONTENT_MAIN']['ERRORS']['message']){
                    $salidaError .= $this->establecer('{ERROR_TYPE}', '#MESSAGE_ERROR# ',$templateErrorRecipient);      
                }     
                
                
                
                $template = $this->establecer('{MESSAGE_ERRORS}', $salidaError,$template);

                if(isset($data['CONTENT_MAIN']['recipient'])){
                    
                    $template = $this->establecer('{MESSAGE_RECIPIENTS}', implode(',',$data['CONTENT_MAIN']['recipient']),$template);
                }                
                
                if(isset($data['CONTENT_MAIN']['subject'])){
                    $template = $this->establecer('{MESSAGE_SUBJECT}', $data['CONTENT_MAIN']['subject'],$template);
                }
                if(isset($data['CONTENT_MAIN']['message'])){
                    $template = $this->establecer('{MESSAGE_TEXT}', $data['CONTENT_MAIN']['message'],$template);
                }        
                
                
            }
        }
        if(isset($data['CONTENT_MAIN']['desiredDecipient'])){
            $template = $this->establecer('{MESSAGE_RECIPIENTS}', $data['CONTENT_MAIN']['desiredDecipient'], $template);
        }
        
        //Si el usuario participa como profesor o como estudiante en actividades
        if($data['IS_GROUPS']){
            $template_actividad = file_get_contents(constant("FOLDER")."/view/structure/messages/recipients_group_elements.html");
            $salida = "";
            foreach ($data['GROUPS'] as $actividad) {
                $entrada = $this->establecer('{GROUP_ELEMENT_ID}', $actividad->getIdActividad(),$template_actividad);
                $entrada = $this->establecer('{GROUP_ELEMENT_NAME}', $actividad->getNombre(),$entrada);
                $salida .= $entrada;
            }
            $template = $this->establecer('{RECIPIENTS_GROUP_ELEMENTS}', $salida,$template);
        }
     
        
        $dataFilter["i"]="";
        $dataFilter["#INBOX#"]=["index.php?messages","#VIEW_ENROL#",constant("FOLDER")."/view/images/icons/navIcons/email123.png"];
        $dataFilter["#SENT_MESSAGES#"]=["index.php?messages&Sent","#VIEW_ENROL#",constant("FOLDER")."/view/images/icons/navIcons/rightarrow23.png"];
        $dataFilter["#SEND_MESSAGE#"]=["index.php?messages&send","#VIEW_ENROL#",constant("FOLDER")."/view/images/icons/navIcons/pencil6.png"];
       
       
        $this->apply_help_context('#COMPETENCE_FILTERS#', '#COMPETENCE_HELP_CONTEXT#');
        $this->apply_navmenu_filters($dataFilter);
        $this->establecer('{CONTENT_MAIN}', $template);
    }

}
