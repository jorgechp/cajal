<?php

require_once (ROOT .'/Layers/Persistance/dao/DaoClass.php');
require_once (ROOT .'/Interfaces/dao/IReportDAO.php');
require_once (ROOT .'/objects/Report.php');

/**
 * Description of ReportDAO
 *
 * @author jorge
 */
abstract class ReportDAO extends DaoClass implements IReportDAO{
}
