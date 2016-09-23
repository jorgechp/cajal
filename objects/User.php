<?php



/**
 * Description of User
 *
 * @author jorge
 */
class User {
    private $idUsuario;
    private $password;
    private $nombre;
    private $apellido1;
    private $apellido2;
    private $DNI;
    private $imagenPerfil;
    private $mail;
    private $phone;
    private $rol;
    private $idArea;
    private $idCentro;
    
    function __construct($idUsuario, $password, $nombre, $apellido1, $apellido2, $DNI, $imagenPerfil, $mail, $phone, $rol,$idArea,$idCentro) {
        $this->idUsuario = $idUsuario;
        $this->password = $password;
        $this->nombre = $nombre;
        $this->apellido1 = $apellido1;
        $this->apellido2 = $apellido2;
        $this->DNI = $DNI;
        $this->imagenPerfil = $imagenPerfil;
        $this->mail = $mail;
        $this->phone = $phone;
        $this->rol = $rol;
        $this->idArea = $idArea;
        $this->idCentro = $idCentro;        
    }
    
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido1() {
        return $this->apellido1;
    }

    public function getApellido2() {
        return $this->apellido2;
    }

    public function getDNI() {
        return $this->DNI;
    }

    public function getImagenPerfil() {
        return $this->imagenPerfil;
    }

    public function getMail() {
        return $this->mail;
    }
    
    public function getPhone() {
        return $this->phone;
    }

    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellido1($apellido1) {
        $this->apellido1 = $apellido1;
    }

    public function setApellido2($apellido2) {
        $this->apellido2 = $apellido2;
    }

    public function setDNI($DNI) {
        $this->DNI = $DNI;
    }

    public function setImagenPerfil($imagenPerfil) {
        $this->imagenPerfil = $imagenPerfil;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }



    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getRol() {
        return $this->rol;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }

    public function getIdArea() {
        return $this->idArea;
    }

    public function getIdCentro() {
        return $this->idCentro;
    }

    public function setIdArea($idArea) {
        $this->idArea = $idArea;
    }

    public function setIdCentro($idCentro) {
        $this->idCentro = $idCentro;
    }




    
    
}
