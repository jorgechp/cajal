<?php

/**
 * Comprueba parametros de la evaluación de un estudiante
 *
 * @author jorge
 */
class EvaluationReport {
    private $connection;
    
    /**
     * 
     * @param type $conection
     */
    function __construct($conection) {
        $this->connection = $conection;
    }
    
    /**
     * 
     * Obtiene la calificación de un estudiante en un indicador
     * 
     * @param int $idStudent
     * @param int $idCompetence
     * @param int $idIndicator
     * @return float
     */
    public function getStudentEvaluationOnIndicator($idStudent, $idCompetence, $idIndicator){
        $evaluationDAO = new EvaluacionMySQLDAO($this->connection);
        return $evaluationDAO->getMeanLastEvaluationByIndicator($idCompetence, $idIndicator, $idStudent, null,-1,true);        
    }
    

    /**
     * Comprueba si el estudiante aprobó un indicador
     * @param int $idStudent
     * @param int $idCompetence
     * @param int $idIndicator
     * @return boolean
     */
    public function isStudentPassedIndicator($idStudent, $idCompetence, $idIndicator){
        $nota = $this->getStudentEvaluationOnIndicator($idStudent, $idCompetence, $idIndicator);
        
        if($nota >= $GLOBALS["GENERAL_EVALUATION_PASS"]){    
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Obtiene el índice de completitud de una competencia
     * @param int $idStudent
     * @param int $idCompetence
     * @return float
     */
    public function getProgressOnCompetence($idStudent, $idCompetence){
        $indicatorDAO = new IndicadorMySQLDAO($this->connection);
        $indicadores = $indicatorDAO->getIndicatorByCompetenceId($idCompetence);
        $contadorIndicadoresSuperados = 0;        
        $progreso = 0;
        if($indicadores != false){
            $countIndicators = count($indicadores);
            for ($index = 0; $index < $countIndicators; ++$index) {
                if($this->isStudentPassedIndicator($idStudent,$idCompetence,$indicadores[$index]->getIdIndicator())){
                    ++$contadorIndicadoresSuperados;
                }
            }
            $progreso = $contadorIndicadoresSuperados/$countIndicators;
        }else{
            $progreso = 1;
        }
        
        
        return $progreso;
    }
    
    /**
     * 
     * Obtiene la calificación de un estudiante en una competencia
     * 
     * @param int $idStudent
     * @param int $idCompetence
     * @return float
     */
    public function getStudentEvaluationOnCompetence($idStudent, $idCompetence){
        $indicatorDAO = new IndicadorMySQLDAO($this->connection);
        $indicadores = $indicatorDAO->getIndicatorByCompetenceId($idCompetence);
        $nota = 0.0;
        
        if($indicadores != false){
            foreach ($indicadores as $indicator) {
                $nota += $this->getStudentEvaluationOnIndicator($idStudent, $idCompetence, $indicator->getIdIndicator());
            }
        }
        
        return $nota/count($indicadores);        
    }
    
    /**
     * Comprueba si una competencia ha sido aprobada por un estudiante
     * @param int $idStudent
     * @param int $idCompetence
     * @return boolean
     */
    public function isStudentPassedCompetence($idStudent,$idCompetence){
        $indicatorDAO = new IndicadorMySQLDAO($this->connection);
        $indicadores = $indicatorDAO->getIndicatorByCompetenceId($idCompetence);
        $passed = true;        
        
        if($indicadores != false){
            
            $countIndicators = count($indicadores);
            for ($index = 0; $index < $countIndicators && $passed; ++$index) {                  
                $passed = $this->isStudentPassedIndicator($idStudent,$idCompetence,$indicadores[$index]->getIdIndicator());               
            }  
        }

        return $passed;
    }
    
    /**
     * Obtiene el índice de completitud de una actividad
     * @param int $idStudent
     * @param int $idActivity
     * @return float
     */
    public function getProgressOnActivity($idStudent, $idActivity){
        $competenceDAO = new CompetenceMySQLDAO($this->connection);
        $competencias = $competenceDAO->getCompetencesByActivity($idActivity);
        $contadorCompetenciasSuperadas = 0;        
        
        if($competencias != false){
            $countCompetences = count($competencias);
            for ($index = 0; $index < $countCompetences ; ++$index) {
                if($this->isStudentPassedCompetence($idStudent, $competencias[$index]->getIdCompetencia())){
                    ++$contadorCompetenciasSuperadas;
                }
            }
        }
        
        return $contadorCompetenciasSuperadas/$countCompetences;        
    }
    
    /**
     * Obtiene la calificación de un estudiante en una actividad
     * @param int $idStudent
     * @param int $idActivity
     * @return float
     */
    public function getStudentEvaluationOnActivity($idStudent, $idActivity){
        $competenceDAO = new CompetenceMySQLDAO($this->connection);
        $competencias = $competenceDAO->getCompetencesByActivity($idActivity);
        $nota = 0.0;
        
        if($competencias != false){
            foreach ($competencias as $competencia) {
                $nota += $this->getStudentEvaluationOnCompetence($idStudent, $competencia->getIdCompetencia());
            }
        }
        return $nota/count($competencias);
    }
    
    /**
     *  Comprueba si un estudiante ha aprobado una actividad
     * @param int $idStudent
     * @param int $idActivity
     * @return type
     */
    public function isStudentPassedActivity($idStudent, $idActivity){
        $competenceDAO = new CompetenceMySQLDAO($this->connection);
        $competencias = $competenceDAO->getCompetencesByActivity($idActivity);
        $passed = true;        
        
        if($competencias != false){
            $countCompetences = count($competencias);
            for ($index = 0; $index < $countCompetences && $passed; ++$index) {
                $passed = $this->isStudentPassedCompetence($idStudent, $competencias[$index]->getIdCompetencia());
            }
        }
        
        return $passed;
    }
    
    


}
