<?php

namespace App\Services\Middlewares;

abstract class BaseMiddleware
{
    abstract public function execute(); 
}