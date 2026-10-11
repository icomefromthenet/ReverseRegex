<?php

declare(strict_types=1);

namespace ReverseRegex;

use ReverseRegex\Exception as ParserException;
use ReverseRegex\Generator\LiteralScope;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Parser\StrategyInterface;

/**
 *  Parser to convert regex into Group.
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *
 *  @since 0.0.1
 */
class Parser
{
    protected Lexer $lexer;

    protected Scope $result;

    /**
     * @var Scope the current head
     */
    protected Scope $head;

    /**
     * @var Scope Last attached scope
     */
    protected $left;

    /**
     * @var int maximum number of occurances applied to the `*` and `+` quantifiers
     */
    protected int $unboundedMax = \ReverseRegex\Parser\Quantifier::DEFAULT_UNBOUNDED_MAX;

    /**
     *  Class Constructor.
     */
    public function __construct(Lexer $lexer, Scope $result, ?Scope $head = null)
    {
        $this->lexer = $lexer;
        $this->result = $result;

        if ($head === null) {
            $this->head = new Scope();
        } else {
            $this->head = $head;
        }

        $this->result->attach($this->head);

        $this->left = $this->head;
    }

    /**
     *  Sets the maximum number of occurances generated for `*` and `+`.
     *
     * @return $this
     */
    public function setUnboundedMax(int $max)
    {
        if ($max < 1) {
            throw new ParserException('The maximum of unbounded quantifiers must be at least 1');
        }

        $this->unboundedMax = $max;

        return $this;
    }

    public function getUnboundedMax(): int
    {
        return $this->unboundedMax;
    }

    /**
     *  Fetch the regex lexer.
     */
    public function getLexer(): Lexer
    {
        return $this->lexer;
    }

    /**
     *  Will parse the regex into generator.
     *
     * @param bool $sub
     *
     * @return $this
     */
    public function parse($sub = false)
    {
        try {
            while ($this->lexer->moveNext()) {
                $result = null;
                $scope = null;
                $parser = null;

                switch (true) {
                    case $this->lexer->isNextToken(Lexer::T_GROUP_OPEN) :

                        // is the group character the first token? is the regex wrapped in brackets.
                        // if($this->lexer->token === null) {
                        //  continue;
                        // }

                        // note this is a new group create new parser instance.
                        $parser = new self($this->lexer, new Scope(), new Scope());

                        $this->left = $parser->parse(true)->getResult();
                        $this->head->attach($this->left);

                        break;
                    case $this->lexer->isNextToken(Lexer::T_GROUP_CLOSE) :

                        // group is finished don't want to contine this loop break = 2
                        break 2;
                    case $this->lexer->isNextTokenAny([Lexer::T_LITERAL_CHAR, Lexer::T_LITERAL_NUMERIC]):

                        // test for literal characters (abcd)
                        $this->left = new LiteralScope();
                        $this->left->addLiteral(TokenHelper::value($this->lexer->lookahead));
                        $this->head->attach($this->left);

                        break;
                    case $this->lexer->isNextToken(Lexer::T_SET_OPEN) :

                        // character classes [a-z]
                        $this->left = new LiteralScope();
                        self::createSubParser('character')->parse($this->left, $this->head, $this->lexer);
                        $this->head->attach($this->left);

                        break;
                    case $this->lexer->isNextTokenAny([
                        Lexer::T_DOT,
                        Lexer::T_SHORT_D,
                        Lexer::T_SHORT_NOT_D,
                        Lexer::T_SHORT_W,
                        Lexer::T_SHORT_NOT_W,
                        Lexer::T_SHORT_S,
                        Lexer::T_SHORT_NOT_S]):

                        // match short (. \d \D \w \W \s \S)
                        $this->left = new LiteralScope();
                        self::createSubParser('short')->parse($this->left, $this->head, $this->lexer);
                        $this->head->attach($this->left);

                        break;
                    case $this->lexer->isNextTokenAny([
                        Lexer::T_SHORT_P,
                        Lexer::T_SHORT_UNICODE_X,
                        Lexer::T_SHORT_X]):

                        // match short (\p{L} \x \X  )
                        $this->left = new LiteralScope();
                        self::createSubParser('unicode')->parse($this->left, $this->head, $this->lexer);
                        $this->head->attach($this->left);

                        break;
                    case $this->lexer->isNextTokenAny([
                        Lexer::T_QUANTIFIER_OPEN,
                        Lexer::T_QUANTIFIER_PLUS,
                        Lexer::T_QUANTIFIER_QUESTION,
                        Lexer::T_QUANTIFIER_STAR,
                        Lexer::T_QUANTIFIER_OPEN,
                    ]):

                        // match quantifiers
                        $quantifier = self::createSubParser('quantifer');

                        if ($quantifier instanceof \ReverseRegex\Parser\Quantifier) {
                            $quantifier->setUnboundedMax($this->unboundedMax);
                        }

                        $quantifier->parse($this->left, $this->head, $this->lexer);

                        break;
                    case $this->lexer->isNextToken(Lexer::T_CHOICE_BAR):

                        // match alternations
                        $this->left = $this->head;

                        $this->head = new Scope();
                        $this->result->useAlternatingStrategy();
                        $this->result->attach($this->head);

                        break;
                    default:
                        // ignore character
                }
            }
        } catch (ParserException $e) {
            $pos = TokenHelper::position($this->lexer->lookahead);
            $compressed = $this->compress();

            throw new ParserException(\sprintf('Error found STARTING at position %s after `%s` with msg %s ', $pos, $compressed, $e->getMessage()));
        }

        return $this;
    }

    /**
     *  Compress the lexer into value string until current lookahead.
     *
     * @return string the compressed value string
     */
    public function compress(): string
    {
        $current = TokenHelper::position($this->lexer->lookahead);
        $this->lexer->reset();
        $string = '';

        while ($this->lexer->moveNext() && TokenHelper::position($this->lexer->lookahead) <= $current) {
            $string .= TokenHelper::value($this->lexer->lookahead);
        }

        return $string;
    }

    /**
     *  Return the result of the parse.
     */
    public function getResult(): Scope
    {
        return $this->result;
    }

    /**
     * @var array<string, class-string<StrategyInterface>|StrategyInterface>
     */
    public static $sub_parsers = [
        'character' => \ReverseRegex\Parser\CharacterClass::class,
        'unicode' => \ReverseRegex\Parser\Unicode::class,
        'quantifer' => \ReverseRegex\Parser\Quantifier::class,
        'short' => \ReverseRegex\Parser\Short::class,
    ];

    /**
     *  Return an instance os subparser.
     *
     *  @static
     *
     * @param string $name the short name
     */
    public static function createSubParser(string $name): StrategyInterface
    {
        if (isset(self::$sub_parsers[$name]) === false) {
            throw new ParserException('Unknown subparser at ' . $name);
        }

        if (\is_object(self::$sub_parsers[$name]) === false) {
            self::$sub_parsers[$name] = new self::$sub_parsers[$name]();
        }

        return self::$sub_parsers[$name];
    }
}
// End of File
