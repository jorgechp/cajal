<?php


/**
 * Description of ActivityCategory
 *
 * @author jorge
 */
class ActivityCategory {
    private $idCategoria;
    private $nombreCategoria;
    private $codigo;
    
    function __construct($idCategoria, $nombreCategoria, $codigo) {
        $this->idCategoria = $idCategoria;
        $this->nombreCategoria = $nombreCategoria;
        $this->codigo = $codigo;
    }

    
    
    public function getIdCategoria() {
        return $this->idCategoria;
    }

    public function getNombreCategoria() {
        return $this->nombreCategoria;
    }

    public function setIdCategoria($idCategoria) {
        $this->idCategoria = $idCategoria;
    }

    public function setNombreCategoria($nombreCategoria) {
        $this->nombreCategoria = $nombreCategoria;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }


}
