<?php
declare(strict_types=1);

namespace Ds7\Semestral\Core\UseCase;

use Ds7\Semestral\Core\Model\Asistencia;
use Ds7\Semestral\Core\Db\PersistenceGatewayOperations;

class RegistrarAsistenciaUseCase {
    private PersistenceGatewayOperations $persistance;

    public function __construct(PersistenceGatewayOperations $persistance) {
        $this->persistence = $persistance;
    }
    
    /**
     * Registra un nuevo asistencia.
     * 
     * @throws Exception
     * @todo Agregar validaciones de negocio (patrón y barco no existente, 
     *      sobreasignación de patrón/hora, etc)
     * @todo Cambiar Exception a domain exception
     */
    public function registrarAsistencia(int $invitacion, int $acompanantes): void {
        $invitacion = $this->persistence->obtenerInvitacion(numero: $invitacion);

        // TODO: Validar que el numero de acompañantes que asiste sea menor al invitado?

        $asistencia = new Asistencia(
            id: 0,
            invitacion: $invitacion,
            acompanantes: $acompanantes
        );

        $this->persistence->guardarAsistencia($asistencia);
    }
}