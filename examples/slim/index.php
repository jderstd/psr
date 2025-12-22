<?php

declare(strict_types=1);

use Jder\Psr\Json\CreateJsonResponse;
use Jder\Psr\Json\JsonResponseError;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;

require __DIR__ . "/../../vendor/autoload.php";

$app = AppFactory::create();

$app->get("/", fn(
    Request $_request,
    Response $response,
    $_args,
): Response => CreateJsonResponse::success($response)->create());

$app->get("/hello", fn(
    Request $_request,
    Response $response,
    $_args,
): Response => CreateJsonResponse::success($response)
    ->setData([
        "message" => "Hello, World!",
    ])
    ->create());

$app->get("/server", function (
    Request $request,
    Response $_response,
    $_args,
): Response {
    throw new HttpInternalServerErrorException($request);
});

$errorMiddleware = $app->addErrorMiddleware(
    $displayErrorDetails = true,
    $logErrors = true,
    $logErrorDetails = true,
);

$errorHandler = function (
    Request $_request,
    Throwable $exception,
    bool $_displayErrorDetails,
    bool $_logErrors,
    bool $_logErrorDetails,
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
