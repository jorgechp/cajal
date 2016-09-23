<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');

 require(ROOT."/lib/pChart214/class/pData.class.php");
 require(ROOT."/lib/pChart214/class/pDraw.class.php");
 require(ROOT."/lib/pChart214/class/pImage.class.php"); 
 
 include (FOLDER.'/i18n/esESGraph.php');
 include (FOLDER.'/i10n/es.php');
/**
 * Description of MVC_view_indicator
 *
 * @author jorge
 */
class MVC_view_indicator extends MVC_view_messages {
    /**
     * Url del fichero temporal del gráfico
     * @var url 
     */
    private $graphUrl;
    
    /**
     * Función necesaria para la generación de ficheros que se guarden en disco. Genera un nombre aleatorio
     * listo para ser usado por un fichero.
     * 
     * Nota: La generación de ficheros puede estar deasactivada
     * @param int $length longitud del nombre
     * @return string
     */
    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    
    
    /**
     * Genera la url del gráfico con la evolución de los indicadores
     * @param Evaluacion[] $data
     */
    private function generateUrlGraph($data,$fontSize,$legend,$height,$width){
       $puntos='?generateIndicator=1';
       $puntos.='&pTypes=2';
       
        $contador = 0;
        foreach ($data as $value) {
           $puntos.='&point[]='.$value['evaluation']->getEvaluacion();
           $fecha = new DateTime($value['evaluation']->getDate());
           $nombreCompetencia = $fecha->format($GLOBALS['i10n_DATE_FORMAT']);
           $nombreCompetencia .= ' ('.$contador.')';
           $puntos.='&point[]='.$nombreCompetencia;   
           ++$contador;
        }
        $puntos.='&nameSeries[]='."{$GLOBALS['i18n_SESSION']}";
        $puntos.='&nameSeries[]='."{$GLOBALS['i18n_CALIFICATON']}";
        $puntos.='&Ylabel='."{$GLOBALS['i18n_CALIFICATON']}";
        $puntos.='&fontSize='."{$fontSize}";
        $puntos.='&legend='."{$legend}";
        $puntos.='&height='."{$height}";
        $puntos.='&width='."{$width}";
        
       return $puntos;
    }
    

    protected function apply_data($data) {       
        $plantillaTipos = file_get_contents(constant("FOLDER").'/view/structure/indicators/indicator_types_info.html');      
        $entrada = file_get_contents(constant("FOLDER").'/view/structure/indicators/indicatorInfo.html');     
        $entrada = $this->establecer('{INDICATOR_CODE}', $data['CONTENT_MAIN']['indicator']['code'],$entrada); 
        $entrada = $this->establecer('{INDICATOR_NAME}', $data['CONTENT_MAIN']['indicator']['name'],$entrada); 
        $entrada = $this->establecer('{COMPETENCE_NAME}', $data['CONTENT_MAIN']['competence']['name'],$entrada);
        $entrada = $this->establecer('{COMPETENCE_ID}', $data['CONTENT_MAIN']['competence']['id'],$entrada);
        $entrada = $this->establecer('{COMPETENCE_NAME}', $data['CONTENT_MAIN']['competence']['name'],$entrada);        
        $entrada = $this->establecer('{STRICT_MEAN}', number_format($data['CONTENT_MAIN']['indicator']['strictMean'],2,',','.'),$entrada);
        if(is_null($data['CONTENT_MAIN']['diagram'])){
            $entrada = $this->establecer('{DIAGRAM}', '#NO_GRAPH_AVAILABLE#',$entrada);
        }
        else{
            $plantillaLeyenda = file_get_contents(constant("FOLDER").'/view/structure/indicators/legend_element.html');  
            $plantilla = file_get_contents(constant("FOLDER").'/view/structure/indicators/diagramRepresentation.html');  
            $url = $this->generateUrlGraph($data['CONTENT_MAIN']['diagram'],9,0,900,630);    
            $plantilla = $this->establecer('{DIAGRAM_IMAGE_URL}', 'GraphGenerator/GraphGenerator.php'.$url,$plantilla); 
            $entrada = $this->establecer('{DIAGRAM}', $plantilla,$entrada);      
            
            //Generamos los datos necesarios para construir una leyenda
            $contador = 0;  
            $salidaHtmlLeyenda = "";
            
            foreach ($data['CONTENT_MAIN']['diagram'] as $value) {
                
                $salidaHtmlLeyenda .= $this->establecer('{ELEMENT_NUMBER}', $contador, $plantillaLeyenda);
                $salidaHtmlLeyenda = $this->establecer('{ACTIVITY_NAME}', $value['activity']->getNombre(), $salidaHtmlLeyenda);
                $salidaHtmlLeyenda = $this->establecer('{ACTIVITY_PLACE}', $value['place'], $salidaHtmlLeyenda);
                ++$contador;
            }
            
            $entrada = $this->establecer('{ELEMENTS}', $salidaHtmlLeyenda,$entrada);
            
        }
        if(strlen($data['CONTENT_MAIN']['indicator']['description']) > 0){
            $plantillaTipos = $this->establecer('{INDICATOR_DESCRIPTION}', $data['CONTENT_MAIN']['indicator']['description'], $plantillaTipos);
        }else{
            $plantillaTipos = $this->establecer('{INDICATOR_DESCRIPTION}', '#NO_DESCRIPTION_AVAILABLE#', $plantillaTipos);
        }
        
        
       
        $this->establecer('{TIPOS}', $plantillaTipos);
        $this->establecer('{TIPOS_TITLE}', '#INDICATOR_DESCRIPTION#');
        $this->establecer('{CONTENT_MAIN}', $entrada);        
        $this->establecer('{IS_SELECTED_1}', 'class="selected"');
    }
    
    /**
     * Genera el código html a mostrar para el usuario y devuelve el enlace
     * al fichero del diagrama generado, o null si no fue generado diagrama alguno
     * @param String[] $diccionario
     * @param String[] $systemDictyonary
     * @param String[] $data
     * @return String
     */
    public function viewHTML($diccionario, $systemDictyonary,$rol, $data = null,$idSelected = null) {
        parent::viewHTML($diccionario, $systemDictyonary,$rol, $data);
        return $this->graphUrl;
    }


}
?>