<?php

declare(strict_types=1);

namespace ReverseRegex;

use Doctrine\Common\Lexer\AbstractLexer;
use ReverseRegex\Exception as LexerException;

/**
 * Lexer to split a regular expression into a stream of tokens.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 0.0.1
 */
class Lexer extends AbstractLexer
{
    //  ----------------------------------------------------------------------------
    # Char Constants

    /**
     *  @integer an escape character
     */
    public const T_ESCAPE_CHAR = -1;

    /**
     *  The literal type ie a=a ^=^
     */
    public const T_LITERAL_CHAR =  0;

    /**
     *  Numeric literal  1=1 100=100
     */
    public const T_LITERAL_NUMERIC =  1;

    /**
     *  The opening character for group. [(]
     */
    public const T_GROUP_OPEN = 2;

    /**
     *  The closing character for group  [)]
     */
    public const T_GROUP_CLOSE = 3;

    /**
     *  Opening character for Quantifier  ({)
     */
    public const T_QUANTIFIER_OPEN = 4;

    /**
     *   Closing character for Quantifier (})
     */
    public const T_QUANTIFIER_CLOSE = 5;

    /**
     *  Star quantifier character (*)
     */
    public const T_QUANTIFIER_STAR = 6;

    /**
     *  Pluse quantifier character (+)
     */
    public const T_QUANTIFIER_PLUS = 7;

    /**
     *  The one but optonal character (?)
     */
    public const T_QUANTIFIER_QUESTION = 8;

    /**
     *  Start of string character (^)
     */
    public const T_START_CARET  = 9;

    /**
     *  End of string character ($)
     */
    public const T_END_DOLLAR   = 10;

    /**
     *  Range character inside set ([)
     */
    public const T_SET_OPEN     = 11;

    /**
     *  Range character inside set (])
     */
    public const T_SET_CLOSE   = 12;

    /**
     *  Range character inside set (-)
     */
    public const T_SET_RANGE    = 13;

    /**
     *  Negated Character in set ([^)
     */
    public const T_SET_NEGATED  = 14;

    /**
     *  The either character (|)
     */
    public const T_CHOICE_BAR  = 15;

    /**
     *  The dot character (.)
     */
    public const T_DOT         = 16;


    //  ----------------------------------------------------------------------------
    # Shorthand public constants

    /**
     *  One Word boundry
     */
    public const T_SHORT_W     = 100;
    public const T_SHORT_NOT_W = 101;

    public const T_SHORT_D     = 102;
    public const T_SHORT_NOT_D = 103;

    public const T_SHORT_S     = 104;
    public const T_SHORT_NOT_S = 105;

    /**
     *  Unicode sequences /p{} /pNum
     */
    public const T_SHORT_P     = 106;


    /**
     *  Hex Sequences /x{} /xNum
     */
    public const T_SHORT_X     = 108;

    /**
     *  Unicode hex sequence /X{} /XNum
     */
    public const T_SHORT_UNICODE_X = 109;

    //  ----------------------------------------------------------------------------
    # Lexer Modes

    /**
     *  @var boolean The lexer has detected escape character
     */
    protected bool $escape_mode = false;

    /**
     * @var boolean The lexer is parsing a char set
     */
    protected bool $set_mode = false;

    /**
     *  @var integer the number of groups open
     */
    protected int $group_set = 0;

    /**
     *  @var int the number of characters parsed inside the set
     */
    protected int $set_internal_counter = 0;

    public function __construct(string $input)
    {
        $this->setInput($input);
    }

    /**
     * Overrides parent to prime the lookahead and validate syntax immediately.
     */
    public function setInput(string $input): void
    {
        parent::setInput($input);
        $this->moveNext();
    }

    /**
     * {@inheritDoc}
     */
    protected function scan(string $input): void
    {
        # reset default for scan
        $this->group_set   = 0;
        $this->escape_mode = false;
        $this->set_mode    = false;

        parent::scan($input);

        $this->validate();
    }

    /**
     * Run after parsing to validate the state and throw errors for unterminated sections.
     *
     * @throws LexerException
     */
    public function validate(): void
    {
        if ($this->group_set > 0) {
            throw new LexerException('Opening group char "(" has no matching closing character');
        }

        if ($this->group_set < 0) {
            throw new LexerException('Closing group char ")" has no matching opening character');
        }

        if ($this->set_mode) {
            throw new LexerException('Character Class has not been closed');
        }
    }

    /**
     * {@inheritDoc}
     * @return string[]
     */
    protected function getCatchablePatterns(): array
    {
        return ['.'];
    }

    /**
     * {@inheritDoc}
     * @return string[]
     */
    protected function getNonCatchablePatterns(): array
    {
        return ['\s+'];
    }

    /**
     * {@inheritDoc}
     * @param string $value
     * @param-out string $value
     */
    protected function getType(&$value): ?int
    {
        $type = null;
        switch (true) {
            case ($value === '\\' && !$this->escape_mode):
                $this->escape_mode = true;
                $this->incrementInSetCounter();
                $type = self::T_ESCAPE_CHAR;
                break;
            case ($value === '(' && !$this->escape_mode && !$this->set_mode):
                $this->group_set++;
                $type = self::T_GROUP_OPEN;
                break;
            case ($value === ')' && !$this->escape_mode && !$this->set_mode):
                $this->group_set--;
                $type = self::T_GROUP_CLOSE;
                break;
            case ($value === '[' && !$this->escape_mode && $this->set_mode):
                throw new LexerException("Can't have a second character class while first remains open");
            case ($value === ']' && !$this->escape_mode && !$this->set_mode):
                throw new LexerException("Can't close a character class while none is open");
            case ($value === '[' && !$this->escape_mode && !$this->set_mode):
                $this->set_mode = true;
                $this->set_internal_counter = 1;
                $type = self::T_SET_OPEN;
                break;
            case ($value === ']' && !$this->escape_mode && $this->set_mode):
                $this->set_mode = false;
                $this->set_internal_counter = 0;
                $type = self::T_SET_CLOSE;
                break;
            case ($value === '-' && !$this->escape_mode && $this->set_mode):
                $this->set_internal_counter++;
                $type = self::T_SET_RANGE;
                break;
            case ($value === '^' && !$this->escape_mode && $this->set_mode && $this->set_internal_counter === 1):
                $this->set_internal_counter++;
                $type = self::T_SET_NEGATED;
                break;
            case (!$this->set_mode && !$this->escape_mode):
                $type = match ($value) {
                    '{' => self::T_QUANTIFIER_OPEN,
                    '}' => self::T_QUANTIFIER_CLOSE,
                    '*' => self::T_QUANTIFIER_STAR,
                    '+' => self::T_QUANTIFIER_PLUS,
                    '?' => self::T_QUANTIFIER_QUESTION,
                    '.' => self::T_DOT,
                    '|' => self::T_CHOICE_BAR,
                    '^' => self::T_START_CARET,
                    '$' => self::T_END_DOLLAR,
                    default => $this->getDefaultType($value),
                };
                break;
            case ($this->escape_mode):
                $this->escape_mode = false;
                $this->incrementInSetCounter();
                $type = match ($value) {
                    'd' => self::T_SHORT_D,
                    'D' => self::T_SHORT_NOT_D,
                    'w' => self::T_SHORT_W,
                    'W' => self::T_SHORT_NOT_W,
                    's' => self::T_SHORT_S,
                    'S' => self::T_SHORT_NOT_S,
                    'x' => self::T_SHORT_X,
                    'X' => self::T_SHORT_UNICODE_X,
                    'p', 'P' => self::T_SHORT_P,
                    default => $this->getDefaultType($value),
                };
                break;
            default:
                $type = $this->getDefaultType($value);
        }
        return $type;
    }

    private function getDefaultType(string $value): int
    {
        $this->incrementInSetCounter();
        $this->escape_mode = false;
        return is_numeric($value) ? self::T_LITERAL_NUMERIC : self::T_LITERAL_CHAR;
    }

    private function incrementInSetCounter(): void
    {
        if ($this->set_mode) {
            $this->set_internal_counter++;
        }
    }
}
