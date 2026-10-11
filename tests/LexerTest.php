<?php

declare(strict_types=1);

namespace ReverseRegex\Test;

use Doctrine\Common\Lexer\AbstractLexer;
use PHPUnit\Framework\TestCase;
use ReverseRegex\Exception as RegexException;
use ReverseRegex\Lexer;
use ReverseRegex\TokenHelper;

class LexerTest extends TestCase
{
    public function testInheritsDoctrineLexer(): void
    {
        $lexer = new Lexer('[a-z]');
        $this->assertInstanceOf(AbstractLexer::class, $lexer);
    }

    public function testLexerPatternA(): void
    {
        $lexer = new Lexer('[a-z]');

        $lexer->moveNext();
        $this->assertEquals('[', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('a', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('-', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_RANGE, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('z', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(']', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_CLOSE, TokenHelper::type($lexer->lookahead));

        // $lexer->moveNext();
        // $this->assertEquals(null,TokenHelper::value($lexer->lookahead));
    }

    public function testLexerPatternB(): void
    {
        $lexer = new Lexer('\[a-z\]');

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('[', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('a', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('-', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('z', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();

        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(']', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        // $lexer->moveNext();
        // $this->assertEquals(null,TokenHelper::value($lexer->lookahead));
    }

    public function testLexerPatternC(): void
    {
        $lexer = new Lexer('[1-9]');

        $lexer->moveNext();
        $this->assertEquals('[', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('1', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('-', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_RANGE, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('9', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(']', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_CLOSE, TokenHelper::type($lexer->lookahead));

        // $lexer->moveNext();
        // $this->assertEquals(null,TokenHelper::value($lexer->lookahead));
    }

    public function testLexerPatternD(): void
    {
        $lexer = new Lexer('[1-9\x{56}]');

        $lexer->moveNext();
        $this->assertEquals('[', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('1', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('-', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_RANGE, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('9', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('x', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SHORT_X, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('{', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('5', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('6', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('}', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(']', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_CLOSE, TokenHelper::type($lexer->lookahead));

        // $lexer->moveNext();
        // $this->assertEquals(null,TokenHelper::value($lexer->lookahead));
    }

    public function testLexerPatternE(): void
    {
        $lexer = new Lexer('([^1-8\[]){0,9}*?+');

        $lexer->moveNext();
        $this->assertEquals('(', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_GROUP_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('[', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('^', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_NEGATED, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('1', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('-', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_RANGE, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('8', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('[', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(']', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_CLOSE, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(')', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_GROUP_CLOSE, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('{', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_QUANTIFIER_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('0', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(',', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('9', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('}', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_QUANTIFIER_CLOSE, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('*', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_QUANTIFIER_STAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('?', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_QUANTIFIER_QUESTION, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('+', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_QUANTIFIER_PLUS, TokenHelper::type($lexer->lookahead));

        // $lexer->moveNext();
        // $this->assertEquals(null,TokenHelper::value($lexer->lookahead));
    }

    public function testParrentShortCodes(): void
    {
        $lexer = new Lexer('\W');

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('W', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SHORT_NOT_W, TokenHelper::type($lexer->lookahead));

        $lexer = new Lexer('\w');

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('w', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SHORT_W, TokenHelper::type($lexer->lookahead));

        $lexer = new Lexer('\S');

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('S', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SHORT_NOT_S, TokenHelper::type($lexer->lookahead));

        $lexer = new Lexer('\s');

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('s', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SHORT_S, TokenHelper::type($lexer->lookahead));

        $lexer = new Lexer('\D');

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('D', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SHORT_NOT_D, TokenHelper::type($lexer->lookahead));

        $lexer = new Lexer('\d');

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('d', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SHORT_D, TokenHelper::type($lexer->lookahead));
    }

    public function testLexerPatternF(): void
    {
        $lexer = new Lexer('[\']');

        $lexer->moveNext();
        $this->assertEquals('[', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_OPEN, TokenHelper::type($lexer->lookahead));

        // in the above expression using php metasequence \' to escape a single quote
        // the reg only see the expression ['] and NOT [\']
        $lexer->moveNext();
        $this->assertEquals("'", TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(']', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_CLOSE, TokenHelper::type($lexer->lookahead));
    }

    public function testLexerEscapedBlackslash(): void
    {
        $lexer = new Lexer('\\\\');

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));
    }

    public function testLexerBrackets(): void
    {
        $lexer = new Lexer('[\p{}]');

        $lexer->moveNext();
        $this->assertEquals('[', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('p', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SHORT_P, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('{', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('}', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(']', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_CLOSE, TokenHelper::type($lexer->lookahead));

        $lexer = new Lexer('\p{}');

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('p', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SHORT_P, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('{', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_QUANTIFIER_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('}', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_QUANTIFIER_CLOSE, TokenHelper::type($lexer->lookahead));
    }

    public function testAlternation(): void
    {
        $lexer = new Lexer('A|a');

        $lexer->moveNext();
        $this->assertEquals('A', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('|', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_CHOICE_BAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('a', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        // no alternation in char classes

        $lexer = new Lexer('[A|a]');

        $lexer->moveNext();
        $this->assertEquals('[', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('A', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('|', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('a', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(']', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_CLOSE, TokenHelper::type($lexer->lookahead));
    }

    public function testDotCharacter(): void
    {
        $lexer = new Lexer('.');

        $lexer->moveNext();
        $this->assertEquals('.', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_DOT, TokenHelper::type($lexer->lookahead));

        $lexer = new Lexer('\.');

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('.', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        // normal char in a char class
        $lexer = new Lexer('[.]');

        $lexer->moveNext();
        $this->assertEquals('[', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('.', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(']', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_CLOSE, TokenHelper::type($lexer->lookahead));
    }

    public function testLexerPatternG(): void
    {
        $lexer = new Lexer('abcd&\*\(\)');

        $lexer->moveNext();
        $this->assertEquals('a', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('b', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('c', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('d', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('&', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('*', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('(', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(')', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));
    }

    public function testUnicodePropertyinCharacterClass(): void
    {
        $lexer = new Lexer('[np\p{L}]');

        $lexer->moveNext();
        $this->assertEquals('[', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('n', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('p', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('p', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SHORT_P, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('{', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('L', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('}', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(']', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_CLOSE, TokenHelper::type($lexer->lookahead));
    }

    public function testUnicodeReferencePropertyinCharacterClass(): void
    {
        $lexer = new Lexer('[no\X{00FF}]');

        $lexer->moveNext();
        $this->assertEquals('[', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('n', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('o', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('X', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SHORT_UNICODE_X, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('{', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('0', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('0', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('F', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('F', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('}', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(']', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_CLOSE, TokenHelper::type($lexer->lookahead));
    }

    public function testCarretAndDollar(): void
    {
        $lexer = new Lexer('^$');

        $lexer->moveNext();
        $this->assertEquals('^', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_START_CARET, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('$', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_END_DOLLAR, TokenHelper::type($lexer->lookahead));

        $lexer = new Lexer('[\^$]');

        $lexer->moveNext();
        $this->assertEquals('[', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('\\', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('^', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('$', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_LITERAL_CHAR, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(']', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_SET_CLOSE, TokenHelper::type($lexer->lookahead));
    }

    public function testLexerPatternHGroupNesting(): void
    {
        $lexer = new Lexer('(())');

        $lexer->moveNext();
        $this->assertEquals('(', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_GROUP_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals('(', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_GROUP_OPEN, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(')', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_GROUP_CLOSE, TokenHelper::type($lexer->lookahead));

        $lexer->moveNext();
        $this->assertEquals(')', TokenHelper::value($lexer->lookahead));
        $this->assertEquals(Lexer::T_GROUP_CLOSE, TokenHelper::type($lexer->lookahead));
    }

    public function testGroupNestingErrorStillOpen(): void
    {
        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Opening group char "(" has no matching closing character');

        $lexer = new Lexer('(()');
    }

    public function testGroupNestingErrorClosedNotOpened(): void
    {
        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Closing group char "(" has no matching opening character');

        $lexer = new Lexer('())');
    }

    public function testCharSetNestingError(): void
    {
        $this->expectException(RegexException::class);
        $this->expectExceptionMessage("Can't have a second character class while first remains open");

        $lexer = new Lexer('[[]]');
    }

    public function testCharSetOpenError(): void
    {
        $this->expectException(RegexException::class);
        $this->expectExceptionMessage("Can't close a character class while none is open");

        $lexer = new Lexer(']');
    }

    public function testCharSetOpenNotClosed(): void
    {
        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Character Class that been closed');

        $lexer = new Lexer('[');
    }
}
// End of File
