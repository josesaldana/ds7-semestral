<?php
declare(strict_types=1);

namespace Ds7\Semestral\Core\Model;

class Patron {
    public function __construct(
        public int $id,
        public int $acompanantes,
        public Invitacion $invitacion
    ) { }
}