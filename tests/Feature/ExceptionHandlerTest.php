<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Konnec\Helpers\Exceptions\Handler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

beforeEach(function () {
    $this->handler = new Handler(app());
    $this->json = Request::create('/api/x', 'GET', server: ['HTTP_ACCEPT' => 'application/json']);
});

it('renders a missing model as a json 404', function () {
    $response = $this->handler->render($this->json, new ModelNotFoundException);

    expect($response->getStatusCode())->toBe(404)
        ->and(json_decode($response->getContent(), true))->toBe(['errors' => 'Model not found']);
});

it('renders a missing route as a json 404', function () {
    $response = $this->handler->render($this->json, new NotFoundHttpException);

    expect($response->getStatusCode())->toBe(404)
        ->and(json_decode($response->getContent(), true))->toBe(['errors' => 'Route not found']);
});

it('falls back to the default rendering for other json exceptions', function () {
    $response = $this->handler->render($this->json, new RuntimeException('boom'));

    expect($response->getStatusCode())->toBe(500);
});

it('uses default rendering for non-json requests', function () {
    $html = Request::create('/web', 'GET');

    $response = $this->handler->render($html, new NotFoundHttpException);

    expect($response->getStatusCode())->toBe(404)
        ->and($response->headers->get('Content-Type'))->not->toContain('json');
});
