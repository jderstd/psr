<?php

declare(strict_types=1);

use Jder\Psr\Json\CreateJsonResponse;
use Jder\Psr\Json\JsonResponseError;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

require __DIR__ . "/../../vendor/autoload.php";

$factory = new Psr17Factory();

$creator = new ServerRequestCreator($factory, $factory, $factory, $factory);

$request = $creator->fromGlobals();

$response = $factory->createResponse();

$method = $request->getMethod();

$path = $request->getUri()->getPath();

try {
    if ("GET" === $method && "/" === $path) {
        $response = CreateJsonResponse::success($response)->create();
    } elseif ("GET" === $method && "/hello" === $path) {
        $response = CreateJsonResponse::success($response)
            ->setData([
                "message" => "Hello, World!",
            ])
            ->create();
    } elseif ("GET" === $method && "/server" === $path) {
        throw new RuntimeException("Internal server error", 500);
    } else {
        throw new RuntimeException("Not found", 404);
    }
} catch (Throwable $exception) {
    $status = $exception->getCode() > 0 ? $exception->getCode() : 500;

    $error = new JsonResponseError();

    if (404 === $status) {
        $error->setCode("not_found")->setMessage("Content not found");
    } else {
        $error->setCode("server")->setMessage("Internal server error");
    }

    $response = CreateJsonResponse::failure($response)
        ->setStatus($status)
        ->addError($error)
        ->create();
}

http_response_code($response->getStatusCode());

foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf("%s: %s", $name, $value), false);
    }
}

echo (string) $response->getBody();
