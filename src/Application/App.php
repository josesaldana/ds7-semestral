<?php
declare(strict_types=1);

/*
    COPYRIGHT DISCLAIMER:
    --------------------

    A lot of code in this project is modified from or directly inspired by
    the excellent example from Kevin Smith: https://github.com/kevinsmith/no-framework

    Here is the related article by Kevin Smith: https://kevinsmith.io/modern-php-without-a-framework/
 */

namespace Ds7\Semestral\Application;

use DI\ContainerBuilder;
use Ds7\Semestral\Config\ConfigAdapter;
use Ds7\Semestral\Infrastructure\Web\Controller\WelcomeController;
use Ds7\Semestral\Infrastructure\Web\Controller\AsistenciasController;
use Ds7\Semestral\Infrastructure\Web\Controller\InvitacionesController;
use Ds7\Semestral\Core\Db\PersistenceGatewayOperations;
use Ds7\Semestral\Infrastructure\Db\MySQLPersistenceGateway;
use Ds7\Semestral\Infrastructure\Db\PersistenceMapper;
use Ds7\Semestral\Core\UseCase\ListarInvitacionesUseCase;
use Ds7\Semestral\Core\UseCase\ListarAsistenciasUseCase;
use Ds7\Semestral\Core\UseCase\RegistrarAsistenciaUseCase;
use mysqli;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Middlewares\FastRoute;
use Middlewares\GzipEncoder;
use Middlewares\Expires;
use Middlewares\RequestHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Relay\Relay;
use function DI\autowire;
use function DI\create;
use function FastRoute\simpleDispatcher;

class App
{

    public function setupTemplatesProcessor(string $templatesDir): TemplatesProcessor
    {
        return new TwigTemplatesProcessor($templatesDir);
    }

    public function setupResponseEmitter(): ResponseEmitter
    {
        return new SapiResponseEmitter();
    }

    public function setupContainer(TemplatesProcessor $templatesProcessor,
                                   ResponseEmitter    $responseEmitter,
                                   mysqli $db): ContainerInterface
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAutowiring(true);
        $containerBuilder->useAnnotations(false);
        $containerBuilder->addDefinitions([

            // PSR-7 request and response
            ResponseInterface::class => create(Response::class),
            ServerRequestInterface::class => function () {
                return ServerRequestFactory::fromGlobals();
            },

            // template processor, response emitter, etc.
            TemplatesProcessor::class => $templatesProcessor,
            ResponseEmitter::class => $responseEmitter,

            mysqli::class => $db,

            PersistenceMapper::class => create(PersistenceMapper::class),
            PersistenceGatewayOperations::class => autowire(MySQLPersistenceGateway::class),

            ListarInvitacionesUseCase::class => autowire(ListarInvitacionesUseCase::class),
            ListarAsistenciasUseCase::class => autowire(ListarAsistenciasUseCase::class),
            RegistrarAsistenciaUseCase::class => autowire(RegistrarAsistenciaUseCase::class)
        ]);
        return $containerBuilder->build();
    }

    public function setupRouting($isDebugEnabled = false): Dispatcher
    {
        return simpleDispatcher(function (RouteCollector $r) {
            $r->get('/', InvitacionesController::class);
            $r->get('/invitaciones/list', InvitacionesController::class);
            $r->get('/asistencias', AsistenciasController::class);
            $r->get('/asistencias/list', AsistenciasController::class);
            $r->get('/asistencias/summary', AsistenciasController::class);
            $r->get('/asistencias/create', AsistenciasController::class);
        }, [
            'cacheFile' => __DIR__ . '/route.cache', 
            'cacheDisabled' => $isDebugEnabled,
        ]);
    }

    public function setupMiddleware(Dispatcher $routes, ContainerInterface $container): RequestHandlerInterface
    {
        $middlewareQueue[] = new FastRoute($routes);
        $middlewareQueue[] = new GzipEncoder();
        $middlewareQueue[] = new Expires();
        $middlewareQueue[] = new RequestHandler($container);
        return new Relay($middlewareQueue);
    }

    public function setupPersistence(string $host, string $username, string $password, $database): \mysqli
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $db = new mysqli($host, $username, $password, $database);
        $this->insertData($db);
        return $db;
    }

    public function run(RequestHandlerInterface $requestHandler, ContainerInterface $container): void
    {
        $request = $container->get(ServerRequestInterface::class);
        $requestHandler->handle($request);
    }

    private function insertData(mysqli $db) {
        if ($db->query("SELECT * FROM invitaciones")->num_rows == 0) {
            $db->query("INSERT INTO invitaciones (invitado, mesa, acompanantes) VALUES('Invitado 1', 1, 3);");
            $db->query("INSERT INTO invitaciones (invitado, mesa, acompanantes) VALUES('Invitado 2', 2, 2);");
            $db->query("INSERT INTO invitaciones (invitado, mesa, acompanantes) VALUES('Invitado 3', 3, 1);");
            $db->query("INSERT INTO invitaciones (invitado, mesa, acompanantes) VALUES('Invitado 4', 3, 1);");
            $db->query("INSERT INTO invitaciones (invitado, mesa, acompanantes) VALUES('Invitado 5', 4, 5);");
            $db->query("INSERT INTO invitaciones (invitado, mesa, acompanantes) VALUES('Invitado 6', 5, 2);");
            $db->query("INSERT INTO invitaciones (invitado, mesa, acompanantes) VALUES('Invitado 7', 6, 4);");
            $db->query("INSERT INTO invitaciones (invitado, mesa, acompanantes) VALUES('Invitado 8', 7, 8);");
        }
    }
}