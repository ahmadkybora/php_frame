<?php

namespace App\Services\Exception;

use Exception;

class ForbiddenException extends Exception
{
    protected $message = 'Forbidden';
    protected $code = 403;
}