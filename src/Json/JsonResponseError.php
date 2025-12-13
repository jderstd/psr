<?php

declare(strict_types=1);

namespace Jder\Psr\Json;

use InvalidArgumentException;
use Jder\Psr\Json\ResponseErrorCode;

/**
 * JSON response error.
 * @api
 */
class JsonResponseError
{
    public string $code;

    /** @var list<string> */
    public array $path;

    public ?string $message;

    public function __construct()
    {
        $this->code = ResponseErrorCode::Unknown->value;
        $this->path = [];
        $this->message = null;
    }

    /**
     * Code representing the error.
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set the error code.
     */
    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Indicates where the error occurred.
     * @return array<string>
     */
    public function getPath(): ?array
    {
        return $this->path;
    }

    /**
     * Set where the error occurred.
     * @param list<string> $path
     */
    public function setPath(array $path): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Detail of the error.
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Set the error detail.
     */
    public function setMessage(?string $message): static
    {
        $this->message = $message;

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

        if (!isset($json->code)) {
            throw new InvalidArgumentException(
                "Missing `code` key in JSON object",
            );
        }

        if (!is_string($json->code)) {
            throw new InvalidArgumentException(
                "Expected `code` to be string, received " .
                    gettype($json->code),
            );
        }

        $this->code = $json->code;

        if (isset($json->path)) {
            if (!is_array($json->path)) {
                throw new InvalidArgumentException(
                    "Expected `path` to be array, received " .
                        gettype($json->path),
                );
            }

            foreach ($json->path as $value) {
                if (!is_string($value)) {
                    throw new InvalidArgumentException(
                        "Expected `path` to be array of strings, received " .
                            gettype($value),
                    );
                }

                $this->path[] = $value;
            }
        } else {
            $this->path = [];
        }

        if (isset($json->message)) {
            if (!is_string($json->message)) {
                throw new InvalidArgumentException(
                    "Expected `message` to be string, received " .
                        gettype($json->message),
                );
            }

            $this->message = $json->message;
        } else {
            $this->message = null;
        }

        return $this;
    }

    /**
     * Turn the class into a JSON object.
     * @return array<string,mixed>
     */
    public function toJsonObject(): array
    {
        $data = [
            "code" => $this->code,
            "path" => $this->path,
            "message" => $this->message,
        ];

        return array_filter(
            $data,
            static fn($value) => $value !== null && $value !== [],
        );
    }
}
