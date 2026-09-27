<?php

abstract class ModeloBase
{
    protected PDO $pdo;
    protected string $tabla;
    protected string $clavePrimaria;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE {$this->clavePrimaria} = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public function eliminar(int $id, array $condicionesExtra = []): bool
    {
        $condiciones = ["{$this->clavePrimaria} = ?"];
        $parametros = [$id];

        foreach ($condicionesExtra as $columna => $valor) {
            $condiciones[] = "$columna = ?";
            $parametros[] = $valor;
        }

        $sql = "DELETE FROM {$this->tabla} WHERE " . implode(' AND ', $condiciones);

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parametros);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new RuntimeException("No se puede eliminar: hay registros asociados que lo impiden.");
            }
            throw $e;
        }
    }
}