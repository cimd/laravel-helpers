<?php

namespace Konnec\Helpers\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    use ExceptionTrait;

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e): Response
    {
        if ($request->expectsJson()) {
            return $this->apiException($request, $e);
        }

        return parent::render($request, $e);
    }
}
