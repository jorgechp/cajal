<?php
require_once (ROOT .'/Layers/Persistance/dao/DaoClass.php');
require_once (ROOT .'/Interfaces/dao/IMessageDAO.php');
require_once (ROOT .'/objects/Message.php');
/**
 * Description of MessagesDAO
 *
 * @author jorge
 */
abstract class MessageDAO extends DaoClass implements IMessageDAO{
    //put your code here
}
