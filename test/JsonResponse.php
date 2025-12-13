<?php

declare(strict_types=1);

namespace Jder\Psr\Test;

use PHPUnit\Framework\TestCase;
use Nyholm\Psr7\Response as MockResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Jder\Psr\Json\CreateJsonResponse;
use Jder\Psr\Json\JsonResponse;
use Jder\Psr\Json\JsonResponseError;

class TestJsonResponse extends TestCase
{
    public function testSuccess(): void
    {
        $msg = "Hello, World!";

        $res = CreateJsonResponse::success(new MockResponse())
            ->setData([
                "message" => $msg,
            ])
            ->toResponse();

        $this->assertInstanceOf(Response::class, $res);

        $result = new JsonResponse();
        $result->fromJsonObject(json_decode($res->getBody()->__toString()));

        $this->assertSame(true, $result->getSuccess());

        $this->assertSame($msg, $result->getData()->message);
    }

    public function testFailure(): void
    {
        $key = "test";

        $err = (new JsonResponseError())
            ->setCode($key)
            ->setPath([$key])
            ->setMessage($key);

        $res = CreateJsonResponse::failure(new MockResponse())
            ->addError($err)
            ->toResponse();

        $this->assertInstanceOf(Response::class, $res);

        $result = new JsonResponse();
        $result->fromJsonObject(json_decode($res->getBody()->__toString()));

        $this->assertSame(false, $result->success);

        $resultErr = $result->getError();

        $this->assertSame($key, $resultErr->getCode());

        $this->assertSame([$key], $resultErr->getPath());

        $this->assertSame($key, $resultErr->getMessage());
    }
}
