<?php
declare(strict_types=1);

namespace Ds7\Semestral\Core\Db;

use Ds7\Semestral\Core\Model\Invitacion;
use Ds7\Semestral\Core\Model\Asistencia;

interface PersistenceGatewayOperations {
    
    /**
     * Carga a lista de barcos registrados.
     */
    function obtenerInvitaciones(): array;

    /**
     * Obtiene una invitacion por n&uacute;mero
     */
    function obtenerInvitacion(int $numero): Invitacion;


    /**
     * Carga la lista de patronos registrados
     */
    function obtenerAsistencias(): array;

    /**
     * Guarda un viaje
     * @throws GenericPersistenceError
     */
    function guardarAsistencia(Asistencia $asistencia): void;


    /**
     * Busca invitaciones en base a filtro
     */
    function buscarInvitacion(mixed $busqueda): array;
}