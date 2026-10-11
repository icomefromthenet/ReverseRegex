<?php

declare(strict_types=1);

namespace ReverseRegex\Test;

use PHPUnit\Framework\TestCase;
use ReverseRegex\Exception as RegexException;
use ReverseRegex\Generator\LiteralScope;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;
use ReverseRegex\Parser\CharacterClass;

class CharacterClassTest extends TestCase
{
    public function testNormalizeNoUnicode(): void
    {
        $lexer = new Lexer('[a-mnop]');
        $scope = new Scope();
        $parser = new CharacterClass();

        $lexer->moveNext();
        $result = $parser->normalize($scope, $scope, $lexer);

        $this->assertEquals('[a-mnop]', $result);
    }

    public function testNormalizeWithUnicodeValue(): void
    {
        $lexer = new Lexer('[\X{00ff}nop]');
        $scope = new Scope();
        $parser = new CharacterClass();

        $lexer->moveNext();
        $result = $parser->normalize($scope, $scope, $lexer);

        $this->assertEquals('[\\ÿnop]', $result);
    }

    public function testNormalizeWithUnicodeRange(): void
    {
        $lexer = new Lexer('[\X{00FF}-\X{00FF}mnop]');
        $scope = new Scope();
        $parser = new CharacterClass();

        $lexer->moveNext();
        $result = $parser->normalize($scope, $scope, $lexer);

        $this->assertEquals('[\\ÿ-\\ÿmnop]', $result);
    }

    public function testFillRangeAscii(): void
    {
        $start = '!';
        $end = '&';
        $range = '!"#$%&';
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $parser->fillRange($scope, $start, $end);

        $this->assertEquals($range, implode('', $scope->getLiterals()->toArray()));
    }

    public function testFillRangeUnicode(): void
    {
        $start = 'Ꭰ';
        $end = 'Ꭵ';
        $range = 'ᎠᎡᎢᎣᎤᎥ';
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $parser->fillRange($scope, $start, $end);

        $this->assertEquals($range, implode('', $scope->getLiterals()->toArray()));
    }

    public function testFillRangeOutofOrder(): void
    {
        $start = 'z';
        $end = 'a';
        $scope = new LiteralScope();
        $parser = new CharacterClass();

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Character class range z - a is out of order');

        $parser->fillRange($scope, $start, $end);
    }

    public function testParseNoRanges(): void
    {
        $lexer = new Lexer('[amnop]');
        $scope = new Scope();
        $head = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext();
        $parser->parse($head, $scope, $lexer);

        $values = $head->getLiterals()->toArray();

        $this->assertEquals(['a', 'm', 'n', 'o', 'p'], array_values($values));
    }

    public function testParseNoUnicodeShorts(): void
    {
        $lexer = new Lexer('[a-k]');
        $scope = new Scope();
        $head = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext();
        $parser->parse($head, $scope, $lexer);

        $values = $head->getLiterals()->toArray();

        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k'], array_values($values));
    }

    public function testParseNoUnicodeShortsMultiRange(): void
    {
        $lexer = new Lexer('[a-k-n]');
        $scope = new Scope();
        $head = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext();
        $parser->parse($head, $scope, $lexer);

        $values = $head->getLiterals()->toArray();

        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n'], array_values($values));
    }

    public function testParseUnicodeShort(): void
    {
        $lexer = new Lexer('[\X{0061}-\X{006B}]');
        $scope = new Scope();
        $head = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext();
        $parser->parse($head, $scope, $lexer);

        $values = $head->getLiterals()->toArray();

        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k'], array_values($values));
    }

    public function testParseHexShort(): void
    {
        $lexer = new Lexer('[\x61-\x6B]');
        $scope = new Scope();
        $head = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext();
        $parser->parse($head, $scope, $lexer);

        $values = $head->getLiterals()->toArray();

        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k'], array_values($values));
    }

    public function testParseHexShortMultirange(): void
    {
        $lexer = new Lexer('[z\x61-\x6B-\x6E]');
        $scope = new Scope();
        $head = new LiteralScope();
        $parser = new CharacterClass();

        $lexer->moveNext();
        $parser->parse($head, $scope, $lexer);

        $values = $head->getLiterals()->getValues();
        $this->assertEquals(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'z'], $values);
    }

    public function testParseHexShortBraceError(): void
    {
        $lexer = new Lexer('[\x{61}-\x6B-\x6E]');
        $scope = new Scope();
        $head = new LiteralScope();
        $parser = new CharacterClass();

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Braces not supported here');

        $lexer->moveNext();
        $parser->parse($head, $scope, $lexer);
    }
}
// End of File
