<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');

/**
 * Description of MVC_view_admin_view_only
 *
 * @author jorge
 */
class MVC_view_professor_TimeMachine  extends MVC_view_messages{
    protected function apply_data($data) {
        $content = file_get_contents(constant("FOLDER").'/view/structure/professorTimeMachine/contentMain.html');
        $element = file_get_contents(constant("FOLDER").'/view/structure/professorTimeMachine/optionElements.html');
        
        $salida = $content;
        $salidaCurso = "";
        foreach ($data['CURRENT_MAIN']['YEARS'] as $year) {            
            if($year->getIdYear() == $data['CURRENT_MAIN']['CURRENT_YEAR']){
                $salida = $this->establecer('{CURRENT_YEAR}', $year->getInitialYear().'/'.$year->getFinalYear(),$salida);
            }else{
                $newYear = $this->establecer('{YEAR_ID}', $year->getIdYear(),$element);
                $newYear = $this->establecer('{YEAR_NAME}', $year->getInitialYear().'/'.$year->getFinalYear(),$newYear);
                $salidaCurso .= $newYear;
            }
        }        
        
        if(isset($data['CURRENT_MAIN']['CHANGED'])){
            $salida = $this->establecer('{CONTENT_INFORMATION}', '#YEAR_CHANGED#',$salida);
        }
        
        $salida = $this->establecer('{OPTIONS_ELEMENTS}', $salidaCurso,$salida);
        $this->establecer('{CONTENT_MAIN}', $salida);
        
        $dataFilter["i"]="";
        $dataFilter["#CHECK_ACTIVITIES#"] = ["index.php","#CHECK_ACTIVITIES#",constant("FOLDER")."/view/images/icons/navIcons/home168.png"];
        $dataFilter["#CHANGE_YEAR#"] = ["index.php?changeYear","#CHANGE_YEAR#",constant("FOLDER")."/view/images/icons/navIcons/time26.png"];
        $this->apply_navmenu_filters($dataFilter);
        
        

    }
    

}
