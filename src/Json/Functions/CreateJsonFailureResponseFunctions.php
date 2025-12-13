<?php

declare(strict_types=1);

namespace Jder\Psr\Json\Functions;

use Psr\Http\Message\ResponseInterface as Response;
use Jder\Psr\Json\Functions\Base\CreateJsonResponseFunctionBase;
use Jder\Psr\Json\JsonResponseError;

/**
 * @api
 */
class CreateJsonFailureResponseFunctions extends CreateJsonResponseFunctionBase
{
    public function __construct(Response $response)
    {
        parent::__construct($response);
        $this->status = 400;
        $this->json->setSuccess(false);
    }

    /**
     * A list of errors for the response when `success` is `false`.
     * @return array<JsonResponseError>
     */
    public function getErrors(): array
    {
        return $this->json->getErrors();
    }

    /**
     * Get the first error for the response when `success` is `false`.
     */
    public function getError(): ?JsonResponseError
    {
        return $this->json->getError();
    }

    /**
     * Add a list of errors to the response.
     * @param array<JsonResponseError> $errors
     */
    public function addErrors(array $errors): static
    {
        $this->json->addErrors($errors);

        return $this;
    }

    /**
     * Add an error to the response.
     */
    public function addError(JsonResponseError $error): static
    {
        $this->json->addError($error);

        return $this;
    }
}
