<?php

declare(strict_types=1);

namespace ReverseRegex\Test;

use PHPUnit\Framework\TestCase;
use ReverseRegex\Exception as RegexException;
use ReverseRegex\Generator\LiteralScope;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Random\MersenneRandom;

class ScopeTest extends TestCase
{
    public function testAttachDoesNotTriggerDeprecations(): void
    {
        $deprecations = [];
        set_error_handler(static function (int $errno, string $errstr) use (&$deprecations): bool {
            $deprecations[] = $errstr;

            return true;
        }, \E_DEPRECATED | \E_USER_DEPRECATED);

        try {
            $scope = new Scope('scope1');
            $scope->attach(new Scope('child'));
        } finally {
            restore_error_handler();
        }

        $this->assertSame([], $deprecations);
    }

    public function testScopeImplementsRepeatInterface(): void
    {
        $scope = new Scope('scope1');
        $this->assertInstanceOf(\ReverseRegex\Generator\RepeatInterface::class, $scope);
    }

    public function testScopeImplementsContextInterface(): void
    {
        $scope = new Scope('scope1');
        $this->assertInstanceOf(\ReverseRegex\Generator\ContextInterface::class, $scope);
    }

    public function testScopeExtendsNode(): void
    {
        $scope = new Scope('scope1');
        $this->assertInstanceOf(\ReverseRegex\Generator\Node::class, $scope);
    }

    public function testScopeImplementsAlternateInterface(): void
    {
        $scope = new Scope('scope1');
        $this->assertInstanceOf(\ReverseRegex\Generator\AlternateInterface::class, $scope);
    }

    public function testAlternateInterface(): void
    {
        $scope = new Scope('scope1');
        $this->assertFalse($scope->usingAlternatingStrategy());

        $scope->useAlternatingStrategy(true);
        $this->assertTrue($scope->usingAlternatingStrategy());
    }

    public function testRepeatInterface(): void
    {
        $scope = new Scope('scope1');

        $scope->setMaxOccurances(10);
        $scope->setMinOccurances(5);

        $this->assertEquals(10, $scope->getMaxOccurances());
        $this->assertEquals(5, $scope->getMinOccurances());
        $this->assertEquals(5, $scope->getOccuranceRange());
    }

    public function testAttachChild(): void
    {
        $scope = new Scope('scope1');
        $scope2 = new Scope('scope2');

        $scope->attach($scope2)->rewind();
        $this->assertEquals($scope2, $scope->current());
    }

    public function testRepeatQuota(): void
    {
        $gen = new MersenneRandom(703);

        $scope = new Scope('scope1');
        $scope->setMinOccurances(1);
        $scope->setMaxOccurances(6);

        $this->assertEquals(3, $scope->calculateRepeatQuota($gen));
    }

    public function testGenerateErrorNotChildren(): void
    {
        $gen = new MersenneRandom(700);

        $scope = new Scope('scope1');
        $scope->setMinOccurances(1);
        $scope->setMaxOccurances(6);

        $result = '';

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('No child scopes to call must be atleast 1');

        $scope->generate($result, $gen);
    }

    public function testGenerate(): void
    {
        $gen = new MersenneRandom(700);
        $result = '';

        $scope = new Scope('scope1');
        $scope->setMinOccurances(6);
        $scope->setMaxOccurances(6);

        $child = $this->getMockBuilder(\ReverseRegex\Generator\Scope::class)->onlyMethods(['generate'])->getMock();

        $child->expects($this->exactly(6))
            ->method('generate')
            ->with($this->callback('is_string'), $this->equalTo($gen))
            ->willReturnCallback(function (&$sResult) {
                return $sResult .= 'a';
            });

        $scope->attach($child);

        $result = $scope->generate($result, $gen);

        $this->assertEquals('aaaaaa', $result);
    }

    public function testGetNode(): void
    {
        $scope = new Scope('scope1');

        for ($i = 1; $i <= 6; ++$i) {
            $scope->attach(new Scope('label_' . $i));
        }

        $other_scope = $scope->get(6);
        $this->assertInstanceOf(\ReverseRegex\Generator\Scope::class, $other_scope);
        $this->assertEquals('label_6', $other_scope->getLabel());

        $other_scope = $scope->get(1);
        $this->assertInstanceOf(\ReverseRegex\Generator\Scope::class, $other_scope);
        $this->assertEquals('label_1', $other_scope->getLabel());

        $other_scope = $scope->get(3);
        $this->assertInstanceOf(\ReverseRegex\Generator\Scope::class, $other_scope);
        $this->assertEquals('label_3', $other_scope->getLabel());

        $other_scope = $scope->get(0);
        $this->assertNull($other_scope);
    }

    public function testGenerateWithAlternatingStrategy(): void
    {
        $scope = new Scope('scope1');
        $gen = new MersenneRandom(700);
        $result = '';

        $scope->setMinOccurances(7);
        $scope->setMaxOccurances(7);

        for ($i = 1; $i <= 6; ++$i) {
            $lit = new LiteralScope('label_' . $i);
            $lit->addLiteral($i);
            $scope->attach($lit);
            $lit = null;
        }

        $scope->useAlternatingStrategy();
        $scope->generate($result, $gen);
        $this->assertMatchesRegularExpression('/[1-6]{7}/', $result);
    }
}
// End of File
