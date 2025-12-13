<?php

// declare(strict_types=1);

namespace Jder\Psr\Json;

/**
 * Response error code.
 * @api
 */
enum ResponseErrorCode: string
{
    /**
     * Internal server error.
     */
    case Server = "server";
    /**
     * Unknown error.
     */
    case Unknown = "unknown";
}
