<?php

namespace Ds7\Semestral\Application;

use Psr\Http\Message\ResponseInterface;

/**
 * Emitter for `ResponseInterface` messages. Will be used in Presenters
 * to actually emit the response back to the caller.
 */
interface ResponseEmitter
{
    public function emit(ResponseInterface $response): void;
}