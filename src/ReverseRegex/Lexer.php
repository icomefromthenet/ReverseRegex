<?php

declare(strict_types=1);

namespace ReverseRegex;

use Doctrine\Common\Lexer\AbstractLexer;
use ReverseRegex\Exception as LexerException;

/**
 *  Lexer to split expression syntax.
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *
 *  @since 0.0.1
 */
class Lexer extends AbstractLexer
{
    //  ----------------------------------------------------------------------------
    // Char Constants

    /**
     *  @integer an escape character
     */
    public const T_ESCAPE_CHAR = -1;

    /**
     *  The literal type ie a=a ^=^.
     */
    public const T_LITERAL_CHAR = 0;

    /**
     *  Numeric literal  1=1 100=100.
     */
    public const T_LITERAL_NUMERIC = 1;

    /**
     *  The opening character for group. [(].
     */
    public const T_GROUP_OPEN = 2;

    /**
     *  The closing character for group  [)].
     */
    public const T_GROUP_CLOSE = 3;

    /**
     *  Opening character for Quantifier  ({).
     */
    public const T_QUANTIFIER_OPEN = 4;

    /**
     *   Closing character for Quantifier (}).
     */
    public const T_QUANTIFIER_CLOSE = 5;

    /**
     *  Star quantifier character (*).
     */
    public const T_QUANTIFIER_STAR = 6;

    /**
     *  Pluse quantifier character (+).
     */
    public const T_QUANTIFIER_PLUS = 7;

    /**
     *  The one but optonal character (?).
     */
    public const T_QUANTIFIER_QUESTION = 8;

    /**
     *  Start of string character (^).
     */
    public const T_START_CARET = 9;

    /**
     *  End of string character ($).
     */
    public const T_END_DOLLAR = 10;

    /**
     *  Range character inside set ([).
     */
    public const T_SET_OPEN = 11;

    /**
     *  Range character inside set (]).
     */
    public const T_SET_CLOSE = 12;

    /**
     *  Range character inside set (-).
     */
    public const T_SET_RANGE = 13;

    /**
     *  Negated Character in set ([^).
     */
    public const T_SET_NEGATED = 14;

    /**
     *  The either character (|).
     */
    public const T_CHOICE_BAR = 15;

    /**
     *  The dot character (.).
     */
    public const T_DOT = 16;

    //  ----------------------------------------------------------------------------
    // Shorthand constants

    /**
     *  One Word boundry.
     */
    public const T_SHORT_W = 100;

    public const T_SHORT_NOT_W = 101;

    public const T_SHORT_D = 102;

    public const T_SHORT_NOT_D = 103;

    public const T_SHORT_S = 104;

    public const T_SHORT_NOT_S = 105;

    /**
     *  Unicode sequences /p{} /pNum.
     */
    public const T_SHORT_P = 106;

    /**
     *  Hex Sequences /x{} /xNum.
     */
    public const T_SHORT_X = 108;

    /**
     *  Unicode hex sequence /X{} /XNum.
     */
    public const T_SHORT_UNICODE_X = 109;

    //  ----------------------------------------------------------------------------
    // Lexer Modes

    /**
     *  @var bool The lexer has detected escape character
     */
    protected $escape_mode = false;

    /**
     * @var bool The lexer is parsing a char set
     */
    protected $set_mode = false;

    /**
     *  @var int the number of groups open
     */
    protected $group_set = 0;

    /**
     *  @var number of characters parsed inside the set
     */
    protected $set_internal_counter = 0;

    //  ----------------------------------------------------------------------------
    // Doctrine\Common\Lexer Methods

    /**
     * Creates a new query scanner object.
     *
     * @param string $input a query string
     */
    public function __construct($input)
    {
        $this->setInput($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function getCatchablePatterns()
    {
        return [
            '.',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getNonCatchablePatterns()
    {
        return ['\s+'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getType(&$value)
    {
        $type = null;

        switch (true) {
            case '\\' === $value && false === $this->escape_mode:
                  $this->escape_mode = true;
                  $type = self::T_ESCAPE_CHAR;

                  if (true === $this->set_mode) {
                      ++$this->set_internal_counter;
                  }

            break;

            // Groups
            case '(' === $value && false === $this->escape_mode && false === $this->set_mode:
                  $type = self::T_GROUP_OPEN;
                  ++$this->group_set;
            break;
            case ')' === $value && false === $this->escape_mode && false === $this->set_mode:
                  $type = self::T_GROUP_CLOSE;
                  --$this->group_set;
            break;

            // Charset
            case '[' === $value && false === $this->escape_mode && true === $this->set_mode:
                throw new LexerException("Can't have a second character class while first remains open");
            break;
            case ']' === $value && false === $this->escape_mode && false === $this->set_mode:
                throw new LexerException("Can't close a character class while none is open");
            break;
            case '[' === $value && false === $this->escape_mode && false === $this->set_mode:
                $this->set_mode = true;
                $type = self::T_SET_OPEN;
                $this->set_internal_counter = 1;
            break;
            case ']' === $value && false === $this->escape_mode && true === $this->set_mode:
                $this->set_mode = false;
                $type = self::T_SET_CLOSE;
                $this->set_internal_counter = 0;
            break;
            case '-' === $value && false === $this->escape_mode && true === $this->set_mode:
                $this->set_internal_counter++;

                return self::T_SET_RANGE;
            break;
            case '^' === $value && false === $this->escape_mode && true === $this->set_mode && 1 === $this->set_internal_counter:
                $this->set_internal_counter++;

                return self::T_SET_NEGATED;
            break;
            // Quantifers
            case '{' === $value && false === $this->escape_mode && false === $this->set_mode: return self::T_QUANTIFIER_OPEN;
            case '}' === $value && false === $this->escape_mode && false === $this->set_mode: return self::T_QUANTIFIER_CLOSE;
            case '*' === $value && false === $this->escape_mode && false === $this->set_mode: return self::T_QUANTIFIER_STAR;
            case '+' === $value && false === $this->escape_mode && false === $this->set_mode: return self::T_QUANTIFIER_PLUS;
            case '?' === $value && false === $this->escape_mode && false === $this->set_mode: return self::T_QUANTIFIER_QUESTION;

            // Recognize symbols
            case '.' === $value && false === $this->escape_mode && false === $this->set_mode: return self::T_DOT;
            case '|' === $value && false === $this->escape_mode && false === $this->set_mode: return self::T_CHOICE_BAR;
            case '^' === $value && false === $this->escape_mode && false === $this->set_mode: return self::T_START_CARET;
            case '$' === $value && false === $this->escape_mode && false === $this->set_mode: return self::T_END_DOLLAR;

            // ShortCodes
            case 'd' === $value && true === $this->escape_mode:
                $type = self::T_SHORT_D;
                $this->escape_mode = false;
            break;
            case 'D' === $value && true === $this->escape_mode:
                $type = self::T_SHORT_NOT_D;
                $this->escape_mode = false;
            break;
            case 'w' === $value && true === $this->escape_mode:
                $type = self::T_SHORT_W;
                $this->escape_mode = false;
            break;
            case 'W' === $value && true === $this->escape_mode:
                $type = self::T_SHORT_NOT_W;
                $this->escape_mode = false;
            break;
            case 's' === $value && true === $this->escape_mode:
                $type = self::T_SHORT_S;
                $this->escape_mode = false;
            break;
            case 'S' === $value && true === $this->escape_mode:
                $type = self::T_SHORT_NOT_S;
                $this->escape_mode = false;
            break;
            case 'x' === $value && true === $this->escape_mode:
                $type = self::T_SHORT_X;
                $this->escape_mode = false;

                if (true === $this->set_mode) {
                    ++$this->set_internal_counter;
                }

            break;
            case 'X' === $value && true === $this->escape_mode:
                $type = self::T_SHORT_UNICODE_X;
                $this->escape_mode = false;

                if (true === $this->set_mode) {
                    ++$this->set_internal_counter;
                }

            break;
            case ('p' === $value || 'P' === $value) && true === $this->escape_mode:
                $type = self::T_SHORT_P;
                $this->escape_mode = false;

                if (true === $this->set_mode) {
                    ++$this->set_internal_counter;
                }

            break;

            // Default
            default:
                if (true === is_numeric($value)) {
                    $type = self::T_LITERAL_NUMERIC;
                } else {
                    $type = self::T_LITERAL_CHAR;
                }

                if (true === $this->set_mode) {
                    ++$this->set_internal_counter;
                }

                $this->escape_mode = false;
        }

        return $type;
    }

    /**
     * Scans the input string for tokens.
     *
     * @param string $input a query string
     */
    protected function scan($input)
    {
        // reset default for scan
        $this->group_set = 0;
        $this->escape_mode = false;
        $this->set_mode = false;

        static $regex;

        if (!isset($regex)) {
            $regex = '/('.implode(')|(', $this->getCatchablePatterns()).')|'
                   .implode('|', $this->getNonCatchablePatterns()).'/ui';
        }

        parent::scan($input);

        if ($this->group_set > 0) {
            throw new LexerException('Opening group char "(" has no matching closing character');
        }

        if ($this->group_set < 0) {
            throw new LexerException('Closing group char "(" has no matching opening character');
        }

        if (true === $this->set_mode) {
            throw new LexerException('Character Class that been closed');
        }
    }
}
