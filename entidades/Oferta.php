<?php
class Oferta
{
    public function __construct(
        public readonly ?int $idOferta,
        public readonly string $fechaInicio,
        public readonly ?string $fechaFin,
        public readonly string $nombrePuesto,
        public readonly ?int $experienciaAnios,
        public readonly bool $requiereIngles,
        public readonly ?string $tecnologia,
        public readonly int $idEnvio,
        public readonly int $idEmpresa,
        public readonly int $idEstado,
        public readonly int $idTipoTrabajo,
        public readonly int $idUsuario,
        public readonly ?int $idContacto,
    ) {}

    public static function desdeFila(array $fila): self
    {
        return new self(
            isset($fila['id_oferta']) ? (int) $fila['id_oferta'] : null,
            $fila['fecha_inicio'],
            $fila['fecha_fin'],
            $fila['nombre_puesto'],
            $fila['experiencia_anios'] !== null ? (int) $fila['experiencia_anios'] : null,
            (bool) $fila['requiere_ingles'],
            $fila['tecnologia'],
            (int) $fila['id_envio'],
            (int) $fila['id_empresa'],
            (int) $fila['id_estado'],
            (int) $fila['id_tipo_trabajo'],
            (int) $fila['id_usuario'],
            $fila['id_contacto'] !== null ? (int) $fila['id_contacto'] : null,
        );
    }

    public function perteneceA(int $idUsuario): bool
    {
        return $this->idUsuario === $idUsuario;
    }
}