<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');

/**
 * Define la vista estandar de un profesor
 *
 * @author jorge
 */
class MVC_view_professor extends MVC_view_messages{   
    protected function apply_data($data) {
        
        if($data['no_activities']){
            $this->establecer('{CONTENT_MAIN}', '#NO_ACTIVITIES_AVAILABLE#');
        }else{
            
            $templateActividadCabecera = file_get_contents(constant("FOLDER").'/view/structure/professor_view_only/activityListHeader.html');
            $templateActividadLista = file_get_contents(constant("FOLDER").'/view/structure/professor_view_only/professor_activities_list_element.html');
            $templateActividadCategoria = file_get_contents(constant("FOLDER").'/view/structure/professor_view_only/activityCategoryElement.html');
            
            $salidaHtml = "";
            $salidaHtmlCategoria = "";
            $color = 0;
            $contadorCategoria = 1;
            
            foreach ($data['ACTIVITIES_CATEGORY'] as $categoria) {
                $salidaHtmlLista = "";
                $contadorInserciones = 0;
                foreach ($data['CONTENT_MAIN'] as $actividad){
                    if($actividad->getIdCategoria() == $categoria->getIdCategoria()){
                        $salidaHtmlLista.= $this->establecer('{ACTIVITY_ID}', $actividad->getIdActividad(), $templateActividadLista);
                        $salidaHtmlLista= $this->establecer('{ACTIVITY_NAME}', $actividad->getNombre(), $salidaHtmlLista);
                        $salidaHtmlLista= $this->establecer('{ACTIVITY_CODE}', $actividad->getCodigo(), $salidaHtmlLista);
                        $salidaHtmlLista= $this->establecer('{ACTIVITY_DESCRIPTION}', $actividad->getDescripcion(), $salidaHtmlLista);
                        $salidaHtmlLista= $this->establecer('{IMAGE_FOLDER}', constant("FOLDER")."/view/images/commons", $salidaHtmlLista);
                        switch ($color) {
                            case 0:
                                $salidaHtmlLista= $this->establecer('{BUTTON_COLOR}', 'GreenButton.png', $salidaHtmlLista);
                                break;
                            case 1:
                                $salidaHtmlLista= $this->establecer('{BUTTON_COLOR}', 'RedButton.png', $salidaHtmlLista);
                                break;
                            case 2:
                                $salidaHtmlLista= $this->establecer('{BUTTON_COLOR}', 'GreyButton.png', $salidaHtmlLista);
                                break;                    
                            default:
                                break;
                        }
                        $color = ($color + 1) % 3;      
                        ++$contadorInserciones;
                    }
                }
                if($contadorInserciones > 0){
                    $salidaHtmlCategoria .= $this->establecer('{ACTIVITY_LIST}', $salidaHtmlLista, $templateActividadCategoria);
                    $salidaHtmlCategoria = $this->establecer('{NUMBER}', $contadorCategoria, $salidaHtmlCategoria);
                    $salidaHtmlCategoria = $this->establecer('{CATEGORY_NAME}', $categoria->getNombreCategoria(), $salidaHtmlCategoria);
                    ++$contadorCategoria;
                }
                
            }           
            
            
            $salidaHtml = $this->establecer('{ACTIVITY_CATEGORY}', $salidaHtmlCategoria, $templateActividadCabecera);
            $this->establecer('{CONTENT_MAIN}', $salidaHtml);
            
        }

        
        $this->apply_help_context('#PROFESSOR_ACTIVITY_TITLE#', '#PROFESSOR_ACTIVITY#');
        
        $dataFilter["i"]="";        
        $dataFilter["#CHANGE_YEAR#"] = ["index.php?changeYear","#CHANGE_YEAR#",constant("FOLDER")."/view/images/icons/navIcons/time26.png"];
        $this->apply_navmenu_filters($dataFilter);
 
    }

}
