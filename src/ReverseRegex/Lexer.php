<?php
namespace ReverseRegex;

use Doctrine\Common\Lexer\AbstractLexer as BaseLexer;
use ReverseRegex\Exception as LexerException;

/**
  *  Lexer to split expression syntax
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class Lexer extends BaseLexer
{

    //  ----------------------------------------------------------------------------
    # Char Constants

    /**
      *  @integer an escape character 
      */
    const T_ESCAPE_CHAR = -1;
    
    /**
      *  The literal type ie a=a ^=^
      */
    const T_LITERAL_CHAR =  0;

    /**
      *  Numeric literal  1=1 100=100
      */
    const T_LITERAL_NUMERIC =  1;
    
    /**
      *  The opening character for group. [(]
      */
    const T_GROUP_OPEN = 2;
    
    /**
      *  The closing character for group  [)]
      */
    const T_GROUP_CLOSE = 3;
    
    /**
      *  Opening character for Quantifier  ({)
      */
    const T_QUANTIFIER_OPEN = 4;
    
    /**
      *   Closing character for Quantifier (})
      */
    const T_QUANTIFIER_CLOSE = 5;
    
    /**
      *  Star quantifier character (*)
      */
    const T_QUANTIFIER_STAR = 6;
    
    /**
      *  Pluse quantifier character (+)
      */
    const T_QUANTIFIER_PLUS = 7;
    
    /**
      *  The one but optonal character (?) 
      */
    const T_QUANTIFIER_QUESTION = 8;
    
    /**
      *  Start of string character (^)
      */
    const T_START_CARET  = 9;
    
    /**
      *  End of string character ($)
      */
    const T_END_DOLLAR   = 10;
    
    /**
      *  Range character inside set ([)
      */
    const T_SET_OPEN     = 11;
    
    /**
      *  Range character inside set (])
      */
    const T_SET_CLOSE   = 12;
    
    /**
      *  Range character inside set (-)
      */
    const T_SET_RANGE    = 13;
    
    /**
      *  Negated Character in set ([^) 
      */
    const T_SET_NEGATED  = 14;
    
    /**
      *  The either character (|) 
      */
    const T_CHOICE_BAR  = 15;
    
    /**
      *  The dot character (.) 
      */
    const T_DOT         = 16;
    
    
    //  ----------------------------------------------------------------------------
    # Shorthand constants
    
    /**
      *  One Word boundry
      */
    const T_SHORT_W     = 100;
    const T_SHORT_NOT_W = 101;
 
    const T_SHORT_D     = 102;
    const T_SHORT_NOT_D = 103;
 
    const T_SHORT_S     = 104;
    const T_SHORT_NOT_S = 105;
    
    /**
      *  Unicode sequences /p{} /pNum 
      */
    const T_SHORT_P     = 106;
    
    
    /**
      *  Hex Sequences /x{} /xNum 
      */
    const T_SHORT_X     = 108;
    
    /**
      *  Unicode hex sequence /X{} /XNum 
      */
    const T_SHORT_UNICODE_X = 109;
    
    //  ----------------------------------------------------------------------------
    # Lexer Modes

    /**
      *  @var boolean The lexer has detected escape character 
      */
    protected $escape_mode = false;

    /**
      * @var boolean The lexer is parsing a char set 
      */
    protected $set_mode = false;

    /**
      *  @var integer the number of groups open 
      */    
    protected $group_set = 0;
    
    /**
      *  @var number of characters parsed inside the set 
      */
    protected $set_internal_counter = 0;
    
    
    //  ----------------------------------------------------------------------------
    # Doctrine\Common\Lexer Methods

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
     * @inheritdoc
     */
    protected function getCatchablePatterns()
    {
        return array(
            '.'
        );
    }

    /**
     * @inheritdoc
     */
    protected function getNonCatchablePatterns()
    {
        return array('\s+');
    }

    /**
     * @inheritdoc
     */
    protected function getType(&$value)
    {
        $type = null;

        switch (true) {
            case ($value === '\\' && $this->escape_mode === false) :
                  $this->escape_mode = true;
                  $type = self::T_ESCAPE_CHAR;
                  
                  if($this->set_mode === true) {
                    $this->set_internal_counter++;
                  }
                  
            break;      
            
            // Groups
            case ($value === '(' && $this->escape_mode === false && $this->set_mode === false) :
                  $type = self::T_GROUP_OPEN;
                  $this->group_set++;
            break;
            case ($value === ')' && $this->escape_mode === false && $this->set_mode === false) :
                  $type = self::T_GROUP_CLOSE;
                  $this->group_set--;
            break;
            
            // Charset 
            case ($value === '[' && $this->escape_mode === false && $this->set_mode === true) :
                throw new LexerException("Can't have a second character class while first remains open");
            break;
            case ($value === ']' && $this->escape_mode === false && $this->set_mode === false) :
                throw new LexerException("Can't close a character class while none is open");
            break;
            case ($value === '[' && $this->escape_mode === false && $this->set_mode === false) :
                $this->set_mode = true;
                $type = self::T_SET_OPEN;
                $this->set_internal_counter = 1;
            break;
            case ($value === ']' && $this->escape_mode === false && $this->set_mode === true) :
                $this->set_mode = false;
                $type = self::T_SET_CLOSE;
                $this->set_internal_counter = 0;
            break;
            case ($value === '-' && $this->escape_mode === false && $this->set_mode === true)  :
                $this->set_internal_counter++;
                return self::T_SET_RANGE;
            break;
            case ($value === '^' && $this->escape_mode === false && $this->set_mode === true && $this->set_internal_counter === 1)  :
                $this->set_internal_counter++;
                return self::T_SET_NEGATED; 
            break;
            // Quantifers
            case ($value === '{' && $this->escape_mode === false && $this->set_mode === false) : return self::T_QUANTIFIER_OPEN;
            case ($value === '}' && $this->escape_mode === false && $this->set_mode === false) : return self::T_QUANTIFIER_CLOSE;
            case ($value === '*' && $this->escape_mode === false && $this->set_mode === false) : return self::T_QUANTIFIER_STAR;
            case ($value === '+' && $this->escape_mode === false && $this->set_mode === false) : return self::T_QUANTIFIER_PLUS;
            case ($value === '?' && $this->escape_mode === false && $this->set_mode === false) : return self::T_QUANTIFIER_QUESTION;
        
            // Recognize symbols
            case ($value === '.' && $this->escape_mode === false && $this->set_mode === false) : return self::T_DOT;
            case ($value === '|' && $this->escape_mode === false && $this->set_mode === false) : return self::T_CHOICE_BAR;
            case ($value === '^' && $this->escape_mode === false && $this->set_mode === false) : return self::T_START_CARET;
            case ($value === '$' && $this->escape_mode === false && $this->set_mode === false) : return self::T_END_DOLLAR;    
            
            
            // ShortCodes
            case ($value === 'd' && $this->escape_mode === true) :
                $type = self::T_SHORT_D;
                $this->escape_mode = false;
            break;
            case ($value === 'D' && $this->escape_mode === true) :
                $type = self::T_SHORT_NOT_D;
                $this->escape_mode = false;
            break;
            case ($value === 'w' && $this->escape_mode === true) :
                $type = self::T_SHORT_W;
                $this->escape_mode = false;
            break;
            case ($value === 'W' && $this->escape_mode === true) :
                $type = self::T_SHORT_NOT_W;
                $this->escape_mode = false;
            break;
            case ($value === 's' && $this->escape_mode === true) :
                $type = self::T_SHORT_S;
                $this->escape_mode = false;
            break;
            case ($value === 'S' && $this->escape_mode === true) :
                $type = self::T_SHORT_NOT_S;
                $this->escape_mode = false;
            break;
            case ($value === 'x' && $this->escape_mode === true) :
                $type = self::T_SHORT_X;
                $this->escape_mode = false;
                
                if($this->set_mode === true) {
                    $this->set_internal_counter++;
                }
                
            break;
            case ($value === 'X' && $this->escape_mode === true) :
                $type = self::T_SHORT_UNICODE_X;
                $this->escape_mode = false;
                
                if($this->set_mode === true) {
                    $this->set_internal_counter++;
                }
                
            break;
            case (($value === 'p' || $value === 'P') && $this->escape_mode === true) :
                $type = self::T_SHORT_P;
                $this->escape_mode = false;
                
                if($this->set_mode === true) {
                    $this->set_internal_counter++;
                }
                
            break;
                            
            // Default 
            default :
                if(is_numeric($value) === true) {
                    $type = self::T_LITERAL_NUMERIC;
                } else {
                    $type = self::T_LITERAL_CHAR;    
                }
                
                if($this->set_mode === true) {
                    $this->set_internal_counter++;
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
        # reset default for scan
        $this->group_set   = 0;
        $this->escape_mode = false;
        $this->set_mode    = false;
        
        static $regex;

        if ( ! isset($regex)) {
            $regex = '/(' . implode(')|(', $this->getCatchablePatterns()) . ')|'
                   . implode('|', $this->getNonCatchablePatterns()) . '/ui';
        }

        parent::scan($input);
        

        if($this->group_set > 0) {
            throw new LexerException('Opening group char "(" has no matching closing character');
        }
        
        if($this->group_set < 0) {
            throw new LexerException('Closing group char "(" has no matching opening character');
        }
        
        if($this->set_mode === true) {
            throw new LexerException('Character Class that been closed');
        }
        
    }
    
}
/* End of File */
