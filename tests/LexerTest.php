<?php

declare(strict_types=1);

namespace ReverseRegex\Tests;

use Doctrine\Common\Lexer\AbstractLexer;
use ReverseRegex\Exception as RegexException;
use ReverseRegex\Lexer;

class LexerTest extends Basic
{
    public function testInheritsDoctrineLexer(): void
    {
        $lexer = new Lexer('[a-z]');
        $this->assertInstanceOf(AbstractLexer::class, $lexer);
    }

    public function testLexerPatternA(): void
    {
        $lexer = new Lexer('[a-z]');
        $this->assertEquals('[', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('a', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('-', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_RANGE, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('z', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(']', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_CLOSE, $lexer->lookahead->type);
    }


    public function testLexerPatternB(): void
    {
        $lexer = new Lexer('\[a-z\]');

        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('[', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('a', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('-', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('z', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(']', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);
    }

    public function testLexerPatternC(): void
    {
        $lexer = new Lexer('[1-9]');

        $this->assertEquals('[', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('1', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('-', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_RANGE, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('9', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(']', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_CLOSE, $lexer->lookahead->type);
    }

    public function testLexerPatternD(): void
    {
        $lexer = new Lexer('[1-9\x{56}]');

        $this->assertEquals('[', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('1', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('-', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_RANGE, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('9', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('x', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SHORT_X, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('{', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('5', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('6', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('}', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(']', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_CLOSE, $lexer->lookahead->type);
    }


    public function testLexerPatternE(): void
    {
        $lexer = new Lexer('([^1-8\[]){0,9}*?+');

        $this->assertEquals('(', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_GROUP_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('[', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('^', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_NEGATED, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('1', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('-', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_RANGE, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('8', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('[', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(']', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_CLOSE, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(')', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_GROUP_CLOSE, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('{', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_QUANTIFIER_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('0', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(',', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('9', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('}', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_QUANTIFIER_CLOSE, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('*', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_QUANTIFIER_STAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('?', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_QUANTIFIER_QUESTION, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('+', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_QUANTIFIER_PLUS, $lexer->lookahead->type);
    }

    public function testParrentShortCodes(): void
    {
        $lexer = new Lexer('\W');
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);
        $lexer->moveNext();
        $this->assertEquals('W', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SHORT_NOT_W, $lexer->lookahead->type);

        $lexer = new Lexer('\w');
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);
        $lexer->moveNext();
        $this->assertEquals('w', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SHORT_W, $lexer->lookahead->type);

        $lexer = new Lexer('\S');
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);
        $lexer->moveNext();
        $this->assertEquals('S', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SHORT_NOT_S, $lexer->lookahead->type);

        $lexer = new Lexer('\s');
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);
        $lexer->moveNext();
        $this->assertEquals('s', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SHORT_S, $lexer->lookahead->type);

        $lexer = new Lexer('\D');
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);
        $lexer->moveNext();
        $this->assertEquals('D', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SHORT_NOT_D, $lexer->lookahead->type);

        $lexer = new Lexer('\d');
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);
        $lexer->moveNext();
        $this->assertEquals('d', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SHORT_D, $lexer->lookahead->type);
    }

    public function testLexerPatternF(): void
    {
        $lexer = new Lexer('[\']');
        $this->assertEquals('[', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_OPEN, $lexer->lookahead->type);

        // in the above expression using php metasequence \' to escape a single quote
        // the reg only see the expression ['] and NOT [\']
        $lexer->moveNext();
        $this->assertEquals("'", $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(']', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_CLOSE, $lexer->lookahead->type);
    }


    public function testLexerEscapedBlackslash(): void
    {
        $lexer = new Lexer('\\\\');
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);
    }


    public function testLexerBrackets(): void
    {
        $lexer = new Lexer('[\p{}]');
        $this->assertEquals('[', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals("\\", $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals("p", $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SHORT_P, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals("{", $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals("}", $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(']', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_CLOSE, $lexer->lookahead->type);

        $lexer = new Lexer('\p{}');
        $this->assertEquals("\\", $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals("p", $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SHORT_P, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals("{", $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_QUANTIFIER_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals("}", $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_QUANTIFIER_CLOSE, $lexer->lookahead->type);
    }


    public function testAlternation(): void
    {
        $lexer = new Lexer('A|a');
        $this->assertEquals('A', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('|', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_CHOICE_BAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('a', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        // no alternation in char classes
        $lexer = new Lexer('[A|a]');
        $this->assertEquals('[', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('A', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('|', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('a', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(']', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_CLOSE, $lexer->lookahead->type);
    }


    public function testDotCharacter(): void
    {
        $lexer = new Lexer('.');
        $this->assertEquals('.', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_DOT, $lexer->lookahead->type);

        $lexer = new Lexer('\.');
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('.', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        // normal char in a char class
        $lexer = new Lexer('[.]');
        $this->assertEquals('[', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('.', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(']', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_CLOSE, $lexer->lookahead->type);
    }

    public function testLexerPatternG(): void
    {
        $lexer = new Lexer('abcd&\*\(\)');
        $this->assertEquals('a', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('b', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('c', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('d', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('&', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('*', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('(', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(')', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);
    }


    public function testUnicodePropertyinCharacterClass(): void
    {
        $lexer = new Lexer('[np\p{L}]');
        $this->assertEquals('[', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('n', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('p', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('p', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SHORT_P, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('{', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('L', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('}', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(']', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_CLOSE, $lexer->lookahead->type);
    }


    public function testUnicodeReferencePropertyinCharacterClass(): void
    {
        $lexer = new Lexer('[no\X{00FF}]');
        $this->assertEquals('[', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('n', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('o', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('X', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SHORT_UNICODE_X, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('{', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('0', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('0', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('F', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('F', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('}', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(']', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_CLOSE, $lexer->lookahead->type);
    }


    public function testCarretAndDollar(): void
    {
        $lexer = new Lexer('^$');
        $this->assertEquals('^', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_START_CARET, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('$', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_END_DOLLAR, $lexer->lookahead->type);

        $lexer = new Lexer('[\^$]');
        $this->assertEquals('[', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('\\', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('^', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('$', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(']', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_SET_CLOSE, $lexer->lookahead->type);
    }

    public function testLexerPatternHGroupNesting(): void
    {
        $lexer = new Lexer('(())');
        $this->assertEquals('(', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_GROUP_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals('(', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_GROUP_OPEN, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(')', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_GROUP_CLOSE, $lexer->lookahead->type);

        $lexer->moveNext();
        $this->assertEquals(')', $lexer->lookahead->value);
        $this->assertEquals(Lexer::T_GROUP_CLOSE, $lexer->lookahead->type);
    }


    public function testGroupNestingErrorStillOpen(): void
    {
        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Opening group char "(" has no matching closing character');

        new Lexer('(()');
    }


    public function testGroupNestingErrorClosedNotOpened(): void
    {
        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Closing group char ")" has no matching opening character');

        new Lexer('())');
    }


    public function testCharSetNestingError(): void
    {
        $this->expectException(RegexException::class);
        $this->expectExceptionMessage("Can't have a second character class while first remains open");

        new Lexer('[[]]');
    }


    public function testCharSetOpenError(): void
    {
        $this->expectException(RegexException::class);
        $this->expectExceptionMessage("Can't close a character class while none is open");

        new Lexer(']');
    }


    public function testCharSetOpenNotClosed(): void
    {
        $this->expectException(RegexException::class);
        $this->expectExceptionMessage("Character Class has not been closed");

        new Lexer('[');
    }
}
