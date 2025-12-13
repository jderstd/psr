<?php

declare(strict_types=1);

namespace Jder\Psr\Json;

use InvalidArgumentException;
use Jder\Psr\Json\JsonResponseError;

/**
 * JSON response.
 * @api
 */
class JsonResponse
{
    public bool $success;

    public mixed $data;

    /** @var array<JsonResponseError> */
    public array $errors;

    public function __construct()
    {
        $this->success = true;
        $this->data = null;
        $this->errors = [];
    }

    /**
     * Indicates whether the response is successful or not.
     */
    public function getSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Set if the response is successful or not.
     */
    public function setSuccess(bool $success): static
    {
        $this->success = $success;

        return $this;
    }

    /**
     * Requested information for the response when `success` is `true`.
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Set requested information for the response.
     */
    public function setData(mixed $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * A list of errors for the response when `success` is `false`.
     * @return array<JsonResponseError>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get the first error for the response when `success` is `false`.
     */
    public function getError(): ?JsonResponseError
    {
        return $this->errors[0] ?? null;
    }

    /**
     * Add a list of errors to the response.
     * @param array<JsonResponseError> $errors
     */
    public function addErrors(array $errors): static
    {
        array_push($this->errors, ...$errors);

        return $this;
    }

    /**
     * Add an error to the response.
     */
    public function addError(JsonResponseError $error): static
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * Create the class from a JSON object.
     */
    public function fromJsonObject(mixed $json): static
    {
        if (!is_object($json)) {
            throw new InvalidArgumentException(
                "Expected JSON object, received " . gettype($json),
            );
        }

        if (!isset($json->success)) {
            throw new InvalidArgumentException(
                "Missing `success` key in JSON object",
            );
        }

        $this->success = (bool) $json->success;

        $this->data = $json->data ?? null;

        $this->errors = [];

        if (!empty($json->errors) && is_array($json->errors)) {
            foreach ($json->errors as $errorJson) {
                $error = new JsonResponseError();
                $error->fromJsonObject($errorJson);
                $this->errors[] = $error;
            }
        }

        return $this;
    }

    /**
     * Turn the class into a JSON object.
     * @return array<string,mixed>
     */
    public function toJsonObject(): array
    {
        $errs = [];

        foreach ($this->errors as $error) {
            $errs[] = $error->toJsonObject();
        }

        $data = [
            "success" => $this->success,
            "data" => $this->data,
            "errors" => $errs,
        ];

        return array_filter(
            $data,
            static fn($value) => $value !== null && $value !== [],
        );
    }
}
