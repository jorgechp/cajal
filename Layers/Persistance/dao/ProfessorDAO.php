<?php

include_once (ROOT .'/Layers/Persistance/dao/DaoClass.php');
include_once (ROOT .'/Interfaces/dao/IProfessorDAO.php');
include_once (ROOT .'/objects/Professor.php');

/**
 * Description of ProfessorDAO
 *
 * @author jorge
 */
abstract class ProfessorDAO extends DaoClass implements IProfessorDAO{
    
}
