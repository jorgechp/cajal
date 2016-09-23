<?php

include_once (ROOT .'/Layers/Persistance/dao/DaoClass.php');
include_once (ROOT .'/Interfaces/dao/IIndicadorDAO.php');
include_once (ROOT .'/objects/Indicator.php');
/**
 * Clase para el manejo de Indicadores en un SGBD
 *
 * @author jorge
 */
abstract class IndicatorDAO extends DaoClass implements IIndicadorDAO{


}
