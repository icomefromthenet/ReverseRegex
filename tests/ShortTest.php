<?php

declare(strict_types=1);

namespace ReverseRegex\Test;

use PHPUnit\Framework\TestCase;
use ReverseRegex\Generator\LiteralScope;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;
use ReverseRegex\Parser\Short;

class ShortTest extends TestCase
{
    public function testDigit(): void
    {
        $lexer = new Lexer('\d');
        $scope = new Scope();
        $parser = new Short();
        $head = new LiteralScope('lit1', $scope);

        $lexer->moveNext();
        $lexer->moveNext();

        $parser->parse($head, $scope, $lexer);

        $result = $head->getLiterals();

        foreach ($result as $value) {
            $this->assertMatchesRegularExpression('/\d/', $value);
        }
    }

    public function testNotDigit(): void
    {
        $lexer = new Lexer('\D');
        $scope = new Scope();
        $parser = new Short();
        $head = new LiteralScope('lit1', $scope);

        $lexer->moveNext();
        $lexer->moveNext();

        $parser->parse($head, $scope, $lexer);

        $result = $head->getLiterals();

        foreach ($result as $value) {
            $this->assertMatchesRegularExpression('/\D/', $value);
        }
    }

    public function testWhitespace(): void
    {
        $lexer = new Lexer('\s');
        $scope = new Scope();
        $parser = new Short();
        $head = new LiteralScope('lit1', $scope);

        $lexer->moveNext();
        $lexer->moveNext();

        $parser->parse($head, $scope, $lexer);

        $result = $head->getLiterals();

        foreach ($result as $value) {
            $this->assertTrue(! empty($value));
        }
    }

    public function testNonWhitespace(): void
    {
        $lexer = new Lexer('\S');
        $scope = new Scope();
        $parser = new Short();
        $head = new LiteralScope('lit1', $scope);

        $lexer->moveNext();
        $lexer->moveNext();

        $parser->parse($head, $scope, $lexer);

        $result = $head->getLiterals();

        foreach ($result as $value) {
            $this->assertTrue(! empty($value));
        }
    }

    public function testWord(): void
    {
        $lexer = new Lexer('\w');
        $scope = new Scope();
        $parser = new Short();
        $head = new LiteralScope('lit1', $scope);

        $lexer->moveNext();
        $lexer->moveNext();

        $parser->parse($head, $scope, $lexer);

        $result = $head->getLiterals();

        foreach ($result as $value) {
            $this->assertMatchesRegularExpression('/\w/', $value);
        }
    }

    public function testNonWord(): void
    {
        $lexer = new Lexer('\W');
        $scope = new Scope();
        $parser = new Short();
        $head = new LiteralScope('lit1', $scope);

        $lexer->moveNext();
        $lexer->moveNext();

        $parser->parse($head, $scope, $lexer);

        $result = $head->getLiterals();

        foreach ($result as $value) {
            $this->assertMatchesRegularExpression('/\W/', $value);
        }
    }

    public function testDotRange(): void
    {
        $lexer = new Lexer('.');
        $scope = new Scope();
        $parser = new Short();
        $head = new LiteralScope('lit1', $scope);

        $lexer->moveNext();

        $parser->parse($head, $scope, $lexer);

        $result = $head->getLiterals();

        // match 0..127 char in ASSCI Chart
        $this->assertCount(128, $result);
    }
}
// End of File
