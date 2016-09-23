<?php
 error_reporting( E_ALL );
 set_time_limit ( 1000 );
 include '../PasswordManager.php';
 
    function letra_nif($dni) {
        return substr("TRWAGMYFPDXBNJZSQVHLCKE",strtr($dni,"XYZ","012")%23,1);
    }
  $arrResult = array();
  $arrLines = file( 'data2.csv' );
  $correo = 'default_';
  $contador = 0;
  foreach( $arrLines as $line ) {
    $arrLine = explode( ',', $line );
    $nombre = $apellido1 = $apellido2 = $dni = $nombre = "";
    
    for ($index = 0; $index < count($arrLine); $index++) {
        if($index == 0){
            $apellido1 = $arrLine[$index];            
        }
        if($index == 1){
            $apellido2 = $arrLine[$index];
        }        
        if($index >= 2){
            if(preg_match("/([A-Z]{1}[0-9]{5})/", $arrLine[$index])){
                $dni = $arrLine[$index];
            }else{
                $nombre .= $arrLine[$index].' ';
            }
        }
        
        
    }
    $correo_definitivo = $correo.$contador.'@'."correo.ugr.es";   
    ++$contador;
    
    $letra = letra_nif($dni);
    $dni_definitivo=$dni.$letra;
    $hash = PasswordManager::hash($dni);
    $cadena = "INSERT INTO `pCompetencias`.`User` (name, lastName1, lastName2, dni, mail, password) VALUES ('$nombre', '$apellido1', '$apellido2', '$dni', '$correo_definitivo', '$hash');";
    for ($index1 = 584; $index1 < 855; $index1++) {
      echo "INSERT INTO `pCompetencias`.`Student` (idUser) VALUES ($index1);";  
    }
    
    echo $cadena;
  
     
  }
 
?>