<?php

/**
 *
 * @author jorge
 */
interface IUsuarioDAO extends Idao_interface {
    public function checkLogin($idUsuario,$password);
    public function getIdUserFromMail($mail);
    public function getIdUserFromRealId($realId);
    public function getPasswordHashFromUser($idUser);
    public function isStudentChangeRol($idUser,$idRol);
    public function changeUserRol($idUser, $newRol);
    public function getAllByArea($idArea);
    public function getAllByCentre($idCentre);
    public function getAllByAreaAndCentre($idArea,$idCentre);

        
}
