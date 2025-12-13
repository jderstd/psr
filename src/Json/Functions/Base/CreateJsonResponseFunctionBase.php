<?php

declare(strict_types=1);

namespace Jder\Psr\Json\Functions\Base;

use Psr\Http\Message\ResponseInterface as Response;
use Jder\Psr\Base\CreateResponseBase;
use Jder\Psr\Json\JsonResponse;

const FAILURE_RESPONSE_DEFAULT = "{\"success\":false,\"data\":null,\"errors\":[{\"code\":\"server\",\"path\":[],\"message\":\"Internal server error.\"}]}";

/**
 * @api
 */
class CreateJsonResponseFunctionBase extends CreateResponseBase
{
    protected JsonResponse $json;

    public function __construct(Response $response)
    {
        parent::__construct($response);
        $this->json = new JsonResponse();
    }

    /**
     * Finish the response creation.
     */
    public function create(): Response
    {
        $this->response = $this->response->withHeader(
            "Content-Type",
            "application/json",
        );

        $json = json_encode($this->json->toJsonObject());

        if (!$json) {
            $this->response = $this->response->withStatus(500);

            $this->response->getBody()->write(FAILURE_RESPONSE_DEFAULT);

            return $this->response;
        }

        $this->response = $this->response->withStatus($this->status);

        foreach ($this->headers as $name => $values) {
            foreach ($values as $value) {
                $this->response = $this->response->withHeader($name, $value);
            }
        }

        $this->response->getBody()->write($json);

        return $this->response;
    }
}
