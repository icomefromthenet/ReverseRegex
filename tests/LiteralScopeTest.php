<?php

declare(strict_types=1);

namespace ReverseRegex\Test;

use PHPUnit\Framework\TestCase;
use ReverseRegex\Generator\LiteralScope;

class LiteralScopeTest extends TestCase
{
    public function testExtendsScope(): void
    {
        $literal = new LiteralScope('scope1');
        $this->assertInstanceOf(\ReverseRegex\Generator\Scope::class, $literal);
    }

    public function testAddLiteral(): void
    {
        $literal = new LiteralScope('scope1');

        $literal->addLiteral('a');
        $literal->addLiteral('b');
        $literal->addLiteral('c');

        $collection = $literal->getLiterals();

        $this->assertInstanceOf(\Doctrine\Common\Collections\ArrayCollection::class, $collection);

        $this->assertEquals('a', $collection->get(0));
        $this->assertEquals('b', $collection->get(1));
        $this->assertEquals('c', $collection->get(2));
    }

    public function testGenerateNoRepeats(): void
    {
        $literal = new LiteralScope('scope1');
        $literal->addLiteral('a');
        $literal->setMinOccurances(1);
        $literal->setMaxOccurances(1);

        $generator_mock = $this->createMock(\ReverseRegex\Random\GeneratorInterface::class, ['generate', 'seed', 'max']);

        $generator_mock->expects($this->exactly(0))
            ->method('generate');

        $result = '';
        $literal->generate($result, $generator_mock);

        $this->assertEquals('a', $result);
    }

    public function testGenerateRepeatsTwice(): void
    {
        $literal = new LiteralScope('scope1');
        $literal->addLiteral('a');
        $literal->addLiteral('b');
        $literal->setMinOccurances(2);
        $literal->setMaxOccurances(2);

        $generator_mock = $this->createMock(\ReverseRegex\Random\GeneratorInterface::class, ['generate', 'seed', 'max']);

        $generator_mock->expects($this->exactly(2))
            ->method('generate')
            ->with($this->equalTo(1), $this->equalTo(2))
            ->willReturn(1);

        $result = '';

        $literal->generate($result, $generator_mock);

        $this->assertEquals('aa', $result);
    }

    public function testGenerateWithSmallRange(): void
    {
        $literal = new LiteralScope('scope1');
        $literal->addLiteral('a');
        $literal->setMinOccurances(1);
        $literal->setMaxOccurances(2);

        $gen = new \ReverseRegex\Random\SrandRandom(0);

        $result = '';
        $literal->generate($result, $gen);

        $this->assertLessThanOrEqual(2, \strlen($result));
        $this->assertGreaterThanOrEqual(1, \strlen($result));
    }

    public function testGenerateWithMulipleLiterals(): void
    {
        $literal = new LiteralScope('scope1');
        $literal->addLiteral('a');
        $literal->addLiteral('b');
        $literal->addLiteral('c');
        $literal->addLiteral('d');

        $literal->setMinOccurances(1);
        $literal->setMaxOccurances(4);

        $gen = new \ReverseRegex\Random\SrandRandom(0);

        $result = '';
        $literal->generate($result, $gen);
        $this->assertLessThanOrEqual(4, \strlen($result));
        $this->assertGreaterThanOrEqual(1, \strlen($result));
    }

    public function testSetLiteral(): void
    {
        $literal = new LiteralScope('scope1');
        $literal->setLiteral('0001', 'a');
        $literal->setLiteral('0002', 'b');
        $literal->setLiteral('0003', 'c');
        $literal->setLiteral('0004', 'd');

        $result = $literal->getLiterals()->toArray();

        $this->assertArrayHasKey('0001', $result);
        $this->assertArrayHasKey('0002', $result);
        $this->assertArrayHasKey('0003', $result);
        $this->assertArrayHasKey('0004', $result);
    }

    public function testGenerateWithoutLiteralsFails(): void
    {
        $literal = new LiteralScope('scope1');
        $result = '';

        $this->expectException(\ReverseRegex\Exception::class);
        $literal->generate($result, new \ReverseRegex\Random\SimpleRandom(1));
    }

    public function testGenerateAcceptsNullInitialResult(): void
    {
        $literal = new LiteralScope('scope1');
        $literal->addLiteral('a');

        $result = null;
        $returned = $literal->generate($result, new \ReverseRegex\Random\SimpleRandom(1));

        $this->assertSame('a', $result);
        $this->assertSame('a', $returned);
    }
}
