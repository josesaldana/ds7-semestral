<?php
declare(strict_types=1);

namespace Ds7\Semestral\Infrastructure\Db;

use Ds7\Semestral\Core\Model\Invitacion;
use Ds7\Semestral\Core\Model\Asistencia;
use Ds7\Semestral\Core\Db\PersistenceGatewayOperations;
use Ds7\Semestral\Core\Db\GenericPersistenceError;
use Ds7\Semestral\Core\Model\InvalidDomainObjectError;
use mysqli;

class MySQLPersistenceGateway implements PersistenceGatewayOperations
{
    private mysqli $db;
    private PersistenceMapper $mapper;

    /**
     * @param mysqli $db
     * @param PersistenceMapper $mapper
     */
    public function __construct(mysqli $db, PersistenceMapper $mapper)
    {
        $this->db = $db;
        $this->mapper = $mapper;
    }

    /**
     * Obtener lista de Barcos
     * 
     * @see Ds7\Semestral\Core\Model\Invitacion
     */
    public function obtenerInvitaciones(): array {
        $resultados = $this->db
            ->query("SELECT 
                        i.* 
                    FROM invitaciones i 
                        LEFT JOIN asistencias a ON i.numero = a.invitacion 
                    WHERE a.invitacion IS NULL")
            ->fetch_all(MYSQLI_ASSOC);

        return array_map(fn($record) => $this->mapper->convertToInvitacion($record), $resultados);
    }

    /**
     * Obtiene una invitaci&oacute;n por n&uacute;mero
     * 
     * @see Ds7\Semestral\Core\Model\Invitacion
     */
    public function obtenerInvitacion(int $numero): Invitacion {
        $resultados = $this->db
            ->execute_query("SELECT * FROM invitaciones WHERE numero = ? LIMIT 1", [$numero])
            ->fetch_all(MYSQLI_ASSOC);

        return $this->mapper->convertToInvitacion($resultados[0]);
    }

    /**
     * Obtener lista de Asistencias
     * 
     * @see Ds7\Semestral\Core\Model\Asistencia
     */
    public function obtenerAsistencias(): array {
        $resultados = $this->db
            ->query("SELECT 
                        'id', a.id,
                        'acompanantes', a.acompanantes,
                        (SELECT JSON_OBJECT(
                            'numero', i.numero,
                            'invitado', i.invitado,
                            'mesa', i.mesa,
                            'acompanantes', i.acompanantes
                        ) FROM invitaciones i WHERE a.invitacion = i.numero) AS 'invitacion'
                    FROM asistencias a")
            ->fetch_all(MYSQLI_ASSOC);

        $asistencias = array_map(fn($record) => $this->mapper->convertToAsistencia($record), $resultados);

        return $asistencias;
    }

    /**
     * Registrar una asistencia
     * 
     * @param Asistencia $asistencia
     */
    public function guardarAsistencia(Asistencia $asistencia): void {
        $sql = "INSERT INTO asistencias (invitacion, acompanantes) VALUES(?, ?)";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param(
            "ii",
            $asistencia->invitacion->numero,
            $asistencia->acompanantes,
        );

        $stmt->execute();

        $stmt->close();
    } 

    /**
     * Busca invitaciones por mesa o invitado
     * 
     * @return array of invitaciones
     */
    public function buscarInvitacion(int $mesa = null, string $invitado = null): array {
        $resultados = $this->db
            ->execute_query("SELECT 
                        v.*,
                        barco.barco_in_json as barco,
                        (SELECT JSON_OBJECT(
                            'codigo', p.codigo,
                            'nombre', p.nombre,
                            'telefono', p.telefono
                        ) FROM conductor_patron p WHERE v.codpatron = p.codigo) as patron
                    FROM viaje v
                        LEFT JOIN(
                            SELECT b.matricula,
                            (SELECT JSON_OBJECT(
                                'matricula', b.matricula,
                                'nombre', b.nombre_barco,
                                'numamarre', b.numamarre,
                                'cuota', b.cuota            
                            )) AS barco_in_json
                            FROM barco b WHERE MATCH (b.nombre_barco) AGAINST (? IN NATURAL LANGUAGE MODE)
                        ) barco ON v.matribarco = barco.matricula
                    WHERE barco.matricula IS NOT NULL", [$busqueda])
            ->fetch_all(MYSQLI_ASSOC);

        $invitaciones = array_map(fn($record) => $this->mapper->convertToViaje($record), $resultados);

        return $invitaciones;
    }
}