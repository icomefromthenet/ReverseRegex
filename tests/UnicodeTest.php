<?php

declare(strict_types=1);

namespace ReverseRegex\Test;

use PHPUnit\Framework\TestCase;
use ReverseRegex\Exception as RegexException;
use ReverseRegex\Generator\LiteralScope;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;
use ReverseRegex\Parser\Unicode;

class UnicodeTest extends TestCase
{
    public function testUnsupportedShortProperty(): void
    {
        $lexer = new Lexer('\p');
        $scope = new Scope();
        $parser = new Unicode();

        $lexer->moveNext();
        $lexer->moveNext();

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Property \p (Unicode Property) not supported use \x to specify unicode character or range');

        $parser->parse($scope, $scope, $lexer);
    }

    public function testErrorNoOpeningBrace(): void
    {
        $lexer = new Lexer('\Xaaaaa');
        $scope = new Scope();
        $parser = new Unicode();

        $lexer->moveNext();
        $lexer->moveNext();

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Expecting character { after \X none found');

        $parser->parse($scope, $scope, $lexer);
    }

    public function testErrorNested(): void
    {
        $lexer = new Lexer('\X{aa{aa}');
        $scope = new Scope();
        $parser = new Unicode();

        $lexer->moveNext();
        $lexer->moveNext();

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Nesting hex value ranges is not allowed');

        $parser->parse($scope, $scope, $lexer);
    }

    public function testErrorUnclosed(): void
    {
        $lexer = new Lexer('\X{aaaa');
        $scope = new Scope();
        $parser = new Unicode();

        $lexer->moveNext();
        $lexer->moveNext();

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Closing quantifier token `}` not found');

        $parser->parse($scope, $scope, $lexer);
    }

    public function testErrorEmptyToken(): void
    {
        $lexer = new Lexer('\X{}');
        $scope = new Scope();
        $parser = new Unicode();

        $lexer->moveNext();
        $lexer->moveNext();

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('No hex number found inside the range');

        $parser->parse($scope, $scope, $lexer);
    }

    public function testsExampleA(): void
    {
        $lexer = new Lexer('\X{FA24}');
        $scope = new Scope();
        $parser = new Unicode();
        $head = new LiteralScope('lit1', $scope);

        $lexer->moveNext();
        $lexer->moveNext();

        $parser->parse($head, $scope, $lexer);

        $result = $head->getLiterals();

        $this->assertEquals('﨤', $result[0]);
    }

    public function testShortErrorWhenBraces(): void
    {
        $lexer = new Lexer('\x{64');
        $scope = new Scope();
        $parser = new Unicode();
        $head = new LiteralScope('lit1', $scope);

        $lexer->moveNext();
        $lexer->moveNext();

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage('Braces not supported here');

        $parser->parse($head, $scope, $lexer);
    }

    public function testShortX(): void
    {
        $lexer = new Lexer('\x64');
        $scope = new Scope();
        $parser = new Unicode();
        $head = new LiteralScope('lit1', $scope);

        $lexer->moveNext();
        $lexer->moveNext();

        $parser->parse($head, $scope, $lexer);

        $result = $head->getLiterals();

        $this->assertEquals('d', $result[0]);
    }
}
// End of File
