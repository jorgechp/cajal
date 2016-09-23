<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');
/**
 * Vista de un mensaje
 *
 * @author jorge
 */
class MVC_view_message_info extends MVC_view_messages {
    protected function apply_data($data) {
       
       
       $plantillaMensaje = file_get_contents(constant("FOLDER")."/view/structure/messages/messageView.html");
       $plantillaUserLink = file_get_contents(constant("FOLDER")."/view/structure/messages/messageUserLink.html");
       $plantillaDelete = file_get_contents(constant("FOLDER")."/view/structure/messages/messageShowDelete.html");
       
       $entrada = $plantillaMensaje;
       $entrada = $this->establecer('{MESSAGE_TITLE}', $data['CONTENT_MAIN']['VIEWMESSAGE']->getSubject(), $entrada);      
       $entrada = $this->establecer('{MESSAGE_FROM}', $data['CONTENT_MAIN']['Sender']->getNombre() . ' ' . $data['CONTENT_MAIN']['Sender']->getApellido1() . ' ' . $data['CONTENT_MAIN']['Sender']->getApellido2(), $entrada);
       $entrada = $this->establecer('{MESSAGE_CONTENT}', $data['CONTENT_MAIN']['VIEWMESSAGE']->getMessage(), $entrada);
       $entrada = $this->establecer('{USER_FROM_ID}', $data['CONTENT_MAIN']['Sender']->getIdUsuario(), $entrada);
       $entrada = $this->establecer('{MESSAGE_ID}', $data['CONTENT_MAIN']['VIEWMESSAGE']->getIdMessage(), $entrada);
       
       $salidaUsuarios = "";
       
      
       foreach ($data['CONTENT_MAIN']['destinatarios'] as $destinatario) {             
           $salidaUsuarios .= $this->establecer('{USER_ID}', $destinatario->getIdUsuario(), $plantillaUserLink);
           $salidaUsuarios = $this->establecer('{USER_NAME}', $destinatario->getNombre() . ' ' . $destinatario->getApellido1() . ' ' . $destinatario->getApellido2(), $salidaUsuarios);           
       }
       
       //Si el usuario es el mismo, no debería poder eliminar un mensae
       if($data['CONTENT_MAIN']['CURRENT_USER']->getIdUsuario() != $data['CONTENT_MAIN']['Sender']->getIdUsuario()){
           $entrada = $this->establecer('{MESSAGE_DELETE}', $plantillaDelete, $entrada);
       }
       
       $entrada = $this->establecer('{MESSAGE_TO}', $salidaUsuarios, $entrada);
       
        $dataFilter["i"]="";
        $dataFilter["#INBOX#"]=["index.php?messages","#VIEW_ENROL#",constant("FOLDER")."/view/images/icons/navIcons/email123.png"];
        $dataFilter["#SENT_MESSAGES#"]=["index.php?messages&Sent","#VIEW_ENROL#",constant("FOLDER")."/view/images/icons/navIcons/rightarrow23.png"];
        $dataFilter["#SEND_MESSAGE#"]=["index.php?messages&send","#VIEW_ENROL#",constant("FOLDER")."/view/images/icons/navIcons/pencil6.png"];
       
       
        $this->apply_help_context('#COMPETENCE_FILTERS#', '#COMPETENCE_HELP_CONTEXT#');
        $this->apply_navmenu_filters($dataFilter);
        
       $this->establecer('{TIPOS_TITLE}', '#VIEW_MESSAGE#');
       $this->establecer('{CONTENT_MAIN}', $entrada);
    }

}
