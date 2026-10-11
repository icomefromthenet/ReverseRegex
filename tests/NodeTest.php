<?php

declare(strict_types=1);

namespace ReverseRegex\Test;

use PHPUnit\Framework\TestCase;
use ReverseRegex\Generator\Node;

class NodeTest extends TestCase
{
    public function testDefaultLabel(): void
    {
        $this->assertSame('node', (new Node())->getLabel());
    }

    public function testSetLabelAcceptsScalarsAndNull(): void
    {
        $node = new Node();

        $node->setLabel('other');
        $this->assertSame('other', $node->getLabel());

        $node->setLabel(null);
        $this->assertNull($node->getLabel());
    }

    public function testSetLabelRejectsNonScalars(): void
    {
        $node = new Node('keep');

        $this->assertFalse($node->setLabel(['array']));
        $this->assertSame('keep', $node->getLabel());
    }

    public function testAttachContainsAndCount(): void
    {
        $parent = new Node('parent');
        $child = new Node('child');

        $this->assertCount(0, $parent);
        $this->assertFalse($parent->contains($child));

        $this->assertSame($parent, $parent->attach($child));

        $this->assertCount(1, $parent);
        $this->assertTrue($parent->contains($child));
    }

    public function testDetach(): void
    {
        $parent = new Node('parent');
        $child = new Node('child');
        $other = new Node('other');
        $parent->attach($child)->attach($other);

        $this->assertSame($parent, $parent->detach($child));

        $this->assertCount(1, $parent);
        $this->assertFalse($parent->contains($child));
        $this->assertTrue($parent->contains($other));
    }

    public function testMapVisitsEveryLinkedNode(): void
    {
        $parent = new Node('parent');
        $parent->attach(new Node('a'))->attach(new Node('b'));

        $labels = [];
        $parent->map(static function (Node $node) use (&$labels): void {
            $labels[] = $node->getLabel();
        });

        $this->assertSame(['a', 'b'], $labels);
    }

    public function testIteration(): void
    {
        $parent = new Node('parent');
        $parent->attach(new Node('a'))->attach(new Node('b'));

        $labels = [];
        foreach ($parent as $key => $child) {
            $labels[$key] = $child->getLabel();
        }

        $this->assertSame([0 => 'a', 1 => 'b'], $labels);
    }

    public function testArrayAccessStoresAttributes(): void
    {
        $node = new Node();

        $this->assertFalse(isset($node['foo']));

        $node['foo'] = 'bar';

        $this->assertTrue(isset($node['foo']));
        $this->assertSame('bar', $node['foo']);

        unset($node['foo']);

        $this->assertFalse(isset($node['foo']));
    }
}
