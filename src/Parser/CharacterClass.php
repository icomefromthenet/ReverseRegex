<?php

declare(strict_types=1);

namespace ReverseRegex\Parser;

use ReverseRegex\Generator\LiteralScope;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;
use ReverseRegex\Exception as ParserException;

/**
 * Parses a character class token stream (e.g., `[a-z0-9]`).
 */
class CharacterClass implements StrategyInterface
{
    /** @var string|null The most recently parsed literal, held in case it's the start of a range. */
    private ?string $lastLiteral = null;

    /** @var bool True if the last literal was the end of a range, preventing it from starting a new one. */
    private bool $literalIsEndOfRange = false;

    /**
     * {@inheritDoc}
     */
    public function parse(Scope $head, Scope $set, Lexer $lexer): Scope
    {
        if (!$head instanceof LiteralScope) {
            throw new ParserException('CharacterClass parser requires a LiteralScope to write to.');
        }

        $this->lastLiteral = null;
        $this->literalIsEndOfRange = false;

        if ($lexer->lookahead?->isA(Lexer::T_SET_NEGATED) === true) {
            throw new ParserException('Negated Character Set ranges not supported at this time');
        }

        // This loop now correctly processes the current token BEFORE peeking at the next one.
        while ($lexer->lookahead !== null && !$lexer->lookahead->isA(Lexer::T_SET_CLOSE)) {
            $lexer->moveNext();
            $this->processToken($head, $lexer);
        }

        if ($this->lastLiteral !== null && !$this->literalIsEndOfRange) {
            $this->addLiteral($head, $this->lastLiteral);
        }

        if ($lexer->lookahead === null || !$lexer->lookahead->isA(Lexer::T_SET_CLOSE)) {
            throw new ParserException('Unterminated character class, missing "]"');
        }

        $lexer->moveNext(); // Consume the final ']'

        $literals = $head->getLiterals();
        $sortedValues = $literals->getValues();
        sort($sortedValues);
        $literals->clear();
        foreach ($sortedValues as $value) {
            $literals->add($value);
        }

        return $head;
    }

    /**
     * Processes a single token within the character class.
     *
     * @param LiteralScope $head  The scope to add literals to.
     * @param Lexer        $lexer The lexer providing the tokens.
     */
    private function processToken(LiteralScope $head, Lexer $lexer): void
    {
        $token = $lexer->token;

        if ($token->isA(Lexer::T_ESCAPE_CHAR)) {
            return; // Skip the escape token itself; the next token is the one we care about.
        }

        switch ($token->type) {
            case Lexer::T_SET_RANGE:
                $this->handleRange($head, $lexer);
                break;
            case Lexer::T_LITERAL_CHAR:
            case Lexer::T_LITERAL_NUMERIC:
                $this->handleLiteral($head, $token->value);
                break;
            case Lexer::T_SHORT_UNICODE_X:
            case Lexer::T_SHORT_P:
            case Lexer::T_SHORT_X:
                $unicodeParser = new Unicode();
                $char = $unicodeParser->evaluate($lexer, true);
                $this->handleLiteral($head, $char);
                break;
            default:
                throw new ParserException(sprintf('Unexpected token type %s in character class', $token->type));
        }
    }

    /**
     * Handles a literal token, storing it in preparation for a potential range.
     */
    private function handleLiteral(LiteralScope $head, string $literal): void
    {
        // If the previously stored literal was not part of a range, it's a standalone character and should be added now.
        if ($this->lastLiteral !== null && !$this->literalIsEndOfRange) {
            $this->addLiteral($head, $this->lastLiteral);
        }
        // Store the new literal. It might be the start of a range, or just a standalone character.
        $this->lastLiteral = $literal;
        $this->literalIsEndOfRange = false;
    }

    /**
     * Handles a range token (`-`), creating all characters between the last literal and the next.
     */
    private function handleRange(LiteralScope $head, Lexer $lexer): void
    {
        $start = $this->lastLiteral;
        if ($start === null) {
            throw new ParserException('Invalid range, `-` must be preceded by a character.');
        }

        $lexer->moveNext();
        $endToken = $lexer->token;
        if ($endToken === null) {
            throw new ParserException('Unterminated character class range');
        }

        $end = '';
        $unicodeParser = new Unicode();

        if ($endToken->isA(Lexer::T_ESCAPE_CHAR)) {
            $lexer->moveNext(); // It's an escaped character, move past the `\`
            $end = $unicodeParser->evaluate($lexer, true);
        } elseif ($endToken->isA(Lexer::T_SHORT_X, Lexer::T_SHORT_UNICODE_X, Lexer::T_SHORT_P)) {
            $end = $unicodeParser->evaluate($lexer, true);
        } else {
            $end = $endToken->value;
        }

        $this->fillRange($head, $start, (string)$end);

        $this->lastLiteral = (string)$end;
        $this->literalIsEndOfRange = true;
    }

    /**
     * Populates the scope with all characters in a given range.
     */
    public function fillRange(LiteralScope $head, string $start, string $end): void
    {
        $startIndex = mb_ord($start, 'UTF-8');
        $endIndex = mb_ord($end, 'UTF-8');

        if ($endIndex < $startIndex) {
            throw new ParserException(sprintf('Character class range %s - %s is out of order', $start, $end));
        }

        for ($i = $startIndex; $i <= $endIndex; $i++) {
            $this->addLiteral($head, mb_chr($i, 'UTF-8'));
        }
    }

    /**
     * Adds a single character literal to the scope, keyed by its ordinal value.
     */
    private function addLiteral(LiteralScope $head, string $char): void
    {
        $index = mb_ord($char, 'UTF-8');
        $head->setLiteral($index, $char);
    }
}
