<?php

namespace Ds7\Semestral\Core;

use Throwable;

/**
 * Interface common to all Presenters, declares a method for presenting
 * errors.
 */
interface ErrorHandling
{
    public function error(Throwable $error);
}