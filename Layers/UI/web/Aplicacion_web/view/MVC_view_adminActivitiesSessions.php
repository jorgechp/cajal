<?php
require_once(constant("FOLDER").'/view/MVC_view_messages.php');

/**
 * Description of MVC_view_adminActivities
 *
 * @author jorge
 */
class MVC_view_adminActivitiesSessions extends MVC_view_messages{ 
    protected function apply_data($data) {

        
        if($data['CONTENT_MAIN']['places'] != false){
            
            $sessionTemplate = file_get_contents(constant("FOLDER") . '/view/structure/professor_sessions_list/sessionsContent.html');
            $sessionRow = file_get_contents(constant("FOLDER") . '/view/structure/professor_sessions_list/sessionRow.html');
            $sessionTypeTemplate = file_get_contents(constant("FOLDER") . '/view/structure/professor_sessions_list/sessionsType.html');
            $placeRow = file_get_contents(constant("FOLDER") . '/view/structure/professor_sessions_list/sessionsPlaceRow.html');           
            
            $salidaHtml = "";
            
            if($data['CONTENT_MAIN']['sessions'] != false && count($data['CONTENT_MAIN']['sessions']) > 0){
                $Contador = 1;
                foreach ($data['CONTENT_MAIN']['sessions'] as $session){
                    
                    $fechaInicial = $session->getDateStart();
                    $a1 = date_create($fechaInicial);
                    $a2 = date_create($session->getDateEnd());
                    $duracion = date_diff($a1,$a2,true);
                    
                    
                    $salidaHtml .= $sessionRow;
                    $salidaHtml = $this->establecer('{SESSION_NUMBER}', $Contador, $salidaHtml);                    
                    $salidaHtml = $this->establecer('{SESSION_DATE_START}', $a1->format($GLOBALS["SYSTEM_LONG_TIMEFORMAT"]), $salidaHtml);
                    
                    $salidaHtml = $this->establecer('{SESSION_DATE_END}', $duracion->format("%H:%I #HOURS#"), $salidaHtml);
                    $salidaHtml = $this->establecer('{SESSION_PLACE}', $data['CONTENT_MAIN']['places'][$session->getIdLugar()], $salidaHtml);
                    $salidaHtml = $this->establecer('{SESSION_PASSWORD}', $session->getPassword(), $salidaHtml);
                    $salidaHtml = $this->establecer('{SESSION_IMAGE_URL}', constant("FOLDER") , $salidaHtml);
                    $salidaHtml = $this->establecer('{SESSION_DELETE}', $session->getIdSession(), $salidaHtml);                    
                    $salidaHtml = $this->establecer('{ACTIVITY_ID}', $data['activityID'], $salidaHtml);
                    $salidaHtml = $this->establecer('{IMG_FOLDER}', constant("FOLDER")."/view/images/commons/", $salidaHtml);
                    ++$Contador;
                }
            }
            
            
            $salidaFinal = $this->establecer('{SESSION_ROW}', $salidaHtml,$sessionTemplate);
            $salidaFinal = $this->establecer('{ACTIVITY_NAME}', $data['activityNAME'],$salidaFinal);
            $salidaFinal = $this->establecer('{ACTIVITY_ID}', $data['activityID'],$salidaFinal);
            
            
            //Lugares
            $salidaHtmlPlaces = "";

            foreach ($data['CONTENT_MAIN']['places'] as $idPlace => $place){
                 $salidaHtmlPlaces .= $placeRow;
                 
                 $salidaHtmlPlaces = $this->establecer('{ID_PLACE}', $idPlace, $salidaHtmlPlaces);
                 $salidaHtmlPlaces = $this->establecer('{NAME_PLACE}', $place, $salidaHtmlPlaces);
            }
            
           
            
            $salidaFinal = $this->establecer('{PLACE_OPTION}', $salidaHtmlPlaces,$salidaFinal);
            $salidaFinal = $this->establecer('{FORM_URL}', 'index.php?adminActivities&sessionList',$salidaFinal);
            $salidaFinal = $this->establecer('{BACK_URL}', 'index.php?adminActivities',$salidaFinal);
            $salidaFinal = $this->establecer('{CURRENT_URL}', 'index.php?adminActivities&sessionList',$salidaFinal);
            
            if(isset($data['insertionCorrect']) && !$data['insertionCorrect']){
                $salidaError = "";
                if($data['INSERT_ERROR']['dateStart'] == 1){
                    $salidaError .= "#DATESTARTERROR#";                   
                }else{
                    
                    $salidaFinal = $this->establecer('{DATETIME_VALUE}', $data['INSERT_ERROR']['dateStart'],$salidaFinal);
                    
                }
                if($data['INSERT_ERROR']['dateLength']  == 1){
                    $salidaError .= "#DATELENGTHERROR#";
                }else{
                    $salidaFinal = $this->establecer('{DATETIME_DURATION_VALUE}', $data['INSERT_ERROR']['dateLength'],$salidaFinal);
                }
                if($data['INSERT_ERROR']['dateStartHour']  == 1){
                    $salidaError .= "#DATESTARTHOURROR#";
                }else{
                    $salidaFinal = $this->establecer('{DATETIME_HOUR_VALUE}', $data['INSERT_ERROR']['dateStartHour'],$salidaFinal);
                }
                if($data['INSERT_ERROR']['dateStartMinute']  == 1){
                    $salidaError .= "#DATESTARTMINUTEERROR#";
                }else{
                    $salidaFinal = $this->establecer('{DATETIME_MINUTE_VALUE}', $data['INSERT_ERROR']['dateLength'],$salidaFinal);
                }             
                 
                $salidaFinal = $this->establecer('{CONTENT_ERROR}', $salidaError,$salidaFinal);
            }
            
            $this->establecer('{CONTENT_MAIN}', $salidaFinal);
            
            
            
        }else{
            $this->establecer('{CONTENT_MAIN}', '#NO_PLACES_AVAILABLES#');
            
        }


    }

}
