<?php

namespace Ds7\Semestral\Infrastructure\Web\Controller;

use Ds7\Semestral\Core\ErrorHandling;
use Ds7\Semestral\Application\ResponseEmitter;
use Ds7\Semestral\Application\TemplatesProcessor;
use Psr\Http\Message\ResponseInterface;
use Throwable;

abstract class AbstractController implements ErrorHandling
{
    protected ResponseInterface $response;
    protected ResponseEmitter $responseEmitter;
    protected TemplatesProcessor $templatesProcessor;

    public function __construct(ResponseInterface  $response,
                                TemplatesProcessor $templatesProcessor,
                                ResponseEmitter    $responseEmitter) {
        $this->response = $response;
        $this->responseEmitter = $responseEmitter;
        $this->templatesProcessor = $templatesProcessor;
    }

    public function error(Throwable $error) {
        // log error
        error_log("Error: {$error->getMessage()}", 4);
        $this->view('error.html', ['message' => $error->getMessage()], 500);
    }

    protected function view(
            string $template, 
            array $args = [], 
            int $status = 200, 
            string $contentType = 'text/html'): void {
        $response = $this->response->withHeader('Content-Type', $contentType)
            ->withStatus($status);

        $body = $this->templatesProcessor->processTemplate($template, $args);

        $response->getBody()->write($body);
        $this->responseEmitter->emit($response);
    }

    protected function text(string $text): void {
        $this->response->getBody()->write($text);
        $this->responseEmitter->emit($this->response);
    }

    protected function redirect(string $redirectUri): void {
        header('Location: ' . $redirectUri);
    }
}