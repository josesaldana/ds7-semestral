<?php

namespace Ds7\Semestral\Infrastructure\Web\Controller;

use Ds7\Semestral\Core\Model\Invitacion;
use Ds7\Semestral\Core\UseCase\ListarInvitacionesUseCase;
use Ds7\Semestral\Application\ResponseEmitter;
use Ds7\Semestral\Application\TemplatesProcessor;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class InvitacionesController extends AbstractController
{
    private ListarInvitacionesUseCase $listarInvitacionesUseCase;

    public function __construct(ResponseInterface  $response,
                                TemplatesProcessor $templatesProcessor,
                                ResponseEmitter    $responseEmitter,
                                ListarInvitacionesUseCase $listarInvitacionesUseCase) {
        parent::__construct($response, $templatesProcessor, $responseEmitter);
        $this->listarInvitacionesUseCase = $listarInvitacionesUseCase;
    }

    /**
     * Método principal que despacha todas las acciones soportadas por el controlador.
     */
    public function __invoke(ServerRequestInterface $request): void {
        $path = $request->getServerParams()['REQUEST_URI'];

        if ($path === "/") {
            $this->mostrarPaginaPrincipalDeInvitaciones();
        } else if ('/invitaciones/summary') {
            $this->mostrarListaDeInvitaciones($request);
        } else if ($path === '/invitaciones/list') {
            $this->mostrarNuevoFormularioDeNuevoAsistencia();
        } 
    }

    public function mostrarPaginaPrincipalDeInvitaciones(): void {
        $this->view('invitaciones.html', []);
    }

    /**
     * Muestra la lista de viajes registrados.
     */
    public function mostrarListaDeInvitaciones(ServerRequestInterface $request): void {
        $queryParams = $request->getQueryParams();

        $invitaciones = 
            (isset($queryParams['busqueda-viaje']) && !empty(trim($queryParams['busqueda-viaje']))) ?
                $this->listarInvitacionesUseCase->listarinvitaciones(trim($queryParams['busqueda-viaje'])) :
                $this->listarInvitacionesUseCase->listarinvitaciones();

        $this->view('lista-invitaciones.html', ['invitaciones' => $invitaciones]);
    }

    /**
     * Muestra el formulario para registrar nuevo viaje.
     */
    public function mostrarNuevoFormularioDeNuevoAsistencia(): void {
        $this->view('nuevo-viaje.html');
    }
}