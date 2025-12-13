<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Jder\Psr\Json\CreateJsonResponse;
use Jder\Psr\Json\JsonResponseError;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpInternalServerErrorException;

require __DIR__ . "/../vendor/autoload.php";

$app = AppFactory::create();

$app->get("/", function (
    Request $request,
    Response $response,
    $args,
): Response {
    return CreateJsonResponse::success($response)->create();
});

$app->get("/hello", function (
    Request $request,
    Response $response,
    $args,
): Response {
    return CreateJsonResponse::success($response)
        ->setData([
            "message" => "Hello, World!",
        ])
        ->create();
});

$app->get("/server", function (
    Request $request,
    Response $response,
    $args,
): Response {
    throw new HttpInternalServerErrorException($request);
});

$errorMiddleware = $app->addErrorMiddleware(
    $displayErrorDetails = true,
    $logErrors = true,
    $logErrorDetails = true,
);

$errorHandler = function (
    Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
) use ($app): Response {
    $response = $app->getResponseFactory()->createResponse();

    $status = $exception->getCode();

    $error = new JsonResponseError();

    if ($exception instanceof HttpNotFoundException) {
        $error->setCode("not_found")->setMessage("Content not found");
    } else {
        $error->setCode("server")->setMessage("Internal server error");
    }

    return CreateJsonResponse::failure($response)
        ->setStatus($status)
        ->addError($error)
        ->create();
};

$errorMiddleware->setDefaultErrorHandler($errorHandler);

$app->run();
