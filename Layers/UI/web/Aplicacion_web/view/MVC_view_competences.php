<?php
require_once(constant("FOLDER").'/view/MVC_view.php');
/**
 * Description of MCV_view_competences
 *
 * @author jorge
 */
class MVC_view_competences extends MVC_view{
    
    protected function apply_data($data) {  
        $dataFilter = array();
        
        
        if(isset($data['CONTENT_MAIN']['NO_COMPETENCES_AVAILABLE']) && $data['CONTENT_MAIN']['NO_COMPETENCES_AVAILABLE']){
            $noCompetencesTemplate = file_get_contents(constant("FOLDER")."/view/structure/competences/noCompetencesToShow.html");
            $this->establecer('{CONTENT_MAIN}', '#NO_COMPETENCES_TO_SHOW#');
            $this->establecer('{TIPOS}', $noCompetencesTemplate);
            $this->establecer('{TIPOS_TITLE}', '#NO_COMPETENCES_INFO_TITLE#');
        }else{
                     
            foreach ($data['TIPOS'] as $tipo) {   
                if($data['VIEW_PASSED']){
                    $idTipo = $tipo->getIdTypeCompetence();
                    if($idTipo == 1){
                        $dataFilter["#SHOW_COMPETENCE# {$tipo->getName()}s"] = ["index.php?view_passed&filter_Competence={$tipo->getIdTypeCompetence()}","#SHOW_COMPETENCE# {$tipo->getName()}s",constant("FOLDER")."/view/images/commons/basic_competence_nav.png"];                    
                    }else if($idTipo == 2){
                        $dataFilter["#SHOW_COMPETENCE# {$tipo->getName()}s"] = ["index.php?view_passed&filter_Competence={$tipo->getIdTypeCompetence()}","#SHOW_COMPETENCE# {$tipo->getName()}s",constant("FOLDER")."/view/images/commons/medium_competence_nav.png"];                    
                    }else{
                        $dataFilter["#SHOW_COMPETENCE# {$tipo->getName()}s"] = ["index.php?view_passed&filter_Competence={$tipo->getIdTypeCompetence()}","#SHOW_COMPETENCE# {$tipo->getName()}s",constant("FOLDER")."/view/images/commons/hard_competence_nav.png"];                    
                    }
                    
                }else{                  
                    
                    $idTipo = $tipo->getIdTypeCompetence();
                    
                    if($idTipo == 1){
                        $dataFilter["#SHOW_COMPETENCE# {$tipo->getName()}s"] = ["index.php?filter_Competence=1","#SHOW_COMPETENCE# {$tipo->getName()}s",constant("FOLDER")."/view/images/commons/basic_competence_nav.png"];
                    }else if($idTipo == 2){
                        $dataFilter["#SHOW_COMPETENCE# {$tipo->getName()}s"] = ["index.php?filter_Competence=2","#SHOW_COMPETENCE# {$tipo->getName()}s",constant("FOLDER")."/view/images/commons/medium_competence_nav.png"];
                    }else{
                        $dataFilter["#SHOW_COMPETENCE# {$tipo->getName()}s"] = ["index.php?filter_Competence=3","#SHOW_COMPETENCE# {$tipo->getName()}s",constant("FOLDER")."/view/images/commons/hard_competence_nav.png"];
                    }
                    
                }
            }
            
            //Informe del estudiante
            $dataFilter["i"]="";
            $dataFilter["#DOWNLOAD_REPORT#"] = ["index.php?downloadReport","#DOWNLOAD_REPORT#",constant("FOLDER")."/view/images/icons/navIcons/seo2.png"];
            
            if($data['VIEW_PASSED']){
                $dataFilter["#DOWNLOAD_REPORT#"] = ["index.php?downloadReport","#DOWNLOAD_REPORT#",constant("FOLDER")."/view/images/icons/navIcons/seo2.png"];
                $dataFilter["#VIEW_ENROL#"] = ["index.php","#VIEW_ENROL#",constant("FOLDER")."/view/images/icons/navIcons/man216.png"];                
            }else{
                $dataFilter["#VIEW_REPORT#"] = ["index.php?view_passed","#VIEW_REPORT#",constant("FOLDER")."/view/images/icons/navIcons/check33.png"];                  
            }
            
            
            $cadenaGeneradaContentMain = "";
            
            if($data['filter']){
                $cadenaGeneradaContentMain .= "<h2>#COMPETENCES# {$data['filterName']}s</h2><div class='contentDiv'>";
            }else{
                $cadenaGeneradaContentMain .= "<h2>#COMPETENCES#</h2><div class='contentDiv'>";
            }
         //  $cadenaGeneradaContentMain .='<p>';
            foreach ($data['CONTENT_MAIN'] as $tipo) {   
                
                $linea = file_get_contents(constant("FOLDER")."/view/structure/competences/competencesList.html");
                $linea = $this->establecer('{DIRECTORY}', constant("FOLDER"),$linea);
                $linea = $this->establecer('{ID}', $tipo['id'],$linea);
                $linea = $this->establecer('{NAME}', $tipo['code'].': '.$tipo['name'],$linea);
                $linea = $this->establecer('{DESCRIPCION}', $tipo['descripcion'],$linea);
                $linea = $this->establecer('{PROGRESS}', $tipo['progress'],$linea);                
                
                
                if($tipo['progress'] == 100){
                    $linea = $this->establecer('{IS_PASSED}', 'passed_subject',$linea);
                    $mean = $tipo['currentMean'];
                    if($mean == 0){
                        $linea = $this->establecer('{MEAN}', "(-)",$linea);
                    }else{
                        $linea = $this->establecer('{MEAN}', number_format($tipo['currentMean'],2),$linea);
                    }
                }else{
                    $linea = $this->establecer('{IS_PASSED}', 'no_passed_subject',$linea);
                    $linea = $this->establecer('{MEAN}', number_format($tipo['currentMean'],2),$linea);
                }
                
                switch ($tipo['type']) {
                    case 1:
                        $linea = $this->establecer('{TYPE_COMPETENCE}', 'basic_competence',$linea);
                        break;
                    case 2:
                        $linea = $this->establecer('{TYPE_COMPETENCE}', 'medium_competence',$linea);
                        break;
                    case 3:
                        $linea = $this->establecer('{TYPE_COMPETENCE}', 'hard_competence',$linea);
                        break;
                    default:
                        break;
                }
                $cadenaGeneradaContentMain .= $linea;

            }     
           
            $cadenaGeneradaContentMain .='</div>';
            
            
            
            $this->establecer('{TIPOS_TITLE}', '#COMPETENCE_FILTERS#');   
            $this->apply_help_context('#COMPETENCE_FILTERS#', '#COMPETENCE_HELP_CONTEXT#');
            $this->apply_navmenu_filters($dataFilter);
            $this->establecer('{CONTENT_MAIN}', $cadenaGeneradaContentMain);            
     
        }
    }

}
