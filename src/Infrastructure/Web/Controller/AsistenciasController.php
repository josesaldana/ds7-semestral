<?php

namespace Ds7\Semestral\Infrastructure\Web\Controller;

use Ds7\Semestral\Application\ResponseEmitter;
use Ds7\Semestral\Application\TemplatesProcessor;
use Ds7\Semestral\Core\UseCase\ListarAsistenciasUseCase;
use Ds7\Semestral\Core\UseCase\RegistrarAsistenciaUseCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AsistenciasController extends AbstractController
{
    private ListarAsistenciasUseCase $listarAsistenciasUseCase;
    private RegistrarAsistenciaUseCase $registrarAsistenciaUseCase;

    public function __construct(ResponseInterface  $response,
                                TemplatesProcessor $templatesProcessor,
                                ResponseEmitter    $responseEmitter,
                                ListarAsistenciasUseCase $listarAsistenciasUseCase,
                                RegistrarAsistenciaUseCase $registrarAsistenciaUseCase) {
        parent::__construct($response, $templatesProcessor, $responseEmitter);

        $this->listarAsistenciasUseCase = $listarAsistenciasUseCase;
        $this->registrarAsistenciaUseCase = $registrarAsistenciaUseCase;
    }

    public function __invoke(ServerRequestInterface $request): void {
        $path = $request->getServerParams()['REQUEST_URI'];

        if ($path === '/asistencias/list') {
            $this->mostrarListaDeAsistencias();
        } else if ($path === '/asistencias/create') {
            crearAsistencia($request);
        }
    }

    public function mostrarListaDeAsistencias(): void {
        $this->view('asistencias.html', [
            'asistencias' => $this->listarAsistenciasUseCase->listarAsistencias()
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

        ['mesa' => $mesa, 'acompanantes' => $acompanantes] = $requestBody;

        $this->registrarAsistenciaUseCase->registrarInvitacion($mesa, $acompanantes);

        $this->redirect("/invitaciones/list");
    }
}

