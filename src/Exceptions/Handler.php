<?php

namespace Konnec\LaravelHelpers\Exceptions;

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

    public function report(Throwable $exception): void
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception): Response
    {
        if ($request->expectsJson()) {
            return $this->apiException($request, $exception);
        }

        return parent::render($request, $exception);
    }
}
