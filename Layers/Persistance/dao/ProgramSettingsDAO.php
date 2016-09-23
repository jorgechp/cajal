<?php

require_once (ROOT .'/Layers/Persistance/dao/DaoClass.php');
require_once (ROOT .'/Interfaces/dao/IProgramSettingsDAO.php');
require_once (ROOT .'/objects/ProgramSettings.php');
/**
 * Description of ProgramSettingsDAO
 *
 * @author jorge
 */
abstract class ProgramSettingsDAO extends DaoClass implements IProgramSettingsDAO {
    
}
