<?php
define('ROOT', __DIR__ .'/');
session_start();

/**
 * Ofusca e idenpendiza el soporte físico donde se almacena una imagen
 * de la generación de la misma
 */
class AvatarGenerator {
    public static function getImage($name){           
        
        $im = imagecreatefrompng(ROOT.'images/'.$name.'.png'); 
        
        imagealphablending($im,false);
        imagesavealpha($im,true);
        header('Content-Type: image/png');
        imagepng($im);
        imagedestroy($im);

       
        return $im;
    }
}

if (isset($_SESSION['login']) && $_SESSION['login']) {
    $name = filter_input(INPUT_GET,'name',FILTER_SANITIZE_SPECIAL_CHARS);
    
    if(isset($name) && strlen($name) > 0){
        AvatarGenerator::getImage($name);
    }else{        
        AvatarGenerator::getImage(0);
        
    }
}