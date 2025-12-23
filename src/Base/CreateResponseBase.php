<?php

declare(strict_types=1);

namespace Jder\Psr\Base;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Base class to create response.
 */
class CreateResponseBase
{
    protected int $status;

    /** @var array<array<string>> */
    protected array $headers;

    protected Response $response;

    public function __construct(Response $response)
    {
        $this->status = 200;
        $this->headers = [];
        $this->response = $response;
    }

    /**
     * Get response status code.
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Set response status code.
     */
    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get response headers.
     *
     * @return array<array<string>>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get specific response header.
     *
     * @return string[]
     */
    public function getHeader(string $name): array
    {
        return $this->headers[$name] ?? [];
    }

    /**
     * Set response headers.
     *
     * @param array<string,string|string[]> $headers
     */
    public function addHeaders(array $headers): static
    {
        foreach ($headers as $name => $values) {
            $this->headers[$name] = array_merge(
                $this->headers[$name] ?? [],
                (array) $values,
            );
        }

        return $this;
    }

    /**
     * Set response header.
     *
     * @param string $name
     * @param string|string[] $value
     */
    public function addHeader(string $name, mixed $value): static
    {
        $this->addHeaders([
            $name => $value,
        ]);

        return $this;
    }
}
