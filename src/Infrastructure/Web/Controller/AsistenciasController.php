<?php

namespace Ds7\Semestral\Infrastructure\Web\Controller;

use Ds7\Semestral\Application\ResponseEmitter;
use Ds7\Semestral\Application\TemplatesProcessor;
use Ds7\Semestral\Core\UseCase\ListarAsistenciasUseCase;
use Ds7\Semestral\Core\UseCase\ListarInvitacionesUseCase;
use Ds7\Semestral\Core\UseCase\RegistrarAsistenciaUseCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AsistenciasController extends AbstractController
{
    private ListarAsistenciasUseCase $listarAsistenciasUseCase;
    private ListarInvitacionesUseCase $listarInvitacionesUseCase;
    private RegistrarAsistenciaUseCase $registrarAsistenciaUseCase;

    public function __construct(ResponseInterface  $response,
                                TemplatesProcessor $templatesProcessor,
                                ResponseEmitter    $responseEmitter,
                                ListarAsistenciasUseCase $listarAsistenciasUseCase,
                                ListarInvitacionesUseCase $listarInvitacionesUseCase,
                                RegistrarAsistenciaUseCase $registrarAsistenciaUseCase) {
        parent::__construct($response, $templatesProcessor, $responseEmitter);

        $this->listarAsistenciasUseCase = $listarAsistenciasUseCase;
        $this->listarInvitacionesUseCase = $listarInvitacionesUseCase;
        $this->registrarAsistenciaUseCase = $registrarAsistenciaUseCase;
    }

    public function __invoke(ServerRequestInterface $request): void {
        $path = $request->getServerParams()['REQUEST_URI'];

        if ($path === '/asistencias') {
            $this->mostrarAsistencias();
        } else if ($path === '/asistencias/list') {
            $this->mostrarListaDeAsistencias();
        } else if ($path === '/asistencias/summary') {
            $this->mostrarSummaryDeAsistencia();
        } else if ($path === '/asistencias/create') {
            $this->crearAsistencia($request);
        }
    }

    public function mostrarAsistencias(): void {
        $this->view('asistencias.html', []);
    }

    public function mostrarListaDeAsistencias(): void {
        $this->view('lista-asistencias.html', [
            'asistencias' => $this->listarAsistenciasUseCase->listarAsistencias()
        ]);
    }

    /**
     * Muestra un resumen de cuantas invitaciones se hicieron y cuantas 
     * han sido consumidas
     */
    public function mostrarSummaryDeAsistencia() {
        $this->view('summary-asistencias.html', [
            'invitaciones' => count($this->listarInvitacionesUseCase->listarInvitaciones()),
            'asistencias' => count($this->listarAsistenciasUseCase->listarAsistencias()),
        ]);
    }

    /**
     * Acción para crear un nuevo viaje.
     * 
     * @todo Validar entrada (tipos de datos, etc) y enviar error si aplica
     * @todo Traducir errors de dominio a errores de interfaz web
     */
    public function crearAsistencia(ServerRequestInterface $request) {
        $requestBody = $request->getParsedBody();

        ['invitacion' => $invitacion, 'acompanantes' => $acompanantes] = $requestBody;

        try {
            $this->registrarAsistenciaUseCase->registrarAsistencia($invitacion, $acompanantes);
            $this->redirect("/invitaciones/list");
        } catch (\Exception $e) {
            $this->view('error.html', ['message' => $e->message]);
        }
    }
}

