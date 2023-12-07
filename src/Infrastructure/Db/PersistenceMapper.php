<?php

namespace Ds7\Semestral\Infrastructure\Db;

use Ds7\Semestral\Core\Model\Invitacion;
use Ds7\Semestral\Core\Model\Asistencia;
use Ds7\Semestral\Core\Model\InvalidDomainObjectError;

/**
 * This mapper will convert model entities to and from corresponding
 * persistent entities. Mapping might not be one-to-one. Use cases
 * will only ever operate with model entities.
 */
class PersistenceMapper
{
    public function convertToInvitacion(array $input): Invitacion {
        $invitacion = new Invitacion(
            numero: $input['numero'],
            invitado: $input['invitado'],
            mesa: $input['mesa'],
            acompanantes: $input['acompanantes']
        );

        return $invitacion;
    }

    public function convertToAsistencia(array $input): Asistencia {
        $invitacion = json_decode($input['invitacion']);

        $asistencia = new Asistencia(
            id: $input['id'],
            acompanantes: $input['acompanantes'],
            invitacion: $this->convertToInvitacion([
                'numero' => $invitacion->numero,
                'invitado' => $invitacion->invitado,
                'mesa' => $invitacion->mesa,
                'acompanantes' => $invitacion->acompanantes
            ])
        );

        return $asistencia;
    }
}