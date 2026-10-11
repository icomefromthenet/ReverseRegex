<?php

declare(strict_types=1);

namespace ReverseRegex\Test;

use PHPUnit\Framework\TestCase;
use ReverseRegex\ArrayCollection;

class ArrayCollectionTest extends TestCase
{
    public function testGetAtIsOneBased(): void
    {
        $collection = new ArrayCollection(['a', 'b', 'c']);

        $this->assertSame('a', $collection->getAt(1));
        $this->assertSame('b', $collection->getAt(2));
        $this->assertSame('c', $collection->getAt(3));
    }

    public function testGetAtReturnsNullOutOfRange(): void
    {
        $collection = new ArrayCollection(['a', 'b', 'c']);

        $this->assertNull($collection->getAt(0));
        $this->assertNull($collection->getAt(-1));
        $this->assertNull($collection->getAt(4));
        $this->assertNull((new ArrayCollection())->getAt(1));
    }
}
