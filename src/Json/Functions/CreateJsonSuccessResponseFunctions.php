<?php

declare(strict_types=1);

namespace Jder\Psr\Json\Functions;

use Jder\Psr\Json\Functions\Base\CreateJsonResponseFunctionBase;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Functions to create success response in JSON.
 */
class CreateJsonSuccessResponseFunctions extends CreateJsonResponseFunctionBase
{
    public function __construct(Response $response)
    {
        parent::__construct($response);
        $this->status = 200;
        $this->json->setSuccess(true);
    }

    /**
     * Requested information for the response when `success` is `true`.
     */
    public function getData(): mixed
    {
        return $this->json->getData();
    }

    /**
     * Set requested information for the response.
     */
    public function setData(mixed $data): static
    {
        $this->json->setData($data);

        return $this;
    }
}
