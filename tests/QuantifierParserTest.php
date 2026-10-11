<?php

declare(strict_types=1);

namespace ReverseRegex\Test;

use PHPUnit\Framework\TestCase;
use ReverseRegex\Exception as RegexException;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;
use ReverseRegex\Parser\Quantifier;

class QuantifierParserTest extends TestCase
{
    public function testQuantifierParserPatternA(): void
    {
        $pattern = '{1,5}';
        $lexer = new Lexer($pattern);
        $scope = new Scope();
        $qual = new Quantifier();

        $lexer->moveNext();
        $qual->parse($scope, $scope, $lexer);

        $this->assertEquals(1, $scope->getMinOccurances());
        $this->assertEquals(5, $scope->getMaxOccurances());
    }

    public function testQuantiferSingleValue(): void
    {
        $pattern = '{5}';
        $lexer = new Lexer($pattern);
        $scope = new Scope();
        $qual = new Quantifier();

        $lexer->moveNext();
        $qual->parse($scope, $scope, $lexer);

        $this->assertEquals(5, $scope->getMinOccurances());
        $this->assertEquals(5, $scope->getMaxOccurances());
    }

    public function testQuantiferSpacesIncluded(): void
    {
        $pattern = '{ 1 , 5 }';
        $lexer = new Lexer($pattern);
        $scope = new Scope();
        $qual = new Quantifier();

        $lexer->moveNext();
        $qual->parse($scope, $scope, $lexer);

        $this->assertEquals(1, $scope->getMinOccurances());
        $this->assertEquals(5, $scope->getMaxOccurances());
    }

    public function testFailerAlphaCaracters(): void
    {
        $pattern = '{ 1 , 5a }';
        $lexer = new Lexer($pattern);
        $scope = new Scope();
        $qual = new Quantifier();

        $lexer->moveNext();

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Quantifier expects and integer compitable string');

        $qual->parse($scope, $scope, $lexer);
    }

    public function testFailerMissingMaximumCaracters(): void
    {
        $pattern = '{ 1 ,}';
        $lexer = new Lexer($pattern);
        $scope = new Scope();
        $qual = new Quantifier();

        $lexer->moveNext();

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Quantifier expects and integer compitable string');

        $qual->parse($scope, $scope, $lexer);
    }

    public function testFailerMissingMinimumCaracters(): void
    {
        $pattern = '{,1}';
        $lexer = new Lexer($pattern);
        $scope = new Scope();
        $qual = new Quantifier();

        $lexer->moveNext();

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Quantifier expects and integer compitable string');

        $qual->parse($scope, $scope, $lexer);
    }

    public function testMissingClosureCharacter(): void
    {
        $pattern = '{1,1';
        $lexer = new Lexer($pattern);
        $scope = new Scope();
        $qual = new Quantifier();

        $lexer->moveNext();

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Closing quantifier token `}` not found');

        $qual->parse($scope, $scope, $lexer);
    }

    public function testNestingQuantifiers(): void
    {
        $pattern = '{1,1{1,1}';
        $lexer = new Lexer($pattern);
        $scope = new Scope();
        $qual = new Quantifier();

        $lexer->moveNext();

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Nesting Quantifiers is not allowed');

        $qual->parse($scope, $scope, $lexer);
    }

    public function testStarQuantifier(): void
    {
        $pattern = 'az*';
        $lexer = new Lexer($pattern);
        $scope = new Scope();
        $qual = new Quantifier();

        $lexer->moveNext();
        $lexer->moveNext();
        $lexer->moveNext();

        $qual->parse($scope, $scope, $lexer);

        $this->assertEquals(0, $scope->getMinOccurances());
        $this->assertEquals(Quantifier::DEFAULT_UNBOUNDED_MAX, $scope->getMaxOccurances());
    }

    public function testCrossQuantifier(): void
    {
        $pattern = 'az+';
        $lexer = new Lexer($pattern);
        $scope = new Scope();
        $qual = new Quantifier();

        $lexer->moveNext();
        $lexer->moveNext();
        $lexer->moveNext();
        $qual->parse($scope, $scope, $lexer);

        $this->assertEquals(1, $scope->getMinOccurances());
        $this->assertEquals(Quantifier::DEFAULT_UNBOUNDED_MAX, $scope->getMaxOccurances());
    }

    public function testQuestionQuantifier(): void
    {
        $pattern = 'az?';
        $lexer = new Lexer($pattern);
        $scope = new Scope();
        $qual = new Quantifier();

        $lexer->moveNext();
        $lexer->moveNext();
        $lexer->moveNext();
        $qual->parse($scope, $scope, $lexer);

        $this->assertEquals(0, $scope->getMinOccurances());
        $this->assertEquals(1, $scope->getMaxOccurances());
    }

    public function testUnboundedMaxIsConfigurable(): void
    {
        $lexer = new Lexer('az*');
        $scope = new Scope();
        $qual = new Quantifier(7);

        $lexer->moveNext();
        $lexer->moveNext();
        $lexer->moveNext();

        $qual->parse($scope, $scope, $lexer);

        $this->assertEquals(7, $scope->getMaxOccurances());
    }

    public function testUnboundedMaxMustBePositive(): void
    {
        $this->expectException(RegexException::class);

        new Quantifier(0);
    }

    public function testParserLimitsGeneratedRepetitions(): void
    {
        $parser = new \ReverseRegex\Parser(new Lexer('a*'), new Scope(), new Scope());
        $parser->setUnboundedMax(3);
        $result = $parser->parse()->getResult();

        $generator = new \ReverseRegex\Random\SimpleRandom(42);

        for ($i = 0; $i < 50; ++$i) {
            $out = '';
            $result->generate($out, $generator);
            $this->assertLessThanOrEqual(3, \strlen($out));
        }
    }
}

// End of File
