<?php

include_once (ROOT .'/Layers/Persistance/dao/DaoClass.php');
include_once (ROOT .'/Interfaces/dao/IUsuarioDAO.php');
include_once (ROOT .'/objects/User.php');

/**
 * Description of UserDAO
 *
 * @author jorge
 */
abstract class UserDAO extends DaoClass implements IUsuarioDAO {

}
