<?php

declare(strict_types=1);

namespace Jder\Psr\Json\Options;

/**
 * Options for `create` method.
 */
class CreateOptions
{
    /**
     * Whether show the success field.
     *
     * By default, it is `true`.
     */
    public bool $showSuccess = true;

    /**
     * Whether show a verbose JSON.
     *
     * By default, it is `false`.
     */
    public bool $verbose = false;
}
