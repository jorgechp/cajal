<?php

/**
 * Trabaja con el algoritmo BCRYPT para obtener el valor hash de una cadena
 * o para verificar que la cadena se corresponde a un hash especificado
 *
 * @author jorge
 */
class PasswordManager {
    
    public static function getTiempo(){
        $timeTarget = 0.05; // 50 milisegundos 

        $coste = 8;
        do {
            $coste++;
            $inicio = microtime(true);
            password_hash("test", PASSWORD_BCRYPT, ["cost" => $coste]);
            $fin = microtime(true);
        } while (($fin - $inicio) < $timeTarget);
        return $coste;
    }
    /**
     * Obtiene un valor hash para una cadena, con un coste por defecto de 14
     * @param string $cadena
     * @param int $coste
     * @return string
     */
    public static function hash($cadena,$coste = 14){
        $options = [ 'cost' => $coste ];
        return password_hash($cadena, PASSWORD_BCRYPT, $options);
    }
    
    /**
     * Comprueba que la cadena pasada como parámetro corresponde al hash
     * especificado
     * @param string $cadena
     * @param string $hash
     * @return boolean
     */
    public static function verifyHash($cadena,$hash){
        return password_verify($cadena, $hash);
    }

}
