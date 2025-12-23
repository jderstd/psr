<?php

declare(strict_types=1);

namespace Jder\Psr\Json;

/**
 * Response error code.
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

    /**
     * Get message of the error code.
     */
    public function getMessage(): string
    {
        return match ($this) {
            self::Server => "Internal server error",
            self::Unknown => "Unknown error",
        };
    }
}
