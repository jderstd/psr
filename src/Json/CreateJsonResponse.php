<?php

declare(strict_types=1);

namespace Jder\Psr\Json;

use Jder\Psr\Json\Functions\CreateFailureJsonResponse;
use Jder\Psr\Json\Functions\CreateSuccessJsonResponse;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Function to create JSON response.
 */
class CreateJsonResponse
{
    /**
     * Create a success JSON response.
     */
    public static function success(Response $response): CreateSuccessJsonResponse
    {
        return new CreateSuccessJsonResponse($response);
    }

    /**
     * Create a failure JSON response.
     */
    public static function failure(Response $response): CreateFailureJsonResponse
    {
        return new CreateFailureJsonResponse($response);
    }
}
