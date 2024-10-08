<?php

namespace Konnec\Helpers\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ExceptionTrait
{
    public function apiException($request, $e)
    {
        if ($this->isModel($e)) {
            return $this->ModelResponse($e);
        }

        if ($this->isHttp($e)) {
            return $this->HttpResponse($e);
        }

        return parent::render($request, $e);
    }

    protected function isModel($e): bool
    {
        return $e instanceof ModelNotFoundException;
    }

    protected function isHttp($e): bool
    {
        return $e instanceof NotFoundHttpException;
    }

    protected function ModelResponse($e): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'errors' => 'Model not found',
        ], Response::HTTP_NOT_FOUND);
    }

    protected function HttpResponse($e): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'errors' => 'Route not found',
        ], Response::HTTP_NOT_FOUND);
    }
}
