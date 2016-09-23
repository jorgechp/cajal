<?php



/**
 * Description of IStudent_has_competence
 *
 * @author jorge
 */
interface IStudent_has_competencesDAO extends Idao_interface {
    public function getAllbyStudent($idStudent);
    
    public function getAllByProfessor($idProfessor);
    
    public function getAllByYear($idYear);
}
