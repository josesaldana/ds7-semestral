<?php

namespace Ds7\Semestral\Infrastructure\Web\Controller;

use Ds7\Semestral\Core\Model\Invitacion;
use Ds7\Semestral\Core\UseCase\ListarInvitacionesUseCase;
use Ds7\Semestral\Core\UseCase\BuscarInvitacionUseCase;
use Ds7\Semestral\Application\ResponseEmitter;
use Ds7\Semestral\Application\TemplatesProcessor;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class InvitacionesController extends AbstractController
{
    private ListarInvitacionesUseCase $listarInvitacionesUseCase;
    private BuscarInvitacionUseCase $buscarInvitacionUseCase;

    public function __construct(ResponseInterface  $response,
                                TemplatesProcessor $templatesProcessor,
                                ResponseEmitter    $responseEmitter,
                                ListarInvitacionesUseCase $listarInvitacionesUseCase,
                                BuscarInvitacionUseCase $buscarInvitacionUseCase) {
        parent::__construct($response, $templatesProcessor, $responseEmitter);

        $this->listarInvitacionesUseCase = $listarInvitacionesUseCase;
        $this->buscarInvitacionUseCase = $buscarInvitacionUseCase;
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
            (isset($queryParams['busqueda-invitado']) && !empty(trim($queryParams['busqueda-invitado']))) ?
                $this->buscarInvitacionUseCase->buscarInvitacion(trim($queryParams['busqueda-invitado'])) :
                $this->listarInvitacionesUseCase->listarinvitaciones();

        try {
            $this->view('lista-invitaciones.html', ['invitaciones' => $invitaciones]);
        } catch (\Exception $e) {
            $this->error($e);
        }
    }

    /**
     * Muestra el formulario para registrar nuevo viaje.
     */
    public function mostrarNuevoFormularioDeNuevoAsistencia(): void {
        $this->view('nuevo-viaje.html');
    }
}