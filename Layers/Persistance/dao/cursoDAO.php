<?php

include_once (ROOT .'/Layers/Persistance/dao/DaoClass.php');
include_once (ROOT .'/Interfaces/dao/ICursoDAO.php');
include_once (ROOT .'/objects/Curso.php');

/**
 * Description of cursoDAO
 *
 * @author jorge
 */
abstract class cursoDAO extends DaoClass implements ICursoDAO {

}
