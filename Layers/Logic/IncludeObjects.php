<?php

/* 
 * Incluye todos los objetos requeridos en la capa lógica
 */


require_once (ROOT .'/Layers/Persistance/MySQL/DB_Mysql.php');
require_once (ROOT .'./Layers/Persistance/MySQL/ActividadMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/CompetenceMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/IndicadorMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/SessionMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/CursoMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/UserMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/StudentMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/ProfessorMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/AdminMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/Student_has_compentecesMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/CompetenceTypeMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/EvaluacionMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/MessageMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/VersionSettingsMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/ProgramSettingsMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/ReportMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/LugarMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/ProfessorAreaMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/ProfessorCentreMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/CompetenciaTipoMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/CompetenceAreaMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/ActividadTipoMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/ActividadCategoriaMySQLDAO.php');
require_once (ROOT .'./Layers/Persistance/MySQL/ValidadorCodigoMySQLDAO.php');

require_once (ROOT .'./Layers/Logic/EvaluationReport.php');
