<?php


class Area {
    private $idArea;
    private $nombre;
    
    
    function __construct($idArea, $nombre) {
        $this->idArea = $idArea;
        $this->nombre = $nombre;
    }

    
    public function getIdArea() {
        return $this->idArea;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setIdArea($idArea) {
        $this->idArea = $idArea;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }


}
