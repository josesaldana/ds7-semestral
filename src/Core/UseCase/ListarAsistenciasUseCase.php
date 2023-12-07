<?php
declare(strict_types=1);

namespace Ds7\Semestral\Core\UseCase;

use Ds7\Semestral\Core\Model\Asistencia;
use Ds7\Semestral\Core\Db\PersistenceGatewayOperations;

class ListarAsistenciasUseCase {
    private PersistenceGatewayOperations $persistence;

    public function __construct(PersistenceGatewayOperations $persistence) {
        $this->persistence = $persistence;
    }

    public function listarAsistencias(string $busqueda = NULL): array {
        if (isset($busqueda) && !empty(trim($busqueda))) {
            return $this->persistence->buscarAsistencias($busqueda);
        } else {
            return $this->persistence->obtenerAsistencias();
        }
    }
}