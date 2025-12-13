# JDER PSR

A response builder for PSR.

This package includes different response builders based on the JSON response structure specified in [JSON Data Errors Response (JDER)](https://github.com/jderstd/spec). With the builders, various kinds of responses can be created easily instead of sending plain text responses.

## Quick Start

To create a JSON response, use the following code:

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

## Documentation

For the documentation,please refer to the 
[documentation](https://github.com/jderstd/psr/blob/main/docs/README.md).

## Contributing

For contributing, please refer to the 
[contributing guide](https://github.com/jderstd/psr/blob/main/CONTRIBUTING.md).

## License

This project is licensed under the terms of the MIT license.
