<?php

namespace Konnec\Helpers\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

trait ExceptionTrait
{
    public function apiException(Request $request, Throwable $e): Response
    {
        if ($this->isModel($e)) {
            return $this->ModelResponse($e);
        }

        if ($this->isHttp($e)) {
            return $this->HttpResponse($e);
        }

        return parent::render($request, $e);
    }

    protected function isModel(Throwable $e): bool
    {
        return $e instanceof ModelNotFoundException;
    }

    protected function isHttp(Throwable $e): bool
    {
        return $e instanceof NotFoundHttpException;
    }

    protected function ModelResponse(Throwable $e): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'errors' => 'Model not found',
        ], Response::HTTP_NOT_FOUND);
    }

    protected function HttpResponse(Throwable $e): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'errors' => 'Route not found',
        ], Response::HTTP_NOT_FOUND);
    }
}
