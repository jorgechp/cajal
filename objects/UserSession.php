<?php


/**
 *  Representa una sesión en el sistema
 *
 * @author jorge
 */
class UserSession {
    private $idUsuario;
    private $rol;
    private $idYear;
    private $avatar;
    
    
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }
    
    public function getRol() {
        return $this->rol;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }

    public function getIdYear() {
        return $this->idYear;
    }

    public function setIdYear($idYear) {
        $this->idYear = $idYear;
    }
    public function getAvatar() {
        return $this->avatar;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }






}
