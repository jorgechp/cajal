<?php
require_once(constant("FOLDER").'/view/MVC_view.php');
/**
 * Description of MCV_view_competences
 *
 * @author jorge
 */
class MVC_view_messages extends MVC_view{
        
    protected function apply_i18n($diccionarios) {        
        foreach ($diccionarios as $diccionario){
            parent::apply_i18n($diccionario);
        }
    }

    protected function apply_data($data) {        
        $plantilla_cabecera_mensajes = file_get_contents(constant("FOLDER")."/view/structure/messages/messagesInfo.html");
        $plantilla_lista_mensajes = file_get_contents(constant("FOLDER")."/view/structure/messages/messagesList.html");
                
        
        $salidaHtml = "";
        $listaMensajes = "";
        
        if(isset($data['CONTENT_MAIN']['MESSAGES'])){
            $salidaHtml = $plantilla_cabecera_mensajes;
            $contadorMensajes = count($data['CONTENT_MAIN']['MESSAGES']);
            for ($index = 0; $index < $contadorMensajes; $index++) {
                $mensaje = $data['CONTENT_MAIN']['MESSAGES'][$index];  
                
                if(!isset($mensaje['remitentes']) || count($mensaje['remitentes'])> 0){ //Si no tiene lista de remitentes, es un mensaje grupal y no debe ser mostrado aquí
                
                    $nuevaLinea = $plantilla_lista_mensajes;
                    $date = new DateTime($mensaje['object']->getDate());
                    $esRemitente = false;
                    if(isset($mensaje['isRemitente']) && $mensaje['isRemitente']){
                        $esRemitente = true;
                    }

                    $nuevaLinea = $this->establecer('{MESSAGE_ID}', $mensaje['object']->getIdMessage(), $nuevaLinea);
                    $nuevaLinea = $this->establecer('{MESSAGE_SUBJECT}', $mensaje['object']->getSubject(), $nuevaLinea);


                    if(!$esRemitente){
                        $nuevaLinea = $this->establecer('{MESSAGE_FROM}', $mensaje['nombre'], $nuevaLinea);
                    }
                    else{
                        $destinatarios = "";

                        if(is_array($mensaje['remitentes'])){
                            foreach ($mensaje['remitentes'] as $remitente) {
                                $destinatarios .= $remitente.', ';
                            }
                        }

                        $nuevaLinea = $this->establecer('{MESSAGE_FROM}', rtrim($destinatarios, ', '), $nuevaLinea);
                        $salidaHtml = $this->establecer('#TABLE_HEADER_FROM#', '#TABLE_HEADER_TO#', $salidaHtml);
                        $salidaHtml = $this->establecer('<input type="submit" name="manageMessages" value="#TABLE_SUBMIT#">', null, $salidaHtml);

                    }
                    $nuevaLinea = $this->establecer('{MESSAGE_DATE}', $date->format($GLOBALS['i10n_DATE_FORMAT']), $nuevaLinea);
                    if($mensaje['isRead']){
                        $nuevaLinea = $this->establecer('{MESSAGE_ISREAD}', '#MESSAGE_READ#', $nuevaLinea);
                    }
                    else{
                        $nuevaLinea = $this->establecer('{MESSAGE_ISREAD}', '#MESSAGE_NOT_READ#', $nuevaLinea);
                    }

                    $listaMensajes .= $nuevaLinea;
                }
                
            }
            $salidaHtml = $this->establecer('{MESSAGES_LIST}', $listaMensajes,$salidaHtml);
            $this->establecer('{IS_SELECTED_3}', 'class="selected"');
        }
        else{
            
            $salidaHtml = '<h2>#VIEW_MESSAGES#</h2> <div class="contentDiv">#NO_MESSAGES_AVAILABLE#</div>';
        }
        
        $dataFilter["i"]="";
        $dataFilter["#INBOX#"]=["index.php?messages","#VIEW_ENROL#",constant("FOLDER")."/view/images/icons/navIcons/email123.png"];
        $dataFilter["#SENT_MESSAGES#"]=["index.php?messages&Sent","#VIEW_ENROL#",constant("FOLDER")."/view/images/icons/navIcons/rightarrow23.png"];
        $dataFilter["#SEND_MESSAGE#"]=["index.php?messages&send","#VIEW_ENROL#",constant("FOLDER")."/view/images/icons/navIcons/pencil6.png"];
       
       
        $this->apply_help_context('#COMPETENCE_FILTERS#', '#COMPETENCE_HELP_CONTEXT#');
        $this->apply_navmenu_filters($dataFilter);
        $this->establecer('{CONTENT_MAIN}', $salidaHtml);
        
    }

}
