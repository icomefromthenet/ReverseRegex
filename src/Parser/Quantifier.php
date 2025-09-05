<?php

declare(strict_types=1);

namespace ReverseRegex\Parser;

use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;
use ReverseRegex\Exception as ParserException;

/**
 * Parses quantifier tokens like `*`, `+`, `?`, and `{m,n}`.
 * This strategy modifies the `min` and `max` occurrences of the node it follows.
 */
class Quantifier implements StrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(Scope $head, Scope $set, Lexer $lexer): Scope
    {
        $token = $lexer->token;
        if ($token === null) {
            throw new ParserException('No token found for quantifier parser to handle');
        }

        switch ($token->type) {
            case Lexer::T_QUANTIFIER_PLUS:
                $head->setMinOccurances(1);
                $head->setMaxOccurances(PHP_INT_MAX);
                break;
            case Lexer::T_QUANTIFIER_QUESTION:
                $head->setMinOccurances(0);
                $head->setMaxOccurances(1);
                break;
            case Lexer::T_QUANTIFIER_STAR:
                $head->setMinOccurances(0);
                $head->setMaxOccurances(PHP_INT_MAX);
                break;
            case Lexer::T_QUANTIFIER_OPEN:
                $this->parseBracedQuantifier($head, $lexer);
                break;
        }

        return $head;
    }

    /**
     * Parses a braced quantifier like `{n}`, `{n,}`, or `{n,m}`.
     *
     * @param Scope $head  The node to which the quantifier applies.
     * @param Lexer $lexer The lexer providing the token stream.
     */
    private function parseBracedQuantifier(Scope $head, Lexer $lexer): void
    {
        // For the 'min' value, parsing should stop at a comma or a closing brace.
        // We pass T_LITERAL_CHAR to enable comma detection.
        $min = $this->parseNumber($lexer, [Lexer::T_QUANTIFIER_CLOSE, Lexer::T_LITERAL_CHAR]);
        if ($min === null) {
            throw new ParserException('Quantifier missing minimum value');
        }

        $max = $min;

        if ($lexer->lookahead?->value === ',') {
            $lexer->moveNext(); // Consume comma.
            // For the 'max' value, only a closing brace should stop parsing.
            $parsedMax = $this->parseNumber($lexer, [Lexer::T_QUANTIFIER_CLOSE]);

            if ($parsedMax === null) {
                // Note: Preserving original typo "compitable" to match test expectations.
                throw new ParserException('Quantifier expects and integer compitable string');
            }
            $max = $parsedMax;
        }

        if ($min > $max) {
            throw new ParserException("The quantifier min value $min cannot be greater than the max value $max");
        }

        if ($lexer->lookahead === null || !$lexer->lookahead->isA(Lexer::T_QUANTIFIER_CLOSE)) {
            throw new ParserException('Closing quantifier token `}` not found or quantifier is malformed');
        }

        $lexer->moveNext(); // Consume `}`.

        $head->setMinOccurances($min);
        $head->setMaxOccurances($max);
    }

    /**
     * Moves the lexer forward, parsing a numeric string until a terminator token is found.
     *
     * @param Lexer $lexer            The lexer providing the token stream.
     * @param int[] $terminatorTokens An array of token types that should stop the parsing.
     * @return int|null The parsed number, or null if no digits were found.
     */
    private function parseNumber(Lexer $lexer, array $terminatorTokens): ?int
    {
        $numberString = '';

        while ($lexer->lookahead !== null) {
            // Always stop at the final closing brace.
            if ($lexer->lookahead->isA(Lexer::T_QUANTIFIER_CLOSE)) {
                break;
            }

            if ($lexer->lookahead->value === ',' && $numberString === '') {
                // Note: Preserving original typo "compitable" to match test expectations.
                throw new ParserException('Quantifier expects and integer compitable string');
            }

            // Check for a comma as a valid terminator when parsing the 'min' part.
            if (in_array(Lexer::T_LITERAL_CHAR, $terminatorTokens, true) && $lexer->lookahead->value === ',') {
                break;
            }

            $lexer->moveNext();
            $token = $lexer->token;

            if ($token->isA(Lexer::T_LITERAL_CHAR) && trim($token->value) === '') {
                continue; // Skip whitespace.
            }

            if ($token->isA(Lexer::T_QUANTIFIER_OPEN)) {
                throw new ParserException('Nesting Quantifiers is not allowed');
            }

            if (!$token->isA(Lexer::T_LITERAL_NUMERIC)) {
                // Note: Preserving original typo "compitable" to match test expectations.
                throw new ParserException('Quantifier expects and integer compitable string');
            }

            $numberString .= $token->value;
        }

        return $numberString === '' ? null : (int)$numberString;
    }
}
