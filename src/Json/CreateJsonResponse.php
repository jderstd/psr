<?php

declare(strict_types=1);

namespace Jder\Psr\Json;

use Jder\Psr\Json\Functions\CreateFailureJsonResponseFunctions;
use Jder\Psr\Json\Functions\CreateSuccessJsonResponseFunctions;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Function to create JSON response.
 */
class CreateJsonResponse
{
    /**
     * Create a success JSON response.
     */
    public static function success(Response $response): CreateSuccessJsonResponseFunctions
    {
        return new CreateSuccessJsonResponseFunctions($response);
    }

    /**
     * Create a failure JSON response.
     */
    public static function failure(Response $response): CreateFailureJsonResponseFunctions
    {
        return new CreateFailureJsonResponseFunctions($response);
    }
}
