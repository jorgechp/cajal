<?php
include_once (ROOT .'Layers/Persistance/dao/DaoClass.php');
include_once (ROOT .'Interfaces/dao/IVersionSettingsDAO.php');
include_once (ROOT .'objects/VersionSettings.php');

/**
 * Description of VersionSettingsDAO
 *
 * @author jorge
 */
abstract class VersionSettingsDAO extends DaoClass implements IVersionSettingsDAO{
}
