<?php

declare(strict_types=1);

namespace ReverseRegex;

use Doctrine\Common\Lexer\Token;
use ReverseRegex\Exception as ParserException;
use ReverseRegex\Generator\LiteralScope;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Parser\CharacterClass;
use ReverseRegex\Parser\Quantifier;
use ReverseRegex\Parser\Short;
use ReverseRegex\Parser\StrategyInterface;
use ReverseRegex\Parser\Unicode;

/**
 * Parses a token stream from the Lexer into a tree of Generator nodes.
 */
class Parser
{
    /** @var Scope The current scope to which new nodes are attached. */
    private Scope $head;

    /** @var Scope The most recently attached node, used as a target for quantifiers. */
    private Scope $left;

    /** @var array<string, StrategyInterface|class-string<StrategyInterface>> A cache of sub-parser instances. */
    private static array $sub_parsers = [
      'character' => CharacterClass::class,
      'unicode' => Unicode::class,
      'quantifier' => Quantifier::class,
      'short' => Short::class,
    ];

    /**
     * @param Lexer      $lexer  The lexer providing the token stream.
     * @param Scope      $result The root scope where the final AST will be built.
     * @param Scope|null $head   The initial head scope for parsing.
     */
    public function __construct(
        private readonly Lexer $lexer,
        private readonly Scope $result,
        ?Scope $head = null
    ) {
        $this->head = $head ?? new Scope();
        $this->result->attach($this->head);
        $this->left = $this->head;
    }

    /**
     * Parses the token stream from the lexer.
     *
     * @return $this
     * @throws ParserException
     */
    public function parse(): self
    {
        try {
            while ($this->lexer->moveNext()) {
                if ($this->dispatchToken($this->lexer->token)) {
                    break; // A TRUE return from dispatchToken means the current group is done.
                }
            }

            // Dispatch the final token if it exists. moveNext() returns false *after* the last token is consumed.
            if ($this->lexer->token !== null && $this->lexer->lookahead === null) {
                $this->dispatchToken($this->lexer->token);
            }

            $this->lexer->validate();
        } catch (ParserException $e) {
            $messageText = $e->getMessage();
            $position = 0;
            $contextEndPosition = 0;

            if (str_contains($messageText, 'is out of order')) {
                // For range errors, the original parser uses the position of the lookahead token (e.g., ']').
                $position = $this->lexer->lookahead->position;
                // The context includes the lookahead token.
                $contextEndPosition = $position + ($this->lexer->lookahead ? mb_strlen((string)$this->lexer->lookahead->value, 'UTF-8') : 0);
            } else {
                // For other errors (like negation), it uses the position of the token that
                // started the sub-parser (e.g., '[').
                $position = $this->lexer->token->position;
                // The context includes the token that caused the error.
                $contextEndPosition = $this->lexer->lookahead->position ?? ($this->lexer->token->position + 1);
            }

            $context = $this->lexer->getInputUntilPosition($contextEndPosition);

            $message = sprintf('Error found STARTING at position %d after `%s` with msg %s ', $position, $context, $e->getMessage());
            throw new ParserException($message, 0, $e);
        }

        return $this;
    }

    /**
     * Dispatches a token to the appropriate handler.
     *
     * @param Token $token The token to dispatch.
     * @return bool Returns true if parsing of the current group should terminate.
     */
    private function dispatchToken(Token $token): bool
    {
        switch ($token->type) {
            case Lexer::T_GROUP_OPEN:
                $parser = new Parser($this->lexer, new Scope(), new Scope());
                $this->left = $parser->parse()->getResult();
                $this->head->attach($this->left);
                break;
            case Lexer::T_GROUP_CLOSE:
                return true; // Signal to stop parsing this sub-group.
            case Lexer::T_LITERAL_CHAR:
            case Lexer::T_LITERAL_NUMERIC:
                $this->left = new LiteralScope();
                $this->left->addLiteral($token->value);
                $this->head->attach($this->left);
                break;
            case Lexer::T_SET_OPEN:
                $this->runSubParser('character');
                break;
            case Lexer::T_DOT:
            case Lexer::T_SHORT_D:
            case Lexer::T_SHORT_NOT_D:
            case Lexer::T_SHORT_W:
            case Lexer::T_SHORT_NOT_W:
            case Lexer::T_SHORT_S:
            case Lexer::T_SHORT_NOT_S:
                $this->runSubParser('short');
                break;
            case Lexer::T_SHORT_P:
            case Lexer::T_SHORT_UNICODE_X:
            case Lexer::T_SHORT_X:
                $this->runSubParser('unicode');
                break;
            case Lexer::T_QUANTIFIER_OPEN:
            case Lexer::T_QUANTIFIER_PLUS:
            case Lexer::T_QUANTIFIER_QUESTION:
            case Lexer::T_QUANTIFIER_STAR:
                self::createSubParser('quantifier')->parse($this->left, $this->head, $this->lexer);
                break;
            case Lexer::T_CHOICE_BAR:
                $this->left = $this->head;
                $this->head = new Scope();
                $this->result->useAlternatingStrategy();
                $this->result->attach($this->head);
                break;
        }

        return false;
    }

    /**
     * Executes a named sub-parser and attaches its resulting scope.
     *
     * @param string $name The name of the sub-parser (e.g., 'character', 'short').
     */
    private function runSubParser(string $name): void
    {
        $scope = new LiteralScope();
        self::createSubParser($name)->parse($scope, $this->head, $this->lexer);
        $this->head->attach($scope);
        $this->left = $scope;
    }

    /**
     * Returns the final result of the parsing.
     */
    public function getResult(): Scope
    {
        return $this->result;
    }

    /**
     * Creates and returns an instance of a sub-parser.
     *
     * @param string $name The name of the parser to create.
     * @return StrategyInterface The parser instance.
     * @throws ParserException If the sub-parser is unknown.
     */
    private static function createSubParser(string $name): StrategyInterface
    {
        if (!isset(self::$sub_parsers[$name])) {
            throw new ParserException('Unknown subparser named ' . $name);
        }

        $parserClass = self::$sub_parsers[$name];

        if (is_string($parserClass)) {
            self::$sub_parsers[$name] = new $parserClass();
        }

        return self::$sub_parsers[$name];
    }
}
