<?php

namespace Ds7\Semestral\Infrastructure\Web\Controller;

use Ds7\Semestral\Application\ResponseEmitter;
use Ds7\Semestral\Application\TemplatesProcessor;
use Psr\Http\Message\ResponseInterface;

class WelcomeController extends AbstractController
{
    public function __construct(ResponseInterface  $response,
                                TemplatesProcessor $templatesProcessor,
                                ResponseEmitter    $responseEmitter)
    {
        $this->response = $response;
        $this->responseEmitter = $responseEmitter;
        $this->templatesProcessor = $templatesProcessor;
    }

    public function __invoke(): void
    {
        $this->view('welcome.html', []);
    }
}