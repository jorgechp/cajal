<?php


interface IMessageDAO extends Idao_interface{
    public function getAllMessagesToUser($idUser);
    public function getAllMessagesFromUser($idUser);
    public function getNumberOfMessagesNotReadByUser($idUser);
    /**
     * Devuelve si el mensaje va dirigido al destinatario.
     */
    public function isMessagePropertyOfUser($idMessage,$idUser);
    public function isMessageRead($idUser,$idMessage);
    public function setMessageRead($idMessage,$iduser);
    public function getDestinatarios($idMessage);
    public function sendMessage($idSender, $idRecipients,$subject,$message);

}
