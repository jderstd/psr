<?php

declare(strict_types=1);

namespace Jder\Psr\Test;

use Jder\Psr\CreateResponse;
use Nyholm\Psr7\Response as MockResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as Response;

class TestResponse extends TestCase
{
    public function testDefault(): void
    {
        $body = "Hello, World!";

        $res = (new CreateResponse(new MockResponse()))
            ->setBody($body)
            ->create();

        static::assertInstanceOf(Response::class, $res);

        static::assertSame($body, (string) $res->getBody());
    }
}
