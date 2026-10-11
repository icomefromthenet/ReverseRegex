<?php

declare(strict_types=1);

namespace ReverseRegex\Test;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use ReverseRegex\TokenHelper;

class TokenHelperTest extends TestCase
{
    public function testReadsArrayTokens(): void
    {
        $token = ['value' => 'a', 'type' => 3, 'position' => 7];

        $this->assertSame('a', TokenHelper::value($token));
        $this->assertSame(3, TokenHelper::type($token));
        $this->assertSame(7, TokenHelper::position($token));
    }

    public function testReadsArrayAccessTokens(): void
    {
        $token = new ArrayObject(['value' => 'b', 'type' => 4, 'position' => 1]);

        $this->assertSame('b', TokenHelper::value($token));
        $this->assertSame(4, TokenHelper::type($token));
        $this->assertSame(1, TokenHelper::position($token));
    }

    public function testReadsObjectTokens(): void
    {
        $token = (object) ['value' => 'c', 'type' => 5, 'position' => 2];

        $this->assertSame('c', TokenHelper::value($token));
        $this->assertSame(5, TokenHelper::type($token));
        $this->assertSame(2, TokenHelper::position($token));
    }

    public function testNullTokenYieldsNull(): void
    {
        $this->assertNull(TokenHelper::value(null));
        $this->assertNull(TokenHelper::type(null));
        $this->assertNull(TokenHelper::position(null));
    }
}
