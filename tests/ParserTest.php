<?php

declare(strict_types=1);

namespace ReverseRegex\Test;

use PHPUnit\Framework\TestCase;
use ReverseRegex\Exception as RegexException;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;
use ReverseRegex\Parser;
use ReverseRegex\Random\MersenneRandom;
use ReverseRegex\Random\SimpleRandom;

class ParserTest extends TestCase
{
    public function testParserExampleA(): void
    {
        $lexer = new Lexer('ex1{5,5}');
        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();

        $result = '';
        $random = new MersenneRandom(100);
        $generator->generate($result, $random);

        $this->assertEquals('ex11111', $result);
    }

    public function testParserWithoutExplicitHead(): void
    {
        $parser = new Parser(new Lexer('ab{2}'), new Scope());

        $result = '';
        $parser->parse()->getResult()->generate($result, new MersenneRandom(100));

        $this->assertSame('abb', $result);
    }

    public function testParserExampleB(): void
    {
        // $lexer = new Lexer('\(0[23478]\)-[0-9]{4} [0-9]{4}');

        $lexer = new Lexer('(\(0[23478]\)){4}');

        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();

        $result = '';
        $random = new MersenneRandom(100);
        $generator->generate($result, $random);

        $this->assertEquals('(02)(04)(02)(08)', $result);
    }

    public function testExampleC(): void
    {
        $lexer = new Lexer('509[0-9][A-K]');
        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();

        $result = '';
        $random = new MersenneRandom(100);

        $generator->generate($result, $random);
        $this->assertEquals('5090J', $result);
    }

    public function testExampleD(): void
    {
        $lexer = new Lexer('\d\d\d');
        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();

        $result = '';
        $random = new MersenneRandom(100);

        $generator->generate($result, $random);
        $this->assertMatchesRegularExpression('/\d\d\d/', $result);
    }

    public function testExampleE(): void
    {
        $lexer = new Lexer('\d\d\d([a-zA-Z])\w.');
        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();

        $result = '';
        $random = new MersenneRandom(10_034_343);

        $generator->generate($result, $random);
        $this->assertMatchesRegularExpression('/\d\d\d([a-zA-Z])\w./', $result);
    }

    public function testParserExamplePhoneNumber(): void
    {
        $lexer = new Lexer('\(0[23478]\)[0-9]{4}-[0-9]{4}');

        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();

        $result = '';
        $random = new MersenneRandom(100);
        $generator->generate($result, $random);

        $this->assertEquals('(02)2595-1288', $result);
    }

    public function testParserExamplePostCode(): void
    {
        // Australian Post Codes.

        // ACT: 0200-0299 and 2600-2639.
        // NSW: 1000-1999, 2000-2599 and 2640-2914.
        // NT:  0900-0999 and 0800-0899.
        // QLD: 9000-9999 and 4000-4999.
        // SA:  5000-5999.
        // TAS: 7800-7999 and 7000-7499.
        // VIC: 8000-8999 and 3000-3999.
        // WA: 6800-6999 and 6000-6799

        $lexer = new Lexer('(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})');
        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();

        $random = new MersenneRandom(10_789);

        for ($i = 100; $i > 0; --$i) {
            $result = '';
            $generator->generate($result, $random);
            $this->assertMatchesRegularExpression('/^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$/', $result);
        }

        // Generate Postcode for ACT only

        $lexer = new Lexer('02[0-9]{2}|26[0-3][0-9]');
        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();

        $result = '';
        $random = new MersenneRandom(100);

        $generator->generate($result, $random);
        $this->assertEquals('0225', $result);

        $result = '';
        $generator->generate($result, $random);
        $this->assertEquals('2631', $result);

        $result = '';
        $generator->generate($result, $random);
        $this->assertEquals('0288', $result);

        $result = '';
        $generator->generate($result, $random);
        $this->assertEquals('0243', $result);

        $result = '';
        $generator->generate($result, $random);
        $this->assertEquals('0284', $result);

        $result = '';
        $generator->generate($result, $random);
        $this->assertEquals('0210', $result);
    }

    public function testHellowWorld(): void
    {
        $lexer = new Lexer('Hello|World|Is|Good');
        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();

        $random = new MersenneRandom(10_789);

        for ($i = 10; $i > 0; --$i) {
            $result = '';
            $generator->generate($result, $random);
            $this->assertMatchesRegularExpression('/^Hello|World|Is|Good$/', $result);
        }
    }

    public function testLimitingQuantifer(): void
    {
        $lexer = new Lexer('(Hello){5,9}');
        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();

        $random = new MersenneRandom(10_789);

        for ($i = 10; $i > 0; --$i) {
            $result = '';
            $generator->generate($result, $random);
            $this->assertMatchesRegularExpression('/(Hello){5,9}/', $result);
        }

        $lexer = new Lexer('(Hello)?');
        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();

        $random = new MersenneRandom(107_559);

        $result = '';
        $generator->generate($result, $random);
        $this->assertMatchesRegularExpression('/(Hello)?/', $result);
    }

    public function testParserLexerError(): void
    {
        $this->expectException(RegexException::class);
        $this->expectExceptionMessage("Error found STARTING at position 3 after `\(0[` with msg Negated Character Set ranges not supported at this time");

        $lexer = new Lexer('\(0[^23478]\)[0-9]{4}-[0-9]{4}');

        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();
    }

    public function testParserLexerErrorB(): void
    {
        $lexer = new Lexer('\(0[23478]\)[9-4]{4}-[0-9]{4}');

        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);

        $this->expectException(RegexException::class);
        $this->expectExceptionMessage("Error found STARTING at position 16 after `\(0[23478]\)[9-4]` with msg Character class range 9 - 4 is out of order");

        $generator = $parser->parse()->getResult();
    }

    public function testSimpleRandomWithStarQuantifier(): void
    {
        $lexer = new Lexer('[a-z]*');
        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();

        $result = '';
        $random = new SimpleRandom(777);

        $generator->generate($result, $random);
        $this->assertMatchesRegularExpression('/^[a-z]*$/', $result);
    }

    public function testSimpleRandomWithPlusQuantifier(): void
    {
        $lexer = new Lexer('[a-z]+');
        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer, $container, $head);
        $generator = $parser->parse()->getResult();

        $result = '';
        $random = new SimpleRandom(777);

        $generator->generate($result, $random);
        $this->assertMatchesRegularExpression('/^[a-z]+$/', $result);
    }

    public function testUnboundedMaxDefaultsAndSetter(): void
    {
        $parser = new Parser(new Lexer('a'), new Scope(), new Scope());

        $this->assertSame(Parser\Quantifier::DEFAULT_UNBOUNDED_MAX, $parser->getUnboundedMax());
        $this->assertSame($parser, $parser->setUnboundedMax(5));
        $this->assertSame(5, $parser->getUnboundedMax());
    }

    public function testUnboundedMaxMustBePositive(): void
    {
        $parser = new Parser(new Lexer('a'), new Scope(), new Scope());

        $this->expectException(RegexException::class);
        $parser->setUnboundedMax(0);
    }

    public function testUnboundedMaxLimitsStarQuantifier(): void
    {
        $parser = new Parser(new Lexer('a*'), new Scope(), new Scope());
        $parser->setUnboundedMax(3);
        $generator = $parser->parse()->getResult();

        for ($seed = 1; $seed <= 20; ++$seed) {
            $result = '';
            $generator->generate($result, new MersenneRandom($seed));
            $this->assertMatchesRegularExpression('/^a{0,3}$/', $result);
        }
    }

    public function testGetLexerReturnsTheLexer(): void
    {
        $lexer = new Lexer('a');
        $parser = new Parser($lexer, new Scope(), new Scope());

        $this->assertSame($lexer, $parser->getLexer());
    }

    public function testGroupsWithAlternation(): void
    {
        $parser = new Parser(new Lexer('(ab|cd)e'), new Scope(), new Scope());
        $generator = $parser->parse()->getResult();

        for ($seed = 1; $seed <= 30; ++$seed) {
            $result = '';
            $generator->generate($result, new MersenneRandom($seed));
            $this->assertMatchesRegularExpression('/^(abe|cde)$/', $result);
        }
    }

    public function testCompressReturnsTheRegexUntilTheCurrentToken(): void
    {
        $lexer = new Lexer('abc');
        $parser = new Parser($lexer, new Scope(), new Scope());
        $lexer->moveNext();
        $lexer->moveNext();

        $this->assertSame('ab', $parser->compress());
    }

    public function testCreateSubParserRejectsUnknownNames(): void
    {
        $this->expectException(RegexException::class);
        Parser::createSubParser('unknown');
    }
}
// End of File
