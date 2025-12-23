<?php

declare(strict_types=1);

namespace Jder\Psr\Json;

use InvalidArgumentException;
use Jder\Psr\Json\JsonResponseError;
use JsonSerializable;
use Override;

/**
 * JSON response.
 */
class JsonResponse implements JsonSerializable
{
    protected bool $success;

    protected mixed $data;

    /** @var array<JsonResponseError> */
    protected array $errors;

    final public function __construct()
    {
        $this->success = true;
        $this->data = null;
        $this->errors = [];
    }

    /**
     * Create the class from an object.
     */
    public static function fromObject(mixed $json): static
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

        $success = (bool) $json->success;

        $data = $json->data ?? null;

        /** @var array<JsonResponseError> */
        $errors = [];

        if (!empty($json->errors) && is_array($json->errors)) {
            foreach ($json->errors as $errorJson) {
                $error = JsonResponseError::fromObject($errorJson);
                $errors[] = $error;
            }
        }

        return (new static())
            ->setSuccess($success)
            ->setData($data)
            ->addErrors($errors);
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
     *
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
     * Set the list of errors for the response.
     *
     * This function will overwrite any existing errors.
     *
     * @param array<JsonResponseError> $errors
     */
    public function setErrors(array $errors): static
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Add a list of errors to the response.
     *
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
     * Serialize the class to JSON.
     */
    #[Override]
    public function jsonSerialize(): mixed
    {
        return [
            "success" => $this->success,
            "data" => $this->data,
            "errors" => $this->errors,
        ];
    }
}
