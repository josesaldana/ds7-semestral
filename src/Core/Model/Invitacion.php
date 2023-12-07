<?php

declare(strict_types=1);

namespace Ds7\Semestral\Core\Model;

class Invitacion {
    public function __construct(
        public int $numero,
        public string $invitado,
        public int $mesa,
        public int $acompanantes
    ) { }
}