<?php

declare(strict_types=1);

namespace Jder\Psr\Json;

use Jder\Psr\Json\Functions\CreateJsonFailureResponseFunctions;
use Jder\Psr\Json\Functions\CreateJsonSuccessResponseFunctions;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Function to create JSON response.
 */
class CreateJsonResponse
{
    public static function success(Response $response): CreateJsonSuccessResponseFunctions
    {
        return new CreateJsonSuccessResponseFunctions($response);
    }

    public static function failure(Response $response): CreateJsonFailureResponseFunctions
    {
        return new CreateJsonFailureResponseFunctions($response);
    }
}
