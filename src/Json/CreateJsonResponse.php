<?php

declare(strict_types=1);

namespace Jder\Psr\Json;

use Psr\Http\Message\ResponseInterface as Response;
use Jder\Psr\Json\Functions\CreateJsonFailureResponseFunctions;
use Jder\Psr\Json\Functions\CreateJsonSuccessResponseFunctions;

/**
 * Function to create JSON response.
 *
 * @api
 */
class CreateJsonResponse
{
    public static function success(
        Response $response,
    ): CreateJsonSuccessResponseFunctions {
        return new CreateJsonSuccessResponseFunctions($response);
    }

    public static function failure(
        Response $response,
    ): CreateJsonFailureResponseFunctions {
        return new CreateJsonFailureResponseFunctions($response);
    }
}
