<?php

declare(strict_types=1);

namespace Jder\Psr\Json\Functions\Base;

use Psr\Http\Message\ResponseInterface as Response;
use Jder\Psr\Base\CreateResponseBase;
use Jder\Psr\Json\JsonResponse;
use Jder\Psr\Json\Options\CreateOptions;

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
     * Filter success field.
     */
    protected function filterSuccess(mixed $value): mixed
    {
        if (!is_iterable($value)) {
            return $value;
        }

        $result = [];

        foreach ($value as $key => $item) {
            if (!is_int($key) && !is_string($key)) {
                continue;
            }

            if ($key === "success") {
                continue;
            }

            $result[$key] = $item;
        }

        return $result;
    }

    /**
     * Recursively filter the json.
     */
    protected function filterJson(mixed $value): mixed
    {
        if (!is_iterable($value)) {
            return $value;
        }

        $result = [];

        foreach ($value as $key => $item) {
            if (!is_int($key) && !is_string($key)) {
                continue;
            }

            $item = $this->filterJson($item);

            // remove null value
            if ($item === null) {
                continue;
            }

            // remove empty array
            if (is_array($item) && $item === []) {
                continue;
            }

            $result[$key] = $item;
        }

        return $result;
    }

    /**
     * Finish the response creation.
     */
    public function create(
        CreateOptions $options = new CreateOptions(),
    ): Response {
        $this->response = $this->response->withHeader(
            "Content-Type",
            "application/json",
        );

        $json = $this->json;
        $json =
            $options->showSuccess === false
                ? $this->filterSuccess($json)
                : $json;
        $json = $options->verbose === false ? $this->filterJson($json) : $json;
        $json = json_encode($json);

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
