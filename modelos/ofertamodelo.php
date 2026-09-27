<?php
require_once __DIR__ . '/ModeloBase.php';

class OfertaModelo extends ModeloBase
{
    protected string $tabla = 'oferta';
    protected string $clavePrimaria = 'id_oferta';

    private const COLUMNAS_ORDEN_PERMITIDAS = [
        'fecha_creacion' => 'o.fecha_creacion',
        'fecha_inicio' => 'o.fecha_inicio',
        'nombre_puesto' => 'o.nombre_puesto',
        'nombre_empresa' => 'emp.nombre_empresa',
        'estado' => 'est.nombre',
    ];

    private function construirCondiciones(int $idUsuario, ?int $idEmpresa, ?int $idEnvio, ?int $idEstado, ?int $idTipoTrabajo): array
    {
        $condiciones = ['o.id_usuario = ?'];
        $parametros = [$idUsuario];

        if ($idEmpresa !== null) { $condiciones[] = 'o.id_empresa = ?'; $parametros[] = $idEmpresa; }
        if ($idEnvio !== null) { $condiciones[] = 'o.id_envio = ?'; $parametros[] = $idEnvio; }
        if ($idEstado !== null) { $condiciones[] = 'o.id_estado = ?'; $parametros[] = $idEstado; }
        if ($idTipoTrabajo !== null) { $condiciones[] = 'o.id_tipo_trabajo = ?'; $parametros[] = $idTipoTrabajo; }

        return [implode(' AND ', $condiciones), $parametros];
    }

    public function listarConFiltros(
        int $idUsuario,
        ?int $idEmpresa = null,
        ?int $idEnvio = null,
        ?int $idEstado = null,
        ?int $idTipoTrabajo = null,
        int $pagina = 1,
        int $porPagina = 10,
        string $ordenarPor = 'fecha_creacion',
        string $direccion = 'DESC'
    ): array {
        [$whereSql, $parametros] = $this->construirCondiciones($idUsuario, $idEmpresa, $idEnvio, $idEstado, $idTipoTrabajo);

        $pagina = max(1, $pagina);
        $porPagina = min(50, max(1, $porPagina));
        $offset = ($pagina - 1) * $porPagina;

        $columnaOrden = self::COLUMNAS_ORDEN_PERMITIDAS[$ordenarPor] ?? self::COLUMNAS_ORDEN_PERMITIDAS['fecha_creacion'];
        $direccion = strtoupper($direccion) === 'ASC' ? 'ASC' : 'DESC';

        $sql = "SELECT o.id_oferta, o.nombre_puesto, emp.nombre_empresa, e.tipo AS medio_envio,
                       est.nombre AS estado, tt.nombre AS tipo_trabajo, u.usuario AS usuario,
                       c.nombre_contacto AS contacto, o.experiencia_anios, o.requiere_ingles,
                       o.tecnologia, o.fecha_inicio, o.fecha_fin, o.fecha_creacion
                FROM oferta o
                INNER JOIN empresa emp ON o.id_empresa = emp.id_empresa
                INNER JOIN envio e ON o.id_envio = e.id_envio
                INNER JOIN estado_oferta est ON o.id_estado = est.id_estado
                INNER JOIN tipo_trabajo tt ON o.id_tipo_trabajo = tt.id_tipo_trabajo
                INNER JOIN usuario u ON o.id_usuario = u.id_usuario
                LEFT JOIN contacto c ON o.id_contacto = c.id_contacto
                WHERE $whereSql
                ORDER BY $columnaOrden $direccion
                LIMIT $porPagina OFFSET $offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll();
    }

    public function contarConFiltros(
        int $idUsuario,
        ?int $idEmpresa = null,
        ?int $idEnvio = null,
        ?int $idEstado = null,
        ?int $idTipoTrabajo = null
    ): int {
        [$whereSql, $parametros] = $this->construirCondiciones($idUsuario, $idEmpresa, $idEnvio, $idEstado, $idTipoTrabajo);

        $sql = "SELECT COUNT(*) FROM oferta o WHERE $whereSql";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($parametros);
        return (int) $stmt->fetchColumn();
    }

    public function listarPorUsuario(int $idUsuario): array
    {
        return $this->listarConFiltros($idUsuario);
    }
}