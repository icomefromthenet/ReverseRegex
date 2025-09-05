<?php

declare(strict_types=1);

namespace ReverseRegex\Parser;

use ReverseRegex\Generator\LiteralScope;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;
use ReverseRegex\Exception as ParserException;

class Unicode implements StrategyInterface
{
    public function parse(Scope $head, Scope $set, Lexer $lexer): Scope
    {
        $character = $this->evaluate($lexer);

        if (!$head instanceof LiteralScope) {
            throw new ParserException('Unicode parser requires a LiteralScope to write to.');
        }

        $head->addLiteral($character);
        return $head;
    }

    public function evaluate(Lexer $lexer, bool $inCharClass = false): string
    {
        $tokenType = $lexer->token->type ?? null;
        return match ($tokenType) {
            Lexer::T_SHORT_P => throw new ParserException('Property \p (Unicode Property) not supported use \x to specify unicode character or range'),
            Lexer::T_SHORT_UNICODE_X => $this->parseBracedHex($lexer),
            Lexer::T_SHORT_X => $this->parseTwoDigitHex($lexer, $inCharClass),
            default => throw new ParserException('No Unicode expression to evaluate'),
        };
    }

    private function parseBracedHex(Lexer $lexer): string
    {
        if (!$lexer->moveNext() || $lexer->token === null || $lexer->token->value !== '{') {
            throw new ParserException('Expecting character { after \X none found');
        }
        $hexTokens = [];
        while ($lexer->moveNext() && $lexer->token !== null && $lexer->token->value !== '}') {
            if ($lexer->token->value === '{') {
                throw new ParserException('Nesting hex value ranges is not allowed');
            }
            if (!ctype_xdigit((string) $lexer->token->value)) {
                throw new ParserException(sprintf('Character `%s` is not a hexadecimal digit', $lexer->token->value));
            }
            $hexTokens[] = $lexer->token->value;
        }
        if ($lexer->token === null || $lexer->token->value !== '}') {
            throw new ParserException('Closing quantifier token `}` not found');
        }
        if (empty($hexTokens)) {
            throw new ParserException('No hex number found inside the range');
        }
        return mb_chr(hexdec(implode('', $hexTokens)), 'UTF-8');
    }

    private function parseTwoDigitHex(Lexer $lexer, bool $inCharClass = false): string
    {
        if ($lexer->lookahead !== null && $lexer->lookahead->value === '{') {
            $message = $inCharClass ? 'Braces are not supported for `\x`, use `\X{...}` instead' : 'Braces not supported here';
            throw new ParserException($message);
        }

        // 1. Get the first hex digit from the lookahead
        $hex1 = $lexer->lookahead?->value;
        if ($hex1 === null || !ctype_xdigit((string)$hex1)) {
            throw new ParserException('Expected a hex digit after \x');
        }
        $lexer->moveNext(); // Consume the first hex digit. State is now: token='6', lookahead='4'

        // 2. Get the second hex digit from the new lookahead
        $hex2 = $lexer->lookahead?->value;
        if ($hex2 === null || !ctype_xdigit((string)$hex2)) {
            throw new ParserException('Expected a second hex digit for \x');
        }
        $lexer->moveNext(); // Consume the second hex digit. State is now: token='4', lookahead=null

        $hexValue = (string)$hex1 . (string)$hex2;

        return mb_chr(hexdec($hexValue), 'UTF-8');
    }
}
