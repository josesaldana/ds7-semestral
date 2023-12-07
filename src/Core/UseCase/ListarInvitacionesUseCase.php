<?php
declare(strict_types=1);

namespace Ds7\Semestral\Core\UseCase;

use Ds7\Semestral\Core\Model\Invitacion;
use Ds7\Semestral\Core\Db\PersistenceGatewayOperations;

class ListarInvitacionesUseCase {
    private PersistenceGatewayOperations $persistence;

    public function __construct(PersistenceGatewayOperations $persistence) {
        $this->persistence = $persistence;
    }

    public function listarInvitaciones(): array {
        return $this->persistence->obtenerInvitaciones();
    }
}