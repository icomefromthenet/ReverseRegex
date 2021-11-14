<?php

declare(strict_types=1);

namespace ReverseRegex\Parser;

use ReverseRegex\Exception as ParserException;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;

/**
 *  Parse a unicode sequence e.g  \x54 \X{4444}.
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *
 *  @since 0.0.1
 */
class Unicode implements StrategyInterface
{
    /**
     *  Parse the current token for new Quantifiers.
     *
     *  @param ReverseRegex\Generator\LiteralScope $head
     *  @param ReverseRegex\Generator\Scope $set
     *  @param ReverseRegex\Lexer $lexer
     *
     *  @return ReverseRegex\Generator\Scope a new head
     */
    public function parse(Scope $head, Scope $set, Lexer $lexer)
    {
        $character = $this->evaluate($lexer);
        $head->addLiteral($character);

        return $head;
    }

    /**
     *  Parse a reference.
     */
    public function evaluate(Lexer $lexer)
    {
        switch (true) {
            case $lexer->isNextToken(Lexer::T_SHORT_P):
                throw new ParserException('Property \p (Unicode Property) not supported use \x to specify unicode character or range');
            break;
            case $lexer->isNextToken(Lexer::T_SHORT_UNICODE_X):

                $lexer->moveNext();

                if ('{' !== $lexer->lookahead['value']) {
                    throw new ParserException('Expecting character { after \X none found');
                }

                $tokens = [];
                while ($lexer->moveNext() && null !== $lexer->lookahead && '}' !== $lexer->lookahead['value']) {

                    // check if we nested eg.{ddd{d}
                    if ('{' === $lexer->lookahead['value']) {
                        throw new ParserException('Nesting hex value ranges is not allowed');
                    }

                    if (' ' !== $lexer->lookahead['value'] && false === ctype_xdigit($lexer->lookahead['value'])) {
                        throw new ParserException(sprintf('Character %s is not a hexdeciaml digit', $lexer->lookahead['value']));
                    }

                    $tokens[] = $lexer->lookahead['value'];
                }
                // check that current lookahead is a closing character as it's possible to iterate to end of string (i.e. lookahead === null)
                if (null === $lexer->lookahead || '}' !== $lexer->lookahead['value']) {
                    throw new ParserException('Closing quantifier token `}` not found');
                }

                if (0 === count($tokens)) {
                    throw new ParserException('No hex number found inside the range');
                }

                $number = trim(implode('', $tokens));

                return mb_chr(hexdec($number));

            break;
            case $lexer->isNextToken(Lexer::T_SHORT_X):
                // only allow another 2 hex characters
                $glimpse = $lexer->glimpse();
                if ('{' === $glimpse['value']) {
                    throw new ParserException('Braces not supported here');
                }

                $tokens = [];
                $count = 2;
                while ($count > 0 && $lexer->moveNext()) {
                    $tokens[] = $lexer->lookahead['value'];
                    --$count;
                }

                $value = trim(implode('', $tokens));

                return mb_chr(hexdec($value));
            break;
            default:
                throw new ParserException('No Unicode expression to evaluate');
        }
    }
}
