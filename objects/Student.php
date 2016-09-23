<?php

include_once (ROOT .'/objects/User.php');

/**
 * Description of Student
 *
 * @author jorge
 */
class Student extends User {
    /*
     * Obtiene la lista de competencias matriculadas en el curso actual
     */
    private $listaCompetenciasMatriculadas;
    
    /**
     * Obtiene un vector con todas las competencias matriculadas
     * @return int[]
     */
    public function getListaCompetenciasMatriculadas() {
        return $this->listaCompetenciasMatriculadas;
    }

    /**
     * Obtiene el id de la competencia matriculada en la posición $index del vector de 
     * competencias matriculadas
     * @param int $index
     * @return int
     */
    public function getCompetenciaMatriculada($index){
        return $this->listaCompetenciasMatriculadas[$index];
    }
    
    /**
     * Añade una competencia matriculada
     * @param int $idCompetencia
     */
    public function addCompetenciaMatriculada($idCompetencia){
        $this->listaCompetenciasMatriculadas[] = $idCompetencia;
    }
    
    /**
     * Elimina una competencia matriculada en la posición $index del vector
     * de competencias matriculadas del estudiante
     * @param int $index
     */
    public function delCompetenciaMatriculada($index){
        unset($this->listaCompetenciasMatriculadas[$index]);
    }
    
    /**
     * Elimina, del vector de competencias matriculadas por el estudiante, la
     * competencia cuyo identificador se pasa por parámetro ($idCompetencia).
     * 
     * Se hace una búsqueda lineal en un vector, por lo que la eficiencia
     * en el peor de los casos será de O(n) siendo n el número de competencias
     * matriculadas por el estudiante en el curso académico actual 
     * 
     * @param int $idCompetencia
     */
    public function delCompetenciaMatribucladaPorId($idCompetencia){
        $numCompetenciasMatriculadas = count($this->listaCompetenciasMatriculadas);
        for ($index1 = 0; $index1 < $numCompetenciasMatriculadas; $index1++) {
            if($this->listaCompetenciasMatriculadas[$index1] == $idCompetencia){
                unset($this->listaCompetenciasMatriculadas[$index1]);
            }
        }
    }
    
    /**
     * Elimina todas las competencias matriculadas del estudiante en el curso
     * académico actual
     */
    public function cleanCompetenciasMatriculadas(){
        unset($this->listaCompetenciasMatriculadas);
    }
}
