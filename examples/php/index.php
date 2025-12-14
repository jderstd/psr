<?php

declare(strict_types=1);

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Jder\Psr\Json\CreateJsonResponse;
use Jder\Psr\Json\JsonResponseError;

require __DIR__ . "/../../vendor/autoload.php";

$factory = new Psr17Factory();

$creator = new ServerRequestCreator($factory, $factory, $factory, $factory);

$request = $creator->fromGlobals();

$response = $factory->createResponse();

$method = $request->getMethod();

$path = $request->getUri()->getPath();

try {
    if ($method === "GET" && $path === "/") {
        $response = CreateJsonResponse::success($response)->create();
    } elseif ($method === "GET" && $path === "/hello") {
        $response = CreateJsonResponse::success($response)
            ->setData([
                "message" => "Hello, World!",
            ])
            ->create();
    } elseif ($method === "GET" && $path === "/server") {
        throw new RuntimeException("Internal server error", 500);
    } else {
        throw new RuntimeException("Not found", 404);
    }
} catch (Throwable $exception) {
    $status = $exception->getCode() > 0 ? $exception->getCode() : 500;

    $error = new JsonResponseError();

    if ($status === 404) {
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
