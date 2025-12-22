<?php

declare(strict_types=1);

namespace Jder\Psr\Test;

use Jder\Psr\Json\CreateJsonResponse;
use Jder\Psr\Json\JsonResponse;
use Jder\Psr\Json\JsonResponseError;
use Nyholm\Psr7\Response as MockResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as Response;

class TestJsonResponse extends TestCase
{
    public function testSuccess(): void
    {
        $msg = "Hello, World!";

        $res = CreateJsonResponse::success(new MockResponse())
            ->setData([
                "message" => $msg,
            ])
            ->create();

        static::assertInstanceOf(Response::class, $res);

        $result = JsonResponse::fromObject(json_decode($res->getBody()->__toString()));

        static::assertTrue($result->getSuccess());

        static::assertSame($msg, $result->getData()->message);
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
            ->create();

        static::assertInstanceOf(Response::class, $res);

        $result = JsonResponse::fromObject(json_decode($res->getBody()->__toString()));

        static::assertFalse($result->getSuccess());

        $resultErr = $result->getError();

        static::assertSame($key, $resultErr->getCode());

        static::assertSame([$key], $resultErr->getPath());

        static::assertSame($key, $resultErr->getMessage());
    }
}
