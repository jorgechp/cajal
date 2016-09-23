<?php

include_once (ROOT .'/Layers/Persistance/dao/DaoClass.php');
include_once (ROOT .'/Interfaces/dao/ISesionDAO.php');
include_once (ROOT .'/objects/Session.php');

/**
 * Clase para el manejo de Sesiones en un SGBD
 *
 * @author jorge
 */
abstract class SessionDAO extends DaoClass implements ISesionDAO {
    
}
