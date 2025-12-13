[< Back](./../README.md)

# JDER PSR

A response builder for PSR.

## Installation

Install this package as a dependency in the project:

```sh
composer require jder/psr
```

## Create a Success JSON Response

To create a JSON response without data, just use `CreateJsonResponse` class:

```php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Jder\Psr\Json\CreateJsonResponse;

function route(
    Request $request,
    Response $response
): Response {
    return CreateJsonResponse::success($response)->toResponse();
}
```

And the response will be shown as below:

```json
{
    "success": true
}
```

## Create a Success JSON Response with Data

The `CreateJsonResponse` class can also be used to insert data to the response:

```php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Jder\Psr\Json\CreateJsonResponse;

function route(
    Request $request,
    Response $response
): Response {
    return CreateJsonResponse::success($response)
        ->setData("Hello, World!")
        ->toResponse();
}
```

And the response will be shown as below:

```json
{
    "success": true,
    "data": "Hello, World!"
}
```

## Create a Failure JSON response

To create a failure JSON response, use `failure` function instead of `success` function:

```php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Jder\Psr\Json\CreateJsonResponse;
use Jder\Psr\Json\JsonResponseError;

function route(
    Request $request,
    Response $response
): Response {
    $err = new JsonResponseError()
        ->setCode("server");

    return CreateJsonResponse::failure($response)
        ->addError($err)
        ->toResponse();
}
```

And the response will be shown as below:

```json
{
    "success": false,
    "errors": [
        {
            "code": "server"
        }
    ]
}
```

## Create a Non-JSON response

To create a non-JSON response, use `createResponse` function:

```php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Jder\Psr\CreateResponse;

function route(
    Request $request,
    Response $response
): Response {
    return new CreateResponse($response)
        ->setBody("Hello, World!")
        ->toResponse();
}
```

And the response will be shown as below:

```
Hello, World!
```
