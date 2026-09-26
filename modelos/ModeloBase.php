<?php

abstract class ModeloBase {
    protected $conexion;

    public function __construct() {
        $this->conexion = new ConexionDb();
    }
    
}