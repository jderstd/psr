<?php

declare(strict_types=1);

namespace Jder\Psr;

use Jder\Psr\Base\CreateResponseBase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

/**
 * Function to create response.
 */
class CreateResponse extends CreateResponseBase
{
    /** @var string|Stream */
    protected mixed $body;

    public function __construct(Response $response)
    {
        parent::__construct($response);
    }

    /**
     * Set response body.
     *
     * @param string|Stream $body
     */
    public function setBody(mixed $body): static
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get response body.
     *
     * @return string|Stream
     */
    public function getBody(): mixed
    {
        return $this->body;
    }

    /**
     * Finish the response creation.
     */
    public function create(): Response
    {
        // check if stream
        if (is_string($this->body)) {
            $this->response->getBody()->write($this->body);
        } else {
            $this->response = $this->response->withBody($this->body);
        }

        return $this->response;
    }
}
