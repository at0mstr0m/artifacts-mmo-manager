<?php

declare(strict_types=1);

namespace App\Exceptions;

class SlotOccupiedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Item slot is occupied');
    }
}
