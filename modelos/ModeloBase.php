<?php

abstract class ModeloBase {
    protected $pdo;

    public function __construct() {
        $conexionDb = new ConexionDb();
        $this->pdo = $conexionDb->getPdo();
    }
    
}