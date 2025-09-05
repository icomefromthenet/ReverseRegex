<?php

declare(strict_types=1);

namespace ReverseRegex\Tests;

use ReverseRegex\Exception as RegexException;
use ReverseRegex\Lexer;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Generator\LiteralScope;
use ReverseRegex\Parser\CharacterClass;

class CharacterClassTest extends Basic
{
    // Faithfully adapted from original testNormalizeNoUnicode
    public function testParseWithCharacterRangeAndLiterals(): void
    {
        $lexer = new Lexer('[a-mnop]'); // Input is identical to original test
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext(); // Consume `[`
        $parser->parse($scope, new Scope(), $lexer);
        $values = $scope->getLiterals()->getValues();

        // Asserts the correct final interpretation of "a-m", "n", "o", "p"
        $this->assertEquals(['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p'], $values);
    }

    // Faithfully adapted from original testNormalizeWithUnicodeValue
    public function testParseWithSingleUnicodeValueAndLiterals(): void
    {
        $lexer = new Lexer('[\X{00ff}nop]'); // Input is identical to original test
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext(); // Consume `[`
        $parser->parse($scope, new Scope(), $lexer);
        $values = $scope->getLiterals()->getValues();

        // Asserts the correct final interpretation of "ÿ", "n", "o", "p"
        $this->assertEquals(['n','o','p','ÿ'], $values);
    }

    // Faithfully adapted from original testNormalizeWithUnicodeRange
    public function testParseWithUnicodeRangeAndLiterals(): void
    {
        $lexer = new Lexer('[\X{00FF}-\X{00FF}mnop]'); // Input is identical to original test
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext(); // Consume `[`
        $parser->parse($scope, new Scope(), $lexer);
        $values = $scope->getLiterals()->getValues();

        // Asserts correct interpretation of range "ÿ-ÿ" and literals "m,n,o,p"
        $this->assertEquals(['m','n','o','p','ÿ'], $values);
    }

    public function testFillRangeAscii(): void
    {
        $start = '!';
        $end   = '&';
        $range = '!"#$%&';
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $parser->fillRange($scope, $start, $end);

        $this->assertEquals($range, implode('', $scope->getLiterals()->toArray()));
    }

    public function testFillRangeUnicode(): void
    {
        $start = 'Ꭰ';
        $end   = 'Ꭵ';
        $range = 'ᎠᎡᎢᎣᎤᎥ';
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $parser->fillRange($scope, $start, $end);

        $this->assertEquals($range, implode('', $scope->getLiterals()->toArray()));
    }

    public function testFillRangeOutOfOrder(): void
    {
        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Character class range z - a is out of order');

        $start = 'z';
        $end   = 'a';
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $parser->fillRange($scope, $start, $end);
    }

    public function testParseNoRanges(): void
    {
        $lexer = new Lexer('[amnop]');
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext(); // Consume `[`
        $parser->parse($scope, new Scope(), $lexer);
        $values = $scope->getLiterals()->getValues();

        $this->assertEquals(['a', 'm', 'n', 'o', 'p'], $values);
    }

    public function testParseSimpleRange(): void
    {
        $lexer = new Lexer('[a-k]');
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext(); // Consume `[`
        $parser->parse($scope, new Scope(), $lexer);
        $values = $scope->getLiterals()->getValues();

        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k'], $values);
    }

    public function testParseMultiRange(): void
    {
        $lexer = new Lexer('[a-k-n]');
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext(); // Consume `[`
        $parser->parse($scope, new Scope(), $lexer);
        $values = $scope->getLiterals()->getValues();

        // This assertion now matches the original test's expectation for [a-k-n]
        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n'], $values);
    }

    public function testParseUnicodeShortRange(): void
    {
        $lexer = new Lexer('[\X{0061}-\X{006B}]');
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext(); // Consume `[`
        $parser->parse($scope, new Scope(), $lexer);
        $values = $scope->getLiterals()->getValues();

        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k'], $values);
    }

    public function testParseHexShortRange(): void
    {
        $lexer = new Lexer('[\x61-\x6B]');
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext(); // Consume `[`
        $parser->parse($scope, new Scope(), $lexer);
        $values = $scope->getLiterals()->getValues();

        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k'], $values);
    }

    public function testParseMixedLiteralAndRanges(): void
    {
        $lexer = new Lexer('[z\x61-\x6B-\x6E]');
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext(); // Consume `[`
        $parser->parse($scope, new Scope(), $lexer);
        $values = $scope->getLiterals()->getValues();

        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'z'], $values);
    }

    public function testParseHexShortBraceError(): void
    {
        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Braces are not supported for `\x`, use `\X{...}` instead');

        $lexer = new Lexer('[\x{61}]');
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext(); // Consume `[`
        $parser->parse($scope, new Scope(), $lexer);
    }
}
