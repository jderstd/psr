<?php

declare(strict_types=1);

namespace Jder\Psr\Test;

use PHPUnit\Framework\TestCase;
use Nyholm\Psr7\Response as MockResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Jder\Psr\CreateResponse;

class TestResponse extends TestCase
{
    public function testDefault(): void
    {
        $body = "Hello, World!";

        $res = (new CreateResponse(new MockResponse()))
            ->setBody($body)
            ->create();

        $this->assertInstanceOf(Response::class, $res);

        $this->assertSame($body, (string) $res->getBody());
    }
}
